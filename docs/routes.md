# Routes Documentation

> **Web and API route organization**

## Table of Contents

- [Overview](#overview)
- [Web Routes](#web-routes)
- [API Routes](#api-routes)
- [Route Organization](#route-organization)
- [Middleware Usage](#middleware-usage)
- [Route Model Binding](#route-model-binding)

## Overview

Routes are organized in:
- **`routes/web.php`** - Web UI routes (session auth)
- **`routes/api.php`** - API routes (Sanctum tokens)
- **`routes/console.php`** - Console commands
- **`routes/channels.php`** - Broadcasting channels

## Web Routes

### Public Routes

```php
// Landing/redirect
Route::get('/', function () {
    return Auth::check() ? redirect('/dashboard') : redirect('/login');
});
```

### Authentication Routes

```php
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm']);
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm']);
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail']);
});

Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth');
```

### Protected Dashboard

```php
Route::middleware(['auth', 'dashboard.access', 'session.security', 'auto.logout'])->group(function () {
    Route::get('/dashboard', [UnifiedDashboardController::class, 'index'])->name('dashboard');
    
    Route::get('/sessions', [SessionController::class, 'index']);
    Route::delete('/sessions/{session}', [SessionController::class, 'destroy']);
});
```

### Resource Routes

```php
Route::middleware('auth')->group(function () {
    // Users
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [UserController::class, 'index'])->name('index');
        Route::get('/create', [UserController::class, 'create'])->name('create');
        Route::post('/', [UserController::class, 'store'])->name('store');
    });
    
    // Roles
    Route::resource('roles', RoleController::class);
    
    // Bookings
    Route::resource('bookings', BookingController::class);
});
```

### Impersonation

```php
Route::middleware('auth')->group(function () {
    Route::post('/impersonate/start/{user}', [ImpersonationController::class, 'start']);
    Route::get('/impersonate/stop', [ImpersonationController::class, 'stop']);
});
```

## API Routes

### Authentication

```php
// Public endpoints
Route::post('/login', [Api/AuthController::class, 'login']);

// Protected endpoints
Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', fn() => auth()->user());
    Route::post('/logout', [Api\AuthController::class, 'logout']);
});
```

### API Resources

```php
Route::middleware('auth:sanctum')->prefix('v1')->group(function () {
    // Users
    Route::apiResource('users', Api\V1\UserController::class);
    
    // Bookings
    Route::apiResource('bookings', Api\V1\BookingController::class);
    Route::patch('bookings/{booking}/confirm', [Api\V1\BookingController::class, 'confirm']);
    Route::patch('bookings/{booking}/cancel', [Api\V1\BookingController::class, 'cancel']);
    
    // Providers
    Route::apiResource('providers', Api\V1\ProviderController::class);
    Route::get('providers/{provider}/slots', [Api\V1\ProviderController::class, 'slots']);
});
```

## Route Organization

### Naming Convention

```php
// ✅ Good: Descriptive names
Route::get('/users', ...)->name('users.index');
Route::get('/users/create', ...)->name('users.create');
Route::post('/users', ...)->name('users.store');

// ❌ Bad: Inconsistent or missing names
Route::get('/users', ...)->name('list_users');
Route::get('/users', ...); // No name
```

### Route Grouping

By prefix and middleware:
```php
Route::prefix('admin')
    ->middleware(['auth', 'permission:manage-platform'])
    ->name('admin.')
    ->group(function () {
        Route::resource('tenants', TenantController::class);
    });
```

## Middleware Usage

### Single Middleware

```php
Route::middleware('auth')->get('/dashboard', ...);
```

### Multiple Middleware

```php
Route::middleware(['auth', 'verified', 'permission:view-reports'])
    ->get('/reports', ...);
```

### Middleware with Parameters

```php
Route::middleware('permission:delete-user')
    ->delete('/users/{user}', ...);
```

## Route Model Binding

### Automatic Binding

```php
Route::get('/users/{user}', function (User $user) {
    return $user; // Automatically resolves User::findOrFail($id)
});
```

### Custom Binding Logic

```php
// In RouteServiceProvider
Route::bind('booking', function ($value) {
    return Booking::where('id', $value)
        ->where('tenant_id', app('tenant.id'))
        ->firstOrFail();
});
```

## Cross-Links

- [Middleware](middleware.md) - Middleware details
- [Controllers](controllers.md) - Controller implementations
- [API Reference](api-reference.md) - API endpoints
