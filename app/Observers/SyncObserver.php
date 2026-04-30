<?php

namespace App\Observers;

use App\Jobs\SyncRecordJob;
use Illuminate\Database\Eloquent\Model;

class SyncObserver
{
    /**
     * Handle the model "created" event.
     */
    public function created(Model $model): void
    {
        if (!config('sync.behavior.auto_sync_enabled')) {
            return;
        }

        $this->dispatchSyncJob($model, 'create');
    }

    /**
     * Handle the model "updated" event.
     */
    public function updated(Model $model): void
    {
        if (!config('sync.behavior.auto_sync_enabled')) {
            return;
        }

        // Don't sync if only sync metadata changed
        $syncFields = ['sync_uuid', 'sync_status', 'sync_origin', 'sync_updated_at', 'remote_id'];
        $changedFields = array_keys($model->getChanges());
        $realChanges = array_filter($changedFields, fn($f) => !in_array($f, $syncFields));

        if (count($realChanges) > 0) {
            $this->dispatchSyncJob($model, 'update');
        }
    }

    /**
     * Handle the model "deleted" event.
     */
    public function deleted(Model $model): void
    {
        if (!config('sync.behavior.auto_sync_enabled')) {
            return;
        }

        $this->dispatchSyncJob($model, 'delete');
    }

    /**
     * Dispatch sync job for the model
     */
    private function dispatchSyncJob(Model $model, string $operation): void
    {
        // Ensure model has sync UUID
        if (method_exists($model, 'ensureSyncUuid')) {
            $model->ensureSyncUuid();
        }

        $syncOperation = $model->createSyncOperation($operation);

        // Dispatch the sync job
        SyncRecordJob::dispatch($syncOperation)
            ->onQueue(config('sync.queue.name', 'sync'))
            ->onConnection(config('sync.queue.connection', 'database'));
    }
}
