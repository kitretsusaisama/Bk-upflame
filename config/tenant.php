<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Tenant Identification Strategy
    |--------------------------------------------------------------------------
    |
    | This option controls how tenants are identified in incoming requests.
    | Supported strategies: 'domain', 'subdomain', 'header', 'path'
    |
    */
    'identification_strategy' => env('TENANT_IDENTIFICATION_STRATEGY', 'domain'),

    /*
    |--------------------------------------------------------------------------
    | Default Tenant Limits
    |--------------------------------------------------------------------------
    |
    | These values define the default limits for tenants.
    |
    */
    'default_limits' => [
        'users' => env('TENANT_DEFAULT_USER_LIMIT', 100),
        'providers' => env('TENANT_DEFAULT_PROVIDER_LIMIT', 50),
        'bookings' => env('TENANT_DEFAULT_BOOKING_LIMIT', 1000),
        'workflows' => env('TENANT_DEFAULT_WORKFLOW_LIMIT', 20),
    ],

    /*
    |--------------------------------------------------------------------------
    | Tenant Isolation Rules
    |--------------------------------------------------------------------------
    |
    | These rules enforce tenant data isolation.
    |
    */
    'isolation' => [
        'enforce_strict_isolation' => env('TENANT_STRICT_ISOLATION', true),
        'cross_tenant_queries_allowed' => env('TENANT_CROSS_QUERIES_ALLOWED', false),
    ],

    /*
    |--------------------------------------------------------------------------
    | Domain Mapping Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for custom domain mappings.
    |
    */
    'domain_mapping' => [
        'enabled' => env('TENANT_DOMAIN_MAPPING_ENABLED', true),
        'verification_required' => env('TENANT_DOMAIN_VERIFICATION_REQUIRED', true),
    ],

    /*
    |--------------------------------------------------------------------------
    | Tenant Statuses
    |--------------------------------------------------------------------------
    |
    | Valid tenant statuses.
    |
    */
    'statuses' => [
        'pending',
        'active',
        'suspended',
        'deleted',
    ],

    /*
    |--------------------------------------------------------------------------
    | Tenant Subscription Tiers
    |--------------------------------------------------------------------------
    |
    | Available subscription tiers for tenants.
    |
    */
    'subscription_tiers' => [
        'free' => [
            'name' => 'Free',
            'limits' => [
                'users' => 5,
                'providers' => 3,
                'bookings' => 100,
                'workflows' => 2,
            ],
        ],
        'basic' => [
            'name' => 'Basic',
            'limits' => [
                'users' => 25,
                'providers' => 10,
                'bookings' => 500,
                'workflows' => 5,
            ],
        ],
        'premium' => [
            'name' => 'Premium',
            'limits' => [
                'users' => 100,
                'providers' => 50,
                'bookings' => 5000,
                'workflows' => 20,
            ],
        ],
        'enterprise' => [
            'name' => 'Enterprise',
            'limits' => [
                'users' => 1000,
                'providers' => 200,
                'bookings' => 50000,
                'workflows' => 100,
            ],
        ],
    ],
];