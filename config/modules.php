<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Enabled Modules
    |--------------------------------------------------------------------------
    |
    | List of modules that are enabled in the application.
    |
    */
    'enabled' => [
        'tenant',
        'identity',
        'sso',
        'authorization',
        'provider',
        'booking',
        'workflow',
        'notification',
        'menu',
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Permissions
    |--------------------------------------------------------------------------
    |
    | Default permissions for each module.
    |
    */
    'permissions' => [
        'tenant' => [
            'view',
            'create',
            'update',
            'delete',
            'manage_settings',
        ],
        'identity' => [
            'view_user',
            'create_user',
            'update_user',
            'delete_user',
            'manage_profile',
            'manage_sessions',
        ],
        'sso' => [
            'view_provider',
            'create_provider',
            'update_provider',
            'delete_provider',
            'manage_connections',
        ],
        'authorization' => [
            'view_role',
            'create_role',
            'update_role',
            'delete_role',
            'assign_role',
            'view_permission',
            'create_permission',
            'update_permission',
            'delete_permission',
            'assign_permission',
        ],
        'provider' => [
            'view',
            'create',
            'update',
            'delete',
            'manage_availability',
            'manage_specialties',
            'manage_documents',
            'manage_ratings',
        ],
        'booking' => [
            'view',
            'create',
            'update',
            'delete',
            'cancel',
            'reschedule',
            'manage_history',
        ],
        'workflow' => [
            'view',
            'create',
            'update',
            'delete',
            'execute',
            'manage_steps',
            'manage_forms',
            'manage_transitions',
        ],
        'notification' => [
            'view',
            'send',
            'delete',
            'manage_templates',
            'manage_preferences',
        ],
        'menu' => [
            'view',
            'create',
            'update',
            'delete',
            'manage',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Settings
    |--------------------------------------------------------------------------
    |
    | Configuration settings for each module.
    |
    */
    'settings' => [
        'tenant' => [
            'max_domains_per_tenant' => env('MODULE_TENANT_MAX_DOMAINS', 5),
            'default_subscription_tier' => env('MODULE_TENANT_DEFAULT_TIER', 'free'),
        ],
        'provider' => [
            'max_specialties_per_provider' => env('MODULE_PROVIDER_MAX_SPECIALTIES', 10),
            'require_document_verification' => env('MODULE_PROVIDER_REQUIRE_VERIFICATION', true),
        ],
        'booking' => [
            'min_booking_notice' => env('MODULE_BOOKING_MIN_NOTICE', 60), // minutes
            'max_booking_duration' => env('MODULE_BOOKING_MAX_DURATION', 240), // minutes
            'cancellation_fee_percentage' => env('MODULE_BOOKING_CANCELLATION_FEE', 10), // percentage
        ],
        'workflow' => [
            'max_steps_per_workflow' => env('MODULE_WORKFLOW_MAX_STEPS', 20),
            'max_forms_per_workflow' => env('MODULE_WORKFLOW_MAX_FORMS', 10),
        ],
        'notification' => [
            'max_recipients_per_batch' => env('MODULE_NOTIFICATION_MAX_RECIPIENTS', 1000),
            'retry_attempts' => env('MODULE_NOTIFICATION_RETRY_ATTEMPTS', 3),
        ],
        'menu' => [
            'max_levels' => env('MODULE_MENU_MAX_LEVELS', 3),
            'cache_ttl' => env('MODULE_MENU_CACHE_TTL', 3600), // seconds
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Dependencies
    |--------------------------------------------------------------------------
    |
    | Define dependencies between modules.
    |
    */
    'dependencies' => [
        'booking' => ['provider', 'identity'],
        'workflow' => ['identity'],
        'notification' => ['identity'],
        'sso' => ['identity'],
        'authorization' => ['identity'],
        'menu' => ['identity', 'tenant'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Module Routes
    |--------------------------------------------------------------------------
    |
    | Configuration for module routes.
    |
    */
    'routes' => [
        'prefix' => env('MODULE_ROUTE_PREFIX', 'api/v1'),
        'middleware' => ['api', 'tenant'],
    ],
];