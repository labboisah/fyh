<?php

namespace App\Jobs;

use App\Models\SyncOperation;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class SyncRecordJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected SyncOperation $syncOperation;

    public function __construct(SyncOperation $syncOperation)
    {
        $this->syncOperation = $syncOperation;
    }

    /**
     * Execute the job
     */
    public function handle(): void
    {
        if (!$this->syncOperation->isPending()) {
            return;
        }

        $this->syncOperation->incrementAttempts();

        $endpoint = config('sync.remote.endpoint');
        $token = config('sync.remote.token');

        if (!$endpoint || !$token) {
            $this->markFailed('Sync not configured: missing remote endpoint or token');
            return;
        }

        $payload = $this->syncOperation->payload ?? [];
        $operation = $this->resolveRemoteOperation();

        if ($operation === null) {
            return;
        }

        if ($operation !== 'delete' && !$this->dependenciesReady($payload)) {
            return;
        }

        $requestPayload = [
            'sync_uuid' => $this->syncOperation->sync_uuid,
            'model_type' => $this->syncOperation->model_type,
            'operation' => $operation,
            'payload' => $payload,
            'origin' => config('sync.environment'),
            'timestamp' => now()->toIso8601String(),
        ];

        Log::info('Sync start', [
            'sync_uuid' => $this->syncOperation->sync_uuid,
            'model_type' => $this->syncOperation->model_type,
            'operation' => $operation,
            'attempt' => $this->syncOperation->attempts,
        ]);

        try {
            $response = Http::withToken($token)
                ->timeout(config('sync.remote.timeout', 30))
                ->post("{$endpoint}/api/v1/sync/records", $requestPayload);

            if ($response->successful()) {
                $remoteId = $response->json('remote_id');
                $this->syncOperation->markSynced($remoteId);

                Log::info('Sync successful', [
                    'sync_uuid' => $this->syncOperation->sync_uuid,
                    'model_type' => $this->syncOperation->model_type,
                    'operation' => $operation,
                ]);

                return;
            }

            $errorMessage = $response->json('message') ?? $response->body();
            Log::warning('Sync failed with response error', [
                'sync_uuid' => $this->syncOperation->sync_uuid,
                'model_type' => $this->syncOperation->model_type,
                'status' => $response->status(),
                'error' => $errorMessage,
            ]);

            $this->retryOrFail($errorMessage);
        } catch (Throwable $exception) {
            Log::error('Sync exception', [
                'sync_uuid' => $this->syncOperation->sync_uuid,
                'model_type' => $this->syncOperation->model_type,
                'error' => $exception->getMessage(),
                'trace' => $exception->getTraceAsString(),
            ]);

            $this->retryOrFail($exception->getMessage());
        }
    }

    private function resolveRemoteOperation(): ?string
    {
        if ($this->syncOperation->operation === 'delete') {
            return 'delete';
        }

        $remoteExists = $this->remoteRecordExists($this->syncOperation->sync_uuid);

        if ($remoteExists === null) {
            return null;
        }

        return $remoteExists ? 'update' : 'create';
    }

    private function dependenciesReady(array $payload): bool
    {
        $rules = $this->dependencyRules();
        $modelType = $this->syncOperation->model_type;
        $dependencies = $rules[$modelType] ?? [];

        foreach ($dependencies as $payloadKey => $dependencyClass) {
            if (empty($payload[$payloadKey])) {
                $this->retryLater("Dependency payload missing: {$payloadKey}");
                return false;
            }

            $exists = $this->remoteRecordExists($payload[$payloadKey]);

            if ($exists === null) {
                return false;
            }

            if (! $exists) {
                $this->retryLater("Dependency not ready remotely: {$payloadKey}");
                return false;
            }
        }

        return true;
    }

    private function dependencyRules(): array
    {
        return [
            \App\Models\PatientVisit::class => [
                'patient_sync_uuid' => \App\Models\Patient::class,
            ],
            \App\Models\Bill::class => [
                'patient_visit_sync_uuid' => \App\Models\PatientVisit::class,
            ],
            \App\Models\Payment::class => [
                'bill_sync_uuid' => \App\Models\Bill::class,
            ],
        ];
    }

    private function remoteRecordExists(string $syncUuid): ?bool
    {
        $endpoint = config('sync.remote.endpoint');
        $token = config('sync.remote.token');

        if (!$endpoint || !$token) {
            $this->markFailed('Sync not configured: missing remote endpoint or token');
            return null;
        }

        try {
            $response = Http::withToken($token)
                ->timeout(config('sync.remote.timeout', 30))
                ->get("{$endpoint}/api/v1/sync/status/{$syncUuid}");

            if ($response->status() === 200) {
                return true;
            }

            if ($response->status() === 404) {
                return false;
            }

            Log::warning('Remote record existence check returned unexpected status', [
                'sync_uuid' => $syncUuid,
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            $this->retryLater('Unable to determine remote record existence');
            return null;
        } catch (Throwable $exception) {
            Log::warning('Remote record existence check failed', [
                'sync_uuid' => $syncUuid,
                'error' => $exception->getMessage(),
            ]);

            $this->retryLater('Remote status check failed');
            return null;
        }
    }

    private function retryLater(string $reason): void
    {
        if ($this->syncOperation->shouldRetry()) {
            $delay = config('sync.queue.dependency_delay', 10);

            Log::info('Sync dependency deferred', [
                'sync_uuid' => $this->syncOperation->sync_uuid,
                'model_type' => $this->syncOperation->model_type,
                'reason' => $reason,
                'attempts' => $this->syncOperation->attempts,
            ]);

            $this->release($delay);
            return;
        }

        $this->syncOperation->markFailed($reason);
    }

    private function retryOrFail(string $message): void
    {
        if ($this->syncOperation->shouldRetry()) {
            $delay = $this->retryDelay();
            $this->release($delay);
            return;
        }

        $this->syncOperation->markFailed($message);
    }

    private function retryDelay(): int
    {
        return config('sync.queue.retry_after', 300) * max(1, $this->syncOperation->attempts);
    }

    /**
     * Handle job failure
     */
    public function failed(Throwable $exception): void
    {
        Log::error('SyncRecordJob failed permanently', [
            'sync_uuid' => $this->syncOperation->sync_uuid,
            'model_type' => $this->syncOperation->model_type,
            'error' => $exception->getMessage(),
        ]);

        $this->syncOperation->markFailed($exception->getMessage());
    }
}
