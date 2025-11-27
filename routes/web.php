<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use App\Domains\Identity\Http\Controllers\SsoController;
use App\Http\Controllers\UnifiedDashboardController;
use App\Http\Controllers\SessionController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;


/*
|--------------------------------------------------------------------------
| Public Landing Redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('dashboard');
    }
    
    return redirect()->route('login');
});


/*
|--------------------------------------------------------------------------
| Authentication (Guest Only)
|--------------------------------------------------------------------------
*/

Route::middleware('guest')->group(function () {

    Route::get('/login',  [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);

    Route::get('/register',  [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);

    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])
        ->name('password.request');

    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])
        ->name('password.email');

    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])
        ->name('password.reset');

    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])
        ->name('password.update');
});


/*
|--------------------------------------------------------------------------
| Logout
|--------------------------------------------------------------------------
*/
Route::post('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');
Route::post('/logout-all', [SessionController::class, 'destroyAll'])->middleware('auth')->name('logout.all');

Route::middleware('auth')->prefix('sso')->name('sso.')->group(function () {
    Route::get('/token', [SsoController::class, 'token'])->name('token');
    Route::post('/revoke', [SsoController::class, 'revoke'])->name('revoke');
});

/*
|--------------------------------------------------------------------------
| Impersonation
|--------------------------------------------------------------------------
*/
Route::middleware(['auth'])->group(function () {
    Route::post('/impersonate/start/{user}', [\App\Http\Controllers\ImpersonationController::class, 'start'])->name('impersonate.start');
    Route::get('/impersonate/stop', [\App\Http\Controllers\ImpersonationController::class, 'stop'])->name('impersonate.stop');
});


/*
|--------------------------------------------------------------------------
| UNIFIED DASHBOARD
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'dashboard.access', 'session.security', 'auto.logout'])->group(function () {
    
    // Main unified dashboard route
    Route::get('/dashboard', [UnifiedDashboardController::class, 'index'])->name('dashboard');
    
    // Session management
    Route::get('/sessions', [SessionController::class, 'index'])->name('sessions.index');
    Route::delete('/sessions/{session}', [SessionController::class, 'destroy'])->name('sessions.destroy');

    // User Management
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', function () { return "Users Index Placeholder"; })->name('index');
    });
    Route::prefix('roles')->name('roles.')->group(function () {
        Route::get('/', function () { return "Roles Index Placeholder"; })->name('index');
    });
    Route::prefix('permissions')->name('permissions.')->group(function () {
        Route::get('/', function () { return "Permissions Index Placeholder"; })->name('index');
    });

    // Operations
    Route::prefix('appointments')->name('appointments.')->group(function () {
        Route::get('/', function () { return "Appointments Index Placeholder"; })->name('index');
    });
    Route::prefix('inventory')->name('inventory.')->group(function () {
        Route::get('/', function () { return "Inventory Index Placeholder"; })->name('index');
    });

    // Finance
    Route::prefix('finance')->name('finance.')->group(function () {
        Route::get('/invoices', function () { return "Invoices Placeholder"; })->name('invoices');
        Route::get('/payments', function () { return "Payments Placeholder"; })->name('payments');
    });

    // System
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', function () { return "Settings Index Placeholder"; })->name('index');
    });
    Route::prefix('audit-logs')->name('audit-logs.')->group(function () {
        Route::get('/', function () { return "Audit Logs Index Placeholder"; })->name('index');
    });
});


/*
|--------------------------------------------------------------------------
| LEGACY ROUTE REDIRECTS
| These exist for backward compatibility and redirect to unified dashboard
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {
    // Super Admin legacy routes
    Route::prefix('admin')->name('admin.')->group(function () {
        Route::resource('tenants', \App\Http\Controllers\Admin\TenantController::class);
        Route::put('tenants/{tenant}/suspend', [\App\Http\Controllers\Admin\TenantController::class, 'suspend'])->name('tenants.suspend');
        Route::put('tenants/{tenant}/activate', [\App\Http\Controllers\Admin\TenantController::class, 'activate'])->name('tenants.activate');
    });

    Route::redirect('/superadmin/dashboard', '/dashboard')->name('superadmin.dashboard');
    Route::redirect('/superadmin/tenants', '/admin/tenants');
    Route::redirect('/superadmin/users', '/dashboard');
    Route::redirect('/superadmin/system', '/dashboard');
    Route::redirect('/superadmin/reports', '/dashboard');
    Route::redirect('/superadmin/logs', '/dashboard');
    
    // Tenant Admin legacy routes
    Route::prefix('tenant')->name('tenant.')->group(function () {
        Route::get('organization', [\App\Http\Controllers\Tenant\OrganizationController::class, 'show'])->name('organization.show');
        Route::put('organization', [\App\Http\Controllers\Tenant\OrganizationController::class, 'update'])->name('organization.update');
        
        Route::resource('users', \App\Http\Controllers\Tenant\UserController::class);
        Route::resource('roles', \App\Http\Controllers\Tenant\RoleController::class);
    });

    Route::redirect('/tenantadmin/dashboard', '/dashboard')->name('tenantadmin.dashboard');
    Route::redirect('/tenantadmin/users', '/tenant/users');
    Route::redirect('/tenantadmin/providers', '/dashboard');
    Route::redirect('/tenantadmin/bookings', '/dashboard');
    Route::redirect('/tenantadmin/roles', '/tenant/roles');
    Route::redirect('/tenantadmin/settings', '/tenant/organization');
    
    // Provider legacy routes
    Route::redirect('/provider/dashboard', '/dashboard')->name('provider.dashboard');
    Route::redirect('/provider/bookings', '/dashboard');
    Route::redirect('/provider/schedule', '/dashboard');
    Route::redirect('/provider/services', '/dashboard');
    Route::redirect('/provider/profile', '/dashboard');
    Route::redirect('/provider/reviews', '/dashboard');
    
    // Ops legacy routes
    Route::redirect('/ops/dashboard', '/dashboard')->name('ops.dashboard');
    Route::redirect('/ops/workflows', '/dashboard');
    Route::redirect('/ops/approvals', '/dashboard');
    Route::redirect('/ops/reports', '/dashboard');
    Route::redirect('/ops/analytics', '/dashboard');
    Route::redirect('/ops/logs', '/dashboard');
    
    // Customer legacy routes
    Route::redirect('/customer/dashboard', '/dashboard')->name('customer.dashboard');
    Route::redirect('/customer/bookings', '/dashboard');
    Route::redirect('/customer/services', '/dashboard');
    Route::redirect('/customer/profile', '/dashboard');
    Route::redirect('/customer/payments', '/dashboard');
    Route::redirect('/customer/support', '/dashboard');
});
