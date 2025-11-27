# Configuration & Environment

> **Environment variables and configuration files reference**

## Table of Contents

- [Environment Variables](#environment-variables)
- [Configuration Files](#configuration-files)
- [Cache Configuration](#cache-configuration)
- [Queue Configuration](#queue-configuration)
- [Session Configuration](#session-configuration)
- [Tenant Configuration](#tenant-configuration)
- [RBAC Configuration](#rbac-configuration)
- [Dashboard Configuration](#dashboard-configuration)
- [Cross-Links](#cross-links)

## Environment Variables

### `.env` File Structure

```env
# Application
APP_NAME="Enterprise SaaS Platform"
APP_ENV=local                    # local, staging, production
APP_KEY=base64:...              # Generate with: php artisan key:generate
APP_DEBUG=true                   # Set to false in production
APP_URL=http://localhost

# Database
DB_CONNECTION=sqlite             # sqlite, mysql, pgsql
DB_DATABASE=/absolute/path/database.sqlite

# Session
SESSION_DRIVER=database         # file, cookie, database, redis
SESSION_LIFETIME=120            # Minutes

# Cache
CACHE_STORE=database           # file, database, redis
CACHE_PREFIX=app_cache_

# Queue
QUEUE_CONNECTION=database       # sync, database, redis

# Tenant
TENANT_IDENTIFICATION_STRATEGY=domain  # domain, subdomain, header
TENANT_STRICT_ISOLATION=true

# Dashboard
DASHBOARD_IDLE_TIMEOUT=30       # Minutes (0 = disabled)
DASHBOARD_SESSION_LIMIT=5       # Max concurrent sessions (0 = unlimited)
DASHBOARD_ROLE_PRIORITY_OVERRIDE=true

# RBAC
RBAC_PERMISSION_CACHE_TTL=3600  # Seconds
RBAC_POLICY_ENGINE_ENABLED=true

# Mail
MAIL_MAILER=log                 # smtp, sendmail, mailgun, ses, log
MAIL_FROM_ADDRESS="noreply@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

## Configuration Files

### app.php

**Purpose**: Application-wide settings

**Location**: [`config/app.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/config/app.php)

**Key Settings:**
```php
return [
    'name' => env('APP_NAME', 'Laravel'),
    'env' => env('APP_ENV', 'production'),
    'debug' => env('APP_DEBUG', false),
    'url' => env('APP_URL', 'http://localhost'),
    'timezone' => 'UTC',
    'locale' => 'en',
    'faker_locale' => 'en_US',
];
```

### database.php

**Purpose**: Database connections

```php
'connections' => [
    'sqlite' => [
        'driver' => 'sqlite',
        'url' => env('DB_URL'),
        'database' => env('DB_DATABASE', database_path('database.sqlite')),
        'foreign_key_constraints' => env('DB_FOREIGN_KEYS', true),
    ],
    
    'mysql' => [
        'driver' => 'mysql',
        'host' => env('DB_HOST', '127.0.0.1'),
        'port' => env('DB_PORT', '3306'),
        'database' => env('DB_DATABASE', 'laravel'),
        'username' => env('DB_USERNAME', 'root'),
        'password' => env('DB_PASSWORD', ''),
        'charset' => 'utf8mb4',
        'collation' => 'utf8mb4_unicode_ci',
    ],
],
```

## Cache Configuration

**Location**: [`config/cache.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/config/cache.php)

```php
return [
    'default' => env('CACHE_STORE', 'database'),
    
    'stores' => [
        'file' => [
            'driver' => 'file',
            'path' => storage_path('framework/cache/data'),
        ],
        
        'database' => [
            'driver' => 'database',
            'table' => 'cache',
            'connection' => null,
        ],
        
        'redis' => [
            'driver' => 'redis',
            'connection' => 'cache',
        ],
    ],
    
    'prefix' => env('CACHE_PREFIX', 'app_cache'),
];
```

**Usage:**
```php
// Cache for 1 hour
Cache::remember('users_count', 3600, function () {
    return User::count();
});

// Cache with tags
Cache::tags(['permissions'])->put('key', 'value', 3600);
Cache::tags(['permissions'])->flush();
```

## Queue Configuration

**Location**: [`config/queue.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/config/queue.php)

```php
return [
    'default' => env('QUEUE_CONNECTION', 'database'),
    
    'connections' => [
        'sync' => [
            'driver' => 'sync',
        ],
        
        'database' => [
            'driver' => 'database',
            'table' => 'jobs',
            'queue' => 'default',
            'retry_after' => 90,
        ],
        
        'redis' => [
            'driver' => 'redis',
            'connection' => 'default',
            'queue' => env('REDIS_QUEUE', 'default'),
            'retry_after' => 90,
            'block_for' => null,
        ],
    ],
];
```

**Run Queue Worker:**
```bash
php artisan queue:work --tries=3 --timeout=90
```

## Session Configuration

**Location**: [`config/session.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/config/session.php)

```php
return [
    'driver' => env('SESSION_DRIVER', 'database'),
    'lifetime' => env('SESSION_LIFETIME', 120),
    'expire_on_close' => false,
    'encrypt' => false,
    'table' => 'sessions',
    'store' => null,
    'lottery' => [2, 100],  // Cleanup old sessions
    'cookie' => env('SESSION_COOKIE', 'laravel_session'),
    'path' => '/',
    'domain' => env('SESSION_DOMAIN'),
    'secure' => env('SESSION_SECURE_COOKIE', true),  // HTTPS only
    'http_only' => true,  // Prevent JavaScript access
    'same_site' => 'lax',  // CSRF protection
];
```

## Tenant Configuration

**Location**: [`config/tenant.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/config/tenant.php)

```php
return [
    'identification_strategy' => env('TENANT_IDENTIFICATION_STRATEGY', 'domain'),
    
    'default_limits' => [
        'users' => env('TENANT_DEFAULT_USER_LIMIT', 100),
        'providers' => env('TENANT_DEFAULT_PROVIDER_LIMIT', 50),
        'bookings' => env('TENANT_DEFAULT_BOOKING_LIMIT', 1000),
        'workflows' => env('TENANT_DEFAULT_WORKFLOW_LIMIT', 20),
    ],
    
    'isolation' => [
        'enforce_strict_isolation' => env('TENANT_STRICT_ISOLATION', true),
        'cross_tenant_queries_allowed' => false,
    ],
    
    'subscription_tiers' => [
        'free' => [
            'name' => 'Free',
            'limits' => ['users' => 5, 'providers' => 3, 'bookings' => 100],
        ],
        'premium' => [
            'name' => 'Premium',
            'limits' => ['users' => 100, 'providers' => 50, 'bookings' => 5000],
        ],
    ],
];
```

## RBAC Configuration

**Location**: [`config/rbac.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/config/rbac.php)

```php
return [
    'permission_cache_ttl' => env('RBAC_PERMISSION_CACHE_TTL', 3600),
    
    'default_roles' => [
        'super_admin' => [
            'name' => 'Super Administrator',
            'is_system' => true,
        ],
        'tenant_admin' => [
            'name' => 'Tenant Administrator',
            'is_system' => true,
        ],
    ],
    
    'system_permissions' => [
        'view-tenant', 'create-tenant', 'update-tenant', 'delete-tenant',
        'view-user', 'create-user', 'update-user', 'delete-user',
        // ... more permissions
    ],
    
    'policy_engine' => [
        'enabled' => env('RBAC_POLICY_ENGINE_ENABLED', true),
        'default_deny' => true,
    ],
];
```

## Dashboard Configuration

**Location**: [`config/dashboard.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/config/dashboard.php)

```php
return [
    'idle_timeout' => env('DASHBOARD_IDLE_TIMEOUT', 30),  // Minutes
    'session_limit' => env('DASHBOARD_SESSION_LIMIT', 5),
    'role_priority_override' => env('DASHBOARD_ROLE_PRIORITY_OVERRIDE', true),
    'widget_cache_ttl' => env('DASHBOARD_WIDGET_CACHE_TTL', 300),  // Seconds
    'inactivity_warning_percent' => env('DASHBOARD_INACTIVITY_WARNING_PERCENT', 80),
];
```

## Cross-Links

- [Deployment](deployment-and-devops.md) - Production configuration
- [Security](security.md) - Security settings
- [Tenancy](tenancy.md) - Tenant configuration
- [RBAC](rbac.md) - Permission configuration
