<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Idle Timeout
    |--------------------------------------------------------------------------
    |
    | The number of minutes a user can be idle before being automatically
    | logged out. Set to 0 to disable idle timeout.
    |
    */
    'idle_timeout' => env('DASHBOARD_IDLE_TIMEOUT', 30),

    /*
    |--------------------------------------------------------------------------
    | Session Limit
    |--------------------------------------------------------------------------
    |
    | The maximum number of concurrent sessions a user can have.
    | Set to 0 for unlimited sessions.
    |
    */
    'session_limit' => env('DASHBOARD_SESSION_LIMIT', 5),

    /*
    |--------------------------------------------------------------------------
    | Role Priority Override
    |--------------------------------------------------------------------------
    |
    | Allow database-driven role priority to override hardcoded values.
    |
    */
    'role_priority_override' => env('DASHBOARD_ROLE_PRIORITY_OVERRIDE', true),

    /*
    |--------------------------------------------------------------------------
    | Widget Cache TTL
    |--------------------------------------------------------------------------
    |
    | Time-to-live for widget data cache in seconds.
    |
    */
    'widget_cache_ttl' => env('DASHBOARD_WIDGET_CACHE_TTL', 300),

    /*
    |--------------------------------------------------------------------------
    | Inactivity Warning Time
    |--------------------------------------------------------------------------
    |
    | Show warning popup at this percentage of idle timeout.
    | Example: 80 means warning shows at 80% of idle timeout period.
    |
    */
    'inactivity_warning_percent' => env('DASHBOARD_INACTIVITY_WARNING_PERCENT', 80),
];
