# System Design Philosophy

> **Architectural decisions and the reasoning behind them**

## 🔗 Table of Contents

- [Core Principles](#core-principles)
- [Why Domain-Driven Design?](#why-domain-driven-design)
- [Why UUID/ULID Primary Keys?](#why-uuidulid-primary-keys)
- [Why Role Priority System?](#why-role-priority-system)
- [Why Unified Dashboard?](#why-unified-dashboard)
- [Why Database-Driven Permissions?](#why-database-driven-permissions)
- [Why Tenant Isolation via Middleware?](#why-tenant-isolation-via-middleware)
- [Why Session-Based + API Token Hybrid?](#why-session-based--api-token-hybrid)
- [Technology Choices](#technology-choices)
- [Trade-offs Made](#trade-offs-made)
- [Cross-Links](#cross-links)

## Core Principles

### 1. Security First
Every architectural decision considers security implications. Multi-tenancy demands absolute data isolation.

### 2. Developer Experience
Code should be easy to understand, modify, and test. Explicit is better than implicit.

### 3. Performance at Scale
Optimize for thousands of tenants, not just dozens. Caching, indexing, and query optimization are first-class concerns.

### 4. Maintainability
Systems change. Architecture should accommodate new features without major refactoring.

### 5. Observability
What you can't measure, you can't improve. Audit logs, metrics, and error tracking built-in.

## Why Domain-Driven Design?

### Decision
Organize code by business domain (`Access`, `Booking`, `Identity`, etc.) instead of by technical layer (`Models`, `Controllers`, etc.)

### Reasoning

**Problem with layer-first approach:**
```
app/
├── Models/
│   ├── User.php
│   ├── Booking.php
│   ├── Role.php          # All models mixed together
│   └── Permission.php
├── Controllers/
│   ├── UserController.php
│   └── BookingController.php
```

As the application grows, you have hundreds of files in each folder with no clear ownership.

**Solution with DDD:**
```
app/Domains/
├── Identity/
│   ├── Models/User.php
│   ├── Services/UserService.php
│   └── Repositories/UserRepository.php
├── Booking/
│   ├── Models/Booking.php
│   └── Services/BookingService.php
```

**Benefits Realized:**
- ✅ Clear ownership: "Access domain team owns RBAC"
- ✅ Bounded contexts: Changes to Booking don't affect Identity
- ✅ Easier testing: Test entire domains in isolation
- ✅ Onboarding: New developers understand one domain at a time

**Trade-off**: Longer file paths, more navigation

## Why UUID/ULID Primary Keys?

### Decision
Use ULID (Universally Unique Lexicographically Sortable Identifiers) instead of auto-increment integers

### Reasoning

**Problems with auto-increment IDs:**
1. **Security**: Enumerable (`/users/1`, `/users/2` reveals total count)
2. **Multi-tenant**: ID collision risk when merging tenants
3. **Distributed systems**: Can't generate IDs offline
4. **Sharding**: Difficult to split databases

**Why ULID over UUID v4?**
- ✅ **Time-sorted**: Better index performance
- ✅ **Shorter string representation**: 26 characters vs 36
- ✅ **Monotonically increasing**: B-tree index friendly
- ✅ **Collision-resistant**: Same as UUID

**Example ULID**: `01HXYZ1234ABCDEFGHJ56789`

**Performance Impact**: Negligible with proper indexing. ULIDs have excellent index locality.

## Why Role Priority System?

### Decision
Assign numeric priority to roles (1 = highest, 100 = lowest) and always use the highest-priority role for UI rendering

### Reasoning

**Problem**: Users often have multiple roles

```
User: John Doe
Roles: 
- Tenant Admin (tenant: Hospital A)
- Provider (tenant: Hospital A)
- Customer (tenant: Hospital B)
```

**Traditional approach**: Show union of all permissions.  
**Problem**: Cluttered UI with conflicting navigation (admin panel + provider dashboard)

**Priority-based approach**: Render based on highest-priority role.

```php
$role = $user->getHighestPriorityRole();

return match ($role->name) {
    'Tenant Admin' => $this->getAdminDashboard($user),
    'Provider' => $this->getProviderDashboard($user),
    ...
};
```

**Benefits:**
- ✅ Clean, focused UI
- ✅ Clear mental model for users
- ✅ Easy to implement role switching later
- ✅ No permission conflicts

**Trade-off**: Manual role switching needed if user wants provider view

## Why Unified Dashboard?

### Decision
Single `/dashboard` route for all users instead of role-specific routes (`/admin`, `/provider`, `/customer`)

### Previous Architecture (Legacy)
```
/superadmin/dashboard → SuperAdminController
/tenantadmin/dashboard → TenantAdminController
/provider/dashboard → ProviderDashboardController
/ops/dashboard → OpsDashboardController
/customer/dashboard → CustomerDashboardController
```

**Problems:**
- 5 controllers with duplicate logic
- Multiple Blade views with similar structure
- Redirects needed when roles change
- Hard to add new roles

### New Architecture
```
/dashboard → UnifiedDashboardController
  ↓
  Detects user's highest-priority role
  ↓
  Renders appropriate widgets/stats
```

**Benefits:**
- ✅ **Maintainability**: Single controller to maintain
- ✅ **Consistency**: All users have same navigation structure
- ✅ **Flexibility**: Adding roles = adding match case, not new routes
- ✅ **SEO**: Canonical URL for dashboards

**Implementation:**
```php
public function index(DashboardService $dashboardService)
{
    $user = auth()->user();
    $data = $dashboardService->getDashboardData($user);
    
    return view('dashboard.unified', $data); // Single view
}
```

## Why Database-Driven Permissions?

### Decision
Store menus, widgets, and permissions in database instead of hardcoding in controllers/views

### Reasoning

**Traditional approach:**
```php
// In Controller
if (!$user->can('view-users')) {
    abort(403);
}

// In Blade
@can('view-users')
    <a href="/users">Users</a>
@endcan
```

**Problem**: Permissions hardcoded in 100+ places. Changing permission names requires code deployment.

**Database-driven approach:**
```php
// Menus table
id | label | route | required_permissions
-- | ----- | ----- | --------------------
1  | Users | users.index | ["view-user"]
2  | Roles | roles.index | ["view-role"]
```

**Benefits:**
- ✅ **No-code permission changes**: Update database, not code
- ✅ **Tenant-specific customization**: Disable features per tenant
- ✅ **Audit trail**: Permission changes logged
- ✅ **Dynamic sidebar**: Menu builds automatically

**Service layer:**
```php
class SidebarService
{
    public function buildForUser(User $user): array
    {
        return Menu::active()
            ->forUser($user)
            ->get()
            ->filter(fn($menu) => $menu->isAccessibleBy($user))
            ->toArray();
    }
}
```

**Trade-off**: Additional database queries (mitigated by 10-minute cache)

## Why Tenant Isolation via Middleware?

### Decision
Identify and scope tenant context in middleware, not in controllers

### Reasoning

**Problem**: Trusting developers to remember `->where('tenant_id', $tenantId)` in every query is error-prone.

**Bad (manually scoped):**
```php
public function index()
{
    $tenantId = auth()->user()->tenant_id;
    return User::where('tenant_id', $tenantId)->get(); // Easy to forget!
}
```

**Good (middleware + global scope):**
```php
// TenantIdentification middleware
app()->singleton('tenant.id', fn() => $this->identifyTenant($request));

// TenantScoped trait
protected static function booted()
{
    static::addGlobalScope('tenant', function ($query) {
        $query->where('tenant_id', app('tenant.id'));
    });
}

// Controller (tenant scoping automatic)
public function index()
{
    return User::all(); // Automatically scoped!
}
```

**Benefits:**
- ✅ **Secure by default**: Can't forget to scope
- ✅ **DRY**: No repetition
- ✅ **Testable**: Mock `app('tenant.id')`

**Trade-off**: Magic behavior (less explicit)

## Why Session-Based + API Token Hybrid?

### Decision
Use Laravel Session auth for web, Sanctum tokens for API/mobile

### Reasoning

**Web (Session-based):**
- ✅ CSRF protection built-in
- ✅ No token management complexity
- ✅ Server-side session control (can invalidate)
- ✅ Redis-backed for scalability

**API (Sanctum tokens):**
- ✅ Stateless (no session storage needed)
- ✅ Mobile-friendly (store token locally)
- ✅ Granular revocation (per-device tokens)
- ✅ SPA-friendly (same-domain)

**Unified authentication:**
```php
// Both share same guards
Route::middleware(['auth:sanctum'])->group(function () {
    // Works for web sessions AND API tokens
});
```

**Benefits:**
- ✅ Best of both worlds
- ✅ Single user table
- ✅ Shared permission system

## Technology Choices

### Laravel 12
**Why?** Latest LTS, HTTP/3 support, improved performance, modern PHP 8.2 features

**Alternatives considered:**
- Symfony: Too low-level for rapid development
- Lumen: Deprecated in favor of Laravel
- Node.js: Team expertise in PHP

### Blade + Alpine.js
**Why?** Server-rendered HTML with progressive JavaScript enhancement

**Alternatives considered:**
- Vue.js/React SPA: Overkill for mostly CRUD operations
- Livewire: Great, but adds framework lock-in
- Inertia.js: Considered for future migration

### SQLite (dev) / MySQL or PostgreSQL (prod)
**Why?** Zero-config development, mature production support

**Alternatives considered:**
- MongoDB: Poor support for transactions and relations
- PostgreSQL: Preferred for JSON column features

### Redis
**Why?** Fast cache and queue backend

**Alternatives considered:**
- Memcached: Less feature-rich
- Database: Too slow for high-traffic scenarios

## Trade-offs Made

### 1. Caching Complexity vs Performance
**Choice**: Cache permissions, sidebar, widgets  
**Trade-off**: Cache invalidation complexity  
**Mitigation**: Short TTLs (1-10 minutes), explicit cache clearing on updates

### 2. Flexibility vs Simplicity
**Choice**: Database-driven menus/permissions  
**Trade-off**: More database queries  
**Mitigation**: Aggressive caching, eager loading

### 3. Multi-Tenancy Isolation vs Query Performance
**Choice**: Global scopes on all tenant-scoped models  
**Trade-off**: Slight query overhead  
**Mitigation**: Indexed tenant_id columns

### 4. Role Priority vs Flexibility
**Choice**: Single "active role" based on priority  
**Trade-off**: Can't view merged permissions  
**Mitigation**: Role-switching mechanism (planned)

## Cross-Links

- [Architecture](architecture.md) - How the system is structured
- [Tenancy](tenancy.md) - Multi-tenant implementation
- [RBAC](rbac.md) - Role and permission system
- [Dashboard](sidebar-and-dashboard.md) - Dynamic UI system
