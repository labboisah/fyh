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
        // Mark sync operation as being processed
        $this->syncOperation->incrementAttempts();

        $endpoint = config('sync.remote.endpoint');
        $token = config('sync.remote.token');

        if (!$endpoint || !$token) {
            Log::warning('Sync not configured: missing remote endpoint or token');
            if (!$this->syncOperation->shouldRetry()) {
                $this->syncOperation->markFailed('Sync not configured');
            }
            return;
        }

        try {
            $payload = [
                'sync_uuid' => $this->syncOperation->sync_uuid,
                'model_type' => $this->syncOperation->model_type,
                'operation' => $this->syncOperation->operation,
                'payload' => $this->syncOperation->payload,
                'origin' => config('sync.environment'),
                'timestamp' => now()->toIso8601String(),
            ];

            $response = Http::withToken($token)
                ->timeout(config('sync.remote.timeout', 30))
                ->post("{$endpoint}/api/v1/sync/records", $payload);

            if ($response->successful()) {
                $data = $response->json();
                $remoteId = $data['remote_id'] ?? null;
                $this->syncOperation->markSynced($remoteId);

                Log::info('Sync successful', [
                    'sync_uuid' => $this->syncOperation->sync_uuid,
                    'model_type' => $this->syncOperation->model_type,
                    'operation' => $this->syncOperation->operation,
                ]);

                return;
            }

            // Handle failed response
            $errorMessage = $response->json('message') ?? $response->body();
            Log::warning('Sync failed with response error', [
                'sync_uuid' => $this->syncOperation->sync_uuid,
                'status' => $response->status(),
                'error' => $errorMessage,
            ]);

            if ($this->syncOperation->shouldRetry()) {
                // Retry by re-queueing the job
                $this->release(config('sync.queue.retry_after', 300));
            } else {
                $this->syncOperation->markFailed($errorMessage);
            }

        } catch (\Exception $e) {
            Log::error('Sync exception', [
                'sync_uuid' => $this->syncOperation->sync_uuid,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            if ($this->syncOperation->shouldRetry()) {
                // Retry by re-queueing the job
                $this->release(config('sync.queue.retry_after', 300));
            } else {
                $this->syncOperation->markFailed($e->getMessage());
            }
        }
    }

    /**
     * Handle job failure
     */
    public function failed(\Throwable $exception): void
    {
        Log::error('SyncRecordJob failed permanently', [
            'sync_uuid' => $this->syncOperation->sync_uuid,
            'error' => $exception->getMessage(),
        ]);

        $this->syncOperation->markFailed($exception->getMessage());
    }
}
