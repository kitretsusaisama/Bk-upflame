# Dynamic Sidebar & Dashboard

> **Role-aware unified dashboard with permission-controlled navigation**

## Table of Contents

- [Overview](#overview)
- [Unified Dashboard Architecture](#unified-dashboard-architecture)
- [Dynamic Sidebar System](#dynamic-sidebar-system)
- [Dashboard Widgets](#dashboard-widgets)
- [Role-Based Rendering](#role-based-rendering)
- [Caching Strategy](#caching-strategy)
- [Code Examples](#code-examples)
- [Extensibility](#extensibility)
- [Cross-Links](#cross-links)

## Overview

The platform features a **unified dashboard** (`/dashboard`) that dynamically adapts to the user's highest-priority role. Navigation sidebar and widgets are database-driven and permission-controlled.

### Key Features

- **Single Dashboard Route** - All users access `/dashboard`
- **Role-Aware Stats** - Different statistics per role
- **Dynamic Sidebar** - Menu items controlled by permissions
- **Configurable Widgets** - Admin-controlled dashboard widgets
- **Aggressive Caching** - 5-10 minute caching for performance

## Unified Dashboard Architecture

### Before (Legacy)

```
/superadmin/dashboard    → SuperAdminController
/tenantadmin/dashboard   → TenantAdminController
/provider/dashboard      → ProviderDashboardController
/ops/dashboard           → OpsDashboardController
/customer/dashboard      → CustomerDashboardController
```

**Problems:**
- 5 controllers with duplicate logic
- Hard to add new roles
- Inconsistent UI/UX

### After (Unified)

```
/dashboard → UnifiedDashboardController
    ↓
    Detect user's highest-priority role
    ↓
    Render appropriate stats and widgets
```

**Benefits:**
- Single source of truth
- Easy to extend
- Consistent UX

### Controller Implementation

[`app/Http/Controllers/UnifiedDashboardController.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/app/Http/Controllers/UnifiedDashboardController.php):

```php
class UnifiedDashboardController extends Controller
{
    public function index(DashboardService $dashboardService, SidebarService $sidebarService)
    {
        $user = auth()->user();
        
        $data = [
            'dashboardData' => $dashboardService->getDashboardData($user),
            'sidebar' => $sidebarService->buildForUser($user),
        ];
        
        return view('dashboard.unified', $data);
    }
}
```

## Dynamic Sidebar System

### Menu Database Structure

**menus table:**

| Column | Type | Description |
|--------|------|-------------|
| id | ULID | Primary key |
| parent_id | ULID | For nested menus (future) |
| label | string | Display text (e.g., "Users") |
| route | string | Laravel route name (e.g., "users.index") |
| icon | string | Icon class (e.g., "fa-users") |
| group | string | Menu group (e.g.," Management") |
| group_order | int | Group display order |
| sort_order | int | Item order within group |
| scope | enum | 'platform', 'tenant', 'both' |
| required_permissions | json | Array of permissions (OR logic) |
| is_active | boolean | Enable/disable |
| meta | json | Additional metadata |

### Example Menu Seeder

[`database/seeders/MenuSeeder.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/database/seeders/MenuSeeder.php):

```php
Menu::create([
    'label' => 'Users',
    'route' => 'users.index',
    'icon' => 'fa-users',
    'group' => 'Management',
    'group_order' => 1,
    'sort_order' => 1,
    'scope' => 'tenant',
    'required_permissions' => ['view-user'],
    'is_active' => true,
]);

Menu::create([
    'label' => 'Tenants',
    'route' => 'admin.tenants.index',
    'icon' => 'fa-building',
    'group' => 'Platform',
    'group_order' => 0,
    'sort_order' => 1,
    'scope' => 'platform',
    'required_permissions' => ['view-tenant'],
    'is_active' => true,
]);
```

### SidebarService

[`app/Services/SidebarService.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/app/Services/SidebarService.php):

```php
class SidebarService
{
    public function buildForUser(User $user): array
    {
        // Cache for 10 minutes
        $cacheKey = "sidebar_menu_{$user->id}";
        
        return Cache::remember($cacheKey, 600, function () use ($user) {
            return $this->buildMenuStructure($user);
        });
    }
    
    protected function buildMenuStructure(User $user): array
    {
        // 1. Fetch menus scoped by user
        $menus = Menu::active()
            ->forUser($user)  // Scope: platform vs tenant
            ->orderBy('group_order')
            ->orderBy('sort_order')
            ->get();
        
        // 2. Filter by permissions
        $accessibleMenus = $menus->filter(function ($menu) use ($user) {
            return $menu->isAccessibleBy($user);
        });
        
        // 3. Group by menu group
        $grouped = $accessibleMenus->groupBy('group');
        
        // 4. Transform to array
        return $grouped->map(function ($items, $groupName) {
            return [
                'group' => $groupName,
                'order' => $items->first()->group_order,
                'items' => $items->map(fn($menu) => [
                    'label' => $menu->label,
                    'url' => route($menu->route),
                    'icon' => $menu->icon,
                    'active' => request()->routeIs($menu->route),
                ])->toArray()
            ];
        })->values()->toArray();
    }
}
```

### Permission Check Logic

[`app/Domains/Access/Models/Menu.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/app/Domains/Access/Models/Menu.php#L96-L110):

```php
public function isAccessibleBy($user): bool
{
    if (empty($this->required_permissions)) {
        return true; // No permission required
    }
    
    // User needs ANY of the required permissions (OR logic)
    foreach ($this->required_permissions as $permission) {
        if ($user->hasPermission($permission)) {
            return true;
        }
    }
    
    return false;
}

public function scopeForUser($query, $user)
{
    $query->where(function ($q) use ($user) {
        $q->where('scope', 'both');
        
        if ($user->isSuperAdmin()) {
            $q->orWhere('scope', 'platform');
        } else {
            $q->orWhere('scope', 'tenant');
        }
    });
    
    return $query;
}
```

### Blade Rendering

```blade
<!-- resources/views/layouts/sidebar.blade.php -->
<aside class="sidebar">
    @foreach ($sidebar as $group)
        <div class="menu-group">
            <h4 class="group-title">{{ $group['group'] }}</h4>
            
            <ul class="menu-items">
                @foreach ($group['items'] as $item)
                    <li class="{{ $item['active'] ? 'active' : '' }}">
                        <a href="{{ $item['url'] }}">
                            <i class="{{ $item['icon'] }}"></i>
                            <span>{{ $item['label'] }}</span>
                        </a>
                    </li>
                @endforeach
            </ul>
        </div>
    @endforeach
</aside>
```

## Dashboard Widgets

### Widget System

**dashboard_widgets table:**

| Column | Type | Description |
|--------|------|-------------|
| id | ULID | Primary key |
| key | string | Unique identifier (e.g., "total_users") |
| label | string | Display title |
| component | string | Blade component name |
| required_permissions | json | Permissions to view widget |
| sort_order | int | Display order |
| config | json | Widget-specific config |
| is_active | boolean | Enable/disable |

### Example Widgets

```php
DashboardWidget::create([
    'key' => 'total_users',
    'label' => 'Total Users',
    'component' => 'widgets.stat-card',
    'required_permissions' => ['view-user'],
    'sort_order' => 1,
    'config' => ['icon' => 'fa-users', 'color' => 'blue'],
    'is_active' => true,
]);

DashboardWidget::create([
    'key' => 'pending_bookings',
    'label' => 'Pending Bookings',
    'component' => 'widgets.booking-list',
    'required_permissions' => ['view-booking'],
    'sort_order' => 2,
    'is_active' => true,
]);
```

### WidgetResolver Service

[`app/Services/WidgetResolver.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/app/Services/WidgetResolver.php):

```php
class WidgetResolver
{
    public function resolveData(DashboardWidget $widget, User $user)
    {
        return match ($widget->key) {
            'total_users' => User::where('tenant_id', $user->tenant_id)->count(),
            'pending_bookings' => Booking::where('status', 'pending')->count(),
            'revenue_today' => Booking::whereDate('created_at', today())
                ->sum('total_amount'),
            default => null,
        };
    }
}
```

### DashboardService

[`app/Services/DashboardService.php`](file:///C:/Users/Victo/Downloads/backends/Bk-upflame/app/Services/DashboardService.php):

```php
class DashboardService
{
    public function getDashboardData(User $user): array
    {
        $role = $user->getHighestPriorityRole();
        
        return [
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => $role?->name,
                'role_priority' => $role?->priority,
            ],
            'stats' => $this->getStatsForUser($user),
            'widgets' => $this->getWidgetsForUser($user),
        ];
    }
    
    public function getStatsForUser(User $user): array
    {
        $role = $user->getHighestPriorityRole();
        
        if (!$role) {
            return [];
        }
        
        // Cache for 5 minutes
        $cacheKey = "dashboard_stats_{$user->id}_{$role->id}";
        
        return Cache::remember($cacheKey, 300, function () use ($user, $role) {
            return match ($role->name) {
                'Super Admin' => $this->getSuperAdminStats($user),
                'Tenant Admin', 'Admin' => $this->getAdminStats($user),
                'Provider' => $this->getProviderStats($user),
                'Ops' => $this->getOpsStats($user),
                default => $this->getCustomerStats($user),
            };
        });
    }
}
```

## Role-Based Rendering

### Statistics by Role

**Super Admin:**
```php
protected function getSuperAdminStats(User $user): array
{
    return [
        'total_tenants' => Tenant::count(),
        'total_users' => User::count(),
        'active_sessions' => UserSession::where('last_activity', '>', now()->subHour())->count(),
        'total_bookings' => Booking::count(),
    ];
}
```

**Tenant Admin:**
```php
protected function getAdminStats(User $user): array
{
    return [
        'total_users' => User::where('tenant_id', $user->tenant_id)->count(),
        'total_providers' => Provider::where('tenant_id', $user->tenant_id)->count(),
        'total_bookings' => Booking::where('tenant_id', $user->tenant_id)->count(),
        'pending_approvals' => Provider::where('tenant_id', $user->tenant_id)
            ->where('status', 'pending')
            ->count(),
    ];
}
```

**Provider:**
```php
protected function getProviderStats(User $user): array
{
    $provider = $user->provider;
    
    if (!$provider) {
        return [];
    }
    
    return [
        'total_bookings' => $provider->bookings()->count(),
        'pending_bookings' => $provider->bookings()->where('status', 'pending')->count(),
        'completed_bookings' => $provider->bookings()->where('status', 'completed')->count(),
        'revenue_this_month' => $provider->bookings()
            ->where('status', 'completed')
            ->whereMonth('created_at', now()->month)
            ->sum('total_amount'),
    ];
}
```

## Caching Strategy

### Cache Keys

```php
// Sidebar cache (10 minutes)
"sidebar_menu_{$user->id}"

// Dashboard stats (5 minutes)
"dashboard_stats_{$user->id}_{$role->id}"

// Widget data (5 minutes)
"widget_{$widget->key}_{$user->id}"
```

### Cache Invalidation

```php
// When user permissions change
$user->roles()->sync($newRoles);
Cache::forget("sidebar_menu_{$user->id}");
Cache::forget("dashboard_stats_{$user->id}_{$role->id}");

// When menu structure changes
Menu::create([...]);
// Clear all sidebar caches
Cache::tags(['sidebar'])->flush();
```

## Code Examples

### Adding a New Menu Item

```php
Menu::create([
    'label' => 'Reports',
    'route' => 'reports.index',
    'icon' => 'fa-chart-bar',
    'group' => 'Analytics',
    'group_order' => 3,
    'sort_order' => 1,
    'scope' => 'tenant',
    'required_permissions' => ['view-reports'],
    'is_active' => true,
]);
```

### Adding a New Widget

```php
DashboardWidget::create([
    'key' => 'recent_activity',
    'label' => 'Recent Activity',
    'component' => 'widgets.activity-feed',
    'required_permissions' => ['view-audit-logs'],
    'sort_order' => 10,
    'is_active' => true,
]);

// Update WidgetResolver
public function resolveData(DashboardWidget $widget, User $user)
{
    return match ($widget->key) {
        'recent_activity' => AccessLog::latest()->take(10)->get(),
        // ... other widgets
    };
}
```

## Extensibility

### Custom Dashboard for New Role

1. **Create role** in database with priority
2. **Add permissions** for new role
3. **Seed menu items** with appropriate scope
4. **Add stats method** in Dashboardservice:
   ```php
   protected function getVendorStats(User $user): array
   {
       return [
           'total_products' => Product::where('vendor_id', $user->vendor->id)->count(),
           // ...
       ];
   }
   ```
5. **Update match statement**:
   ```php
   return match ($role->name) {
       'Vendor' => $this->getVendorStats($user),
       // ...
   };
   ```

## Cross-Links

- [RBAC](rbac.md) - Permission system driving sidebar
- [Authentication](authentication.md) - Session management
- [Database Schema](database-schema.md) - Menus and widgets tables
- [Caching](config-and-env.md#caching) - Cache configuration
