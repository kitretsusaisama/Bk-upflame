<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Permission Cache TTL
    |--------------------------------------------------------------------------
    |
    | Time-to-live for permission caching in seconds.
    |
    */
    'permission_cache_ttl' => env('RBAC_PERMISSION_CACHE_TTL', 3600), // 1 hour

    /*
    |--------------------------------------------------------------------------
    | Default Roles
    |--------------------------------------------------------------------------
    |
    | Default roles that are created for every tenant.
    |
    */
    'default_roles' => [
        'super_admin' => [
            'name' => 'Super Administrator',
            'description' => 'Full access to all system features',
            'is_system' => true,
        ],
        'tenant_admin' => [
            'name' => 'Tenant Administrator',
            'description' => 'Full access to tenant features',
            'is_system' => true,
        ],
        'manager' => [
            'name' => 'Manager',
            'description' => 'Can manage users and content',
            'is_system' => false,
        ],
        'user' => [
            'name' => 'User',
            'description' => 'Regular user with basic permissions',
            'is_system' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | System Permissions
    |--------------------------------------------------------------------------
    |
    | Core system permissions that are available by default.
    |
    */
    'system_permissions' => [
        // Tenant permissions
        'view-tenant',
        'create-tenant',
        'update-tenant',
        'delete-tenant',
        
        // User permissions
        'view-user',
        'create-user',
        'update-user',
        'delete-user',
        
        // Role permissions
        'view-role',
        'create-role',
        'update-role',
        'delete-role',
        'assign-role',
        
        // Permission permissions
        'view-permission',
        'create-permission',
        'update-permission',
        'delete-permission',
        'assign-permission',
        
        // Provider permissions
        'view-provider',
        'create-provider',
        'update-provider',
        'delete-provider',
        
        // Booking permissions
        'view-booking',
        'create-booking',
        'update-booking',
        'delete-booking',
        'cancel-booking',
        
        // Workflow permissions
        'view-workflow',
        'create-workflow',
        'update-workflow',
        'delete-workflow',
        'execute-workflow',
        
        // Notification permissions
        'view-notification',
        'send-notification',
        'delete-notification',
    ],

    /*
    |--------------------------------------------------------------------------
    | Policy Engine Rules
    |--------------------------------------------------------------------------
    |
    | Configuration for the policy engine.
    |
    */
    'policy_engine' => [
        'enabled' => env('RBAC_POLICY_ENGINE_ENABLED', true),
        'default_deny' => env('RBAC_DEFAULT_DENY', true),
        'evaluation_strategy' => env('RBAC_EVALUATION_STRATEGY', 'allow_override'), // allow_override, deny_override, first_match
    ],

    /*
    |--------------------------------------------------------------------------
    | Role Hierarchy
    |--------------------------------------------------------------------------
    |
    | Define role hierarchies where higher roles inherit permissions from lower roles.
    |
    */
    'role_hierarchy' => [
        'super_admin' => ['tenant_admin', 'manager', 'user'],
        'tenant_admin' => ['manager', 'user'],
        'manager' => ['user'],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Types
    |--------------------------------------------------------------------------
    |
    | Types of permissions available in the system.
    |
    */
    'permission_types' => [
        'read',
        'write',
        'delete',
        'execute',
        'manage',
    ],

    /*
    |--------------------------------------------------------------------------
    | Resource Types
    |--------------------------------------------------------------------------
    |
    | Types of resources that can have permissions.
    |
    */
    'resource_types' => [
        'tenant',
        'user',
        'role',
        'permission',
        'provider',
        'booking',
        'workflow',
        'notification',
    ],
];