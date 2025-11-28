<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Sidebar Caching Configuration
    |--------------------------------------------------------------------------
    */
    'cache' => [
        'enabled' => env('SIDEBAR_CACHE_ENABLED', true),
        'driver' => env('SIDEBAR_CACHE_DRIVER', config('cache.default', 'file')),
        'ttl' => env('SIDEBAR_CACHE_TTL', 600),
        'prefix' => env('SIDEBAR_CACHE_PREFIX', 'sidebar_menu'),
        'tags_enabled' => env('SIDEBAR_CACHE_TAGS', false), // Only enable if using Redis/Memcached
    ],

    /*
    |--------------------------------------------------------------------------
    | Performance Configuration
    |--------------------------------------------------------------------------
    */
    'performance' => [
        'request_cache' => env('SIDEBAR_REQUEST_CACHE', true),
        'eager_load' => ['roles', 'roles.permissions', 'tenant'],
        'max_items' => env('SIDEBAR_MAX_ITEMS', 50),
    ],

    /*
    |--------------------------------------------------------------------------
    | Error Handling Configuration
    |--------------------------------------------------------------------------
    */
    'error' => [
        'graceful_degradation' => env('SIDEBAR_GRACEFUL_DEGRADATION', true),
        'logging_enabled' => env('SIDEBAR_ERROR_LOGGING', true),
        'log_channel' => env('SIDEBAR_LOG_CHANNEL', 'stack'),
        'fallback_items' => [],
    ],

    /*
    |--------------------------------------------------------------------------
    | Security Configuration
    |--------------------------------------------------------------------------
    */
    'security' => [
        'require_auth' => env('SIDEBAR_REQUIRE_AUTH', true),
        'check_user_status' => env('SIDEBAR_CHECK_STATUS', true),
        'max_cache_age' => env('SIDEBAR_MAX_CACHE_AGE', 3600),
    ],

    /*
    |--------------------------------------------------------------------------
    | View Composer Configuration
    |--------------------------------------------------------------------------
    */
    'composer' => [
        'target_views' => [
            'dashboard.layout',
            'dashboard.partials.sidebar',
        ],
        'variable_name' => 'menuItems',
    ],

    /*
    |--------------------------------------------------------------------------
    | Monitoring Configuration
    |--------------------------------------------------------------------------
    */
    'monitoring' => [
        'enabled' => env('SIDEBAR_MONITORING_ENABLED', env('APP_ENV') === 'production'),
        'slow_threshold_ms' => env('SIDEBAR_SLOW_THRESHOLD', 100),
        'report_to_apm' => env('SIDEBAR_APM_ENABLED', false),
    ],
];
