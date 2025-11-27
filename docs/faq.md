# FAQ - Frequently Asked Questions

> **Common questions and troubleshooting**

## Table of Contents

- [General](#general)
- [Multi-Tenancy](#multi-tenancy)
- [Authentication](#authentication)
- [RBAC & Permissions](#rbac--permissions)
- [Dashboard](#dashboard)
- [Performance](#performance)
- [Deployment](#deployment)
- [Development](#development)

## General

### What is this platform?

A Laravel 12-based multi-tenant SaaS platform for booking/scheduling with advanced RBAC, workflow automation, and comprehensive audit logging.

### What Laravel version does it use?

Laravel 12 with PHP 8.2+

### What databases are supported?

- **Development**: SQLite
- **Production**: MySQL 8.0+, PostgreSQL 13+

### Why ULID instead of auto-increment IDs?

ULIDs provide:
- Global uniqueness across tenants
- Time-ordered (better index performance)
- No collision risk when merging data
- URL-safe representation

## Multi-Tenancy

### How are tenants identified?

Four strategies (configured in `config/tenant.php`):
1. **Domain**: `tenant-a.app.com`
2. **Subdomain**: `tenant-a.platform.com`
3. **Header**: `X-Tenant-ID` header
4. **Parameter**: `?tenant_id=...` (not recommended)

### Can users belong to multiple tenants?

Yes! Users can have different roles in different tenants via the `user_roles` pivot table with `tenant_id`.

### How is data isolation enforced?

- Global scopes on all tenant-scoped models
- Middleware sets `app('tenant.id')` 
- Database indexes on `tenant_id` columns
- Foreign key constraints

### Can I migrate tenants between databases?

Not currently implemented. All tenants share the same database with row-level isolation.

## Authentication

### Which authentication methods are supported?

1. **Web**: Session-based (cookies)
2. **API**: Laravel  Sanctum tokens
3. **SSO**: OAuth 2.0 / SAML 2.0 (via `tenant_identity_providers`)

### How do I get an API token?

```http
POST /api/v1/login
{
    "email": "user@example.com",
    "password": "password",
    "device_name": "My Phone"
}
```

Response includes `token` field.

### How long do sessions last?

Configured in `config/session.php`:
- Default: 120 minutes
- Idle timeout: 30 minutes (configurable in `config/dashboard.php`)

### Can users be logged out from all devices?

Yes:
```php
$user->terminateAllSessions();
```

Or via UI: Dashboard → Sessions → "Logout All Devices"

## RBAC & Permissions

### What's the difference between roles and permissions?

- **Role**: Named collection of permissions (e.g., "Tenant Admin")
- **Permission**: Specific capability (e.g., "view-user", "create-booking")

### How does role priority work?

Lower number = higher priority:
- Super Admin: priority 1
- Tenant Admin: priority 10
- Provider: priority 50
- Customer: priority 100

Dashboard renders based on highest-priority (lowest number) role.

### Can I create custom permissions?

Yes:
```php
Permission::create([
    'tenant_id' => $tenantId,
    'name' => 'export-reports',
    'resource' => 'reports',
    'action' => 'export',
]);
```

### Why isn't my sidebar menu showing?

Check:
1. User has required permission(s)
2. Menu is active (`is_active = true`)
3. Menu scope matches user type (platform/tenant/both)
4. Clear cache: `Cache::forget("sidebar_menu_{$user->id}")`

### How do I add a new menu item?

```php
Menu::create([
    'label' => 'Reports',
    'route' => 'reports.index',
    'icon' => 'fa-chart-bar',
    'group' => 'Analytics',
    'required_permissions' => ['view-reports'],
]);
```

## Dashboard

### Why do different users see different dashboards?

The unified dashboard detects the user's **highest-priority role** and renders role-specific stats/widgets.

### How do I add a new widget?

1. Create widget record:
```php
DashboardWidget::create([
    'key' => 'my_widget',
    'label' => 'My Widget',
    'component' => 'widgets.my-widget',
    'required_permissions' => ['view-my-data'],
]);
```

2. Update `DashboardService::getWidgetsForUser()` to return data for your widget.

### Can users customize their dashboard?

Not currently. All widgets are admin-controlled via database.

## Performance

### Are permissions cached?

Yes, with 1-hour TTL (configurable in `config/rbac.php`):
```php
'permission_cache_ttl' => 3600,
```

### Are sidebar menus cached?

Yes, 10 minutes per user:
```php
Cache::remember("sidebar_menu_{$user->id}", 600, ...);
```

### How do I clear all caches?

```bash
php artisan cache:clear      # Application cache
php artisan config:clear     # Config cache
php artisan route:clear      # Route cache
php artisan view:clear       # View cache
```

### What's the recommended production cache driver?

Redis for best performance:
```env
CACHE_STORE=redis
QUEUE_CONNECTION=redis
SESSION_DRIVER=redis
```

## Deployment

### What are the production requirements?

- PHP 8.2+
- MySQL 8.0+ or PostgreSQL 13+
- Redis (recommended for cache/queue/sessions)
- Composer
- Node.js & NPM (for asset building)

### How do I deploy?

See [Deployment Guide](deployment-and-devops.md)

Basic steps:
```bash
composer install --no-dev
npm install && npm run build
php artisan migrate --force
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

### Do I need to run queue workers?

Yes, for background jobs:
```bash
php artisan queue:work --tries=3
```

Or use Supervisor to keep it running.

## Development

### How do I set up locally?

```bash
composer install
npm install
cp .env.example .env
php artisan key:generate
php artisan migrate --seed
npm run dev
php artisan serve
```

### How do I run tests?

```bash
php artisan test
```

### How do I seed test data?

```bash
php artisan db:seed --class=TestDataSeeder
```

### How do I create a new domain?

1. Create directory: `app/Domains/MyDomain/`
2. Add Models, Services, Repositories
3. Register service provider if needed
4. Create migrations

### What's the coding style?

Follow PSR-12:
```bash
./vendor/bin/pint  # Auto-format
```

## Cross-Links

- [Troubleshooting](security.md#troubleshooting) - Debugging guides
- [API Reference](api-reference.md) - API documentation
- [Deployment](deployment-and-devops.md) - Production setup
