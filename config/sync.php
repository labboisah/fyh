<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sync Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for bidirectional synchronization between local and online servers
    |
    */

    'enabled' => env('SYNC_ENABLED', true),

    /*
    |--------------------------------------------------------------------------
    | Current environment
    |--------------------------------------------------------------------------
    | 'local' for hospital local server
    | 'online' for online platform
    */
    'environment' => env('SYNC_ENVIRONMENT', 'local'),

    /*
    |--------------------------------------------------------------------------
    | Remote endpoints
    |--------------------------------------------------------------------------
    */
    'remote' => [
        // Endpoint URL for the remote server
        'endpoint' => env('SYNC_REMOTE_ENDPOINT'),
        // API token for authentication with remote
        'token' => env('SYNC_REMOTE_TOKEN'),
        // Timeout in seconds
        'timeout' => (int) env('SYNC_REMOTE_TIMEOUT', 30),
    ],

    /*
    |--------------------------------------------------------------------------
    | Local API Token
    |--------------------------------------------------------------------------
    | Token used by remote server to authenticate sync requests to this server
    */
    'token' => env('SYNC_API_TOKEN'),

    /*
    |--------------------------------------------------------------------------
    | Queue settings
    |--------------------------------------------------------------------------
    */
    'queue' => [
        'connection' => env('SYNC_QUEUE_CONNECTION', 'database'),
        'name' => env('SYNC_QUEUE_NAME', 'sync'),
        'retry_after' => (int) env('SYNC_QUEUE_RETRY_AFTER', 300),
        'max_attempts' => (int) env('SYNC_MAX_ATTEMPTS', 5),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sync behavior
    |--------------------------------------------------------------------------
    */
    'behavior' => [
        // 'last_write_wins' or 'origin_precedence'
        'conflict_resolution' => env('SYNC_CONFLICT_RESOLUTION', 'last_write_wins'),
        // Should we accept inbound deletes
        'allow_remote_deletes' => env('SYNC_ALLOW_REMOTE_DELETES', false),
        // Auto-sync enabled
        'auto_sync_enabled' => env('SYNC_AUTO_SYNC_ENABLED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Models to sync
    |--------------------------------------------------------------------------
    | List of models that participate in bidirectional sync
    */
    'syncable_models' => [
        'App\Models\Patient',
        'App\Models\Admission',
        'App\Models\AntenatalCare',
        'App\Models\Labour',
        'App\Models\Delivery',
        'App\Models\InvestigationRequest',
        'App\Models\Prescription',
        'App\Models\NewbornExamination',
        'App\Models\ChildFollowUp',
    ],

    /*
    |--------------------------------------------------------------------------
    | Fields to exclude from sync
    |--------------------------------------------------------------------------
    */
    'excluded_fields' => [
        'password',
        'remember_token',
        'api_token',
        'two_factor_secret',
        'api_secret',
    ],
];
