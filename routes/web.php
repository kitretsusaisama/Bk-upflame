<?php

use Illuminate\Support\Facades\Route;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;
use App\Domains\Identity\Http\Controllers\SsoController;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TenantAdminController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\OpsDashboardController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;
use App\Http\Controllers\TestController;

// Test routes for menu debugging
Route::get('/test-menu', [TestController::class, 'testMenu'])->middleware('auth');
Route::get('/test-menu-page', [TestController::class, 'testMenuPage'])->middleware('auth');
Route::get('/test-logout', [TestController::class, 'testLogout'])->middleware('auth');

/*
|--------------------------------------------------------------------------
| Test Route
|--------------------------------------------------------------------------
*/

Route::get('/test-roles', function () {
    if (Auth::check()) {
        $user = Auth::user()->load('roles');
        
        echo "User: " . $user->email . "<br>";
        echo "User ID: " . $user->id . "<br>";
        echo "Roles: " . json_encode($user->roles->pluck('name')->toArray()) . "<br><br>";
        
        // Test each role check
        echo "Has Super Admin: " . ($user->hasRole('Super Admin') ? 'Yes' : 'No') . "<br>";
        echo "Has Tenant Admin: " . ($user->hasRole('Tenant Admin') ? 'Yes' : 'No') . "<br>";
        echo "Has Provider: " . ($user->hasRole('Provider') ? 'Yes' : 'No') . "<br>";
        echo "Has Operations: " . ($user->hasRole('Operations') ? 'Yes' : 'No') . "<br>";
        echo "Has Customer: " . ($user->hasRole('Customer') ? 'Yes' : 'No') . "<br>";
        echo "Has Premium Customer: " . ($user->hasRole('Premium Customer') ? 'Yes' : 'No') . "<br>";
        
        return;
    }
    
    return "Not logged in";
});


/*
|--------------------------------------------------------------------------
| Public Landing Redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (Auth::check()) {
        $user = Auth::user()->load('roles');
        
        // Debug: Log user roles
        \Log::info('User roles for redirect:', [
            'user_id' => $user->id,
            'email' => $user->email,
            'roles' => $user->roles->pluck('name')->toArray()
        ]);

        if ($user->hasRole('Super Admin')) {
            \Log::info('Redirecting Super Admin to superadmin.dashboard');
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->hasRole('Tenant Admin')) {
            \Log::info('Redirecting Tenant Admin to tenantadmin.dashboard');
            return redirect()->route('tenantadmin.dashboard');
        }

        if ($user->hasRole('Provider')) {
            \Log::info('Redirecting Provider to provider.dashboard');
            return redirect()->route('provider.dashboard');
        }

        if ($user->hasRole('Operations')) {
            \Log::info('Redirecting Operations to ops.dashboard');
            return redirect()->route('ops.dashboard');
        }

        if ($user->hasRole('Premium Customer')) {
            \Log::info('Redirecting Premium Customer to customer.dashboard');
            return redirect()->route('customer.dashboard');
        }

        if ($user->hasRole('Customer')) {
            \Log::info('Redirecting Customer to customer.dashboard');
            return redirect()->route('customer.dashboard'); // FIXED
        }

        \Log::info('Redirecting default to customer.dashboard');
        return redirect()->route('customer.dashboard');
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
Route::get('/logout', [LoginController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->prefix('sso')->name('sso.')->group(function () {
    Route::get('/token', [SsoController::class, 'token'])->name('token');
    Route::post('/revoke', [SsoController::class, 'revoke'])->name('revoke');
});


/*
|--------------------------------------------------------------------------
| SUPER ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::prefix('superadmin')
    ->name('superadmin.')
    ->middleware(['auth'])
    ->group(function () {

    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants');

    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');

    Route::get('/system', [SuperAdminController::class, 'system'])->name('system');

    Route::get('/reports', [SuperAdminController::class, 'reports'])->name('reports');

    Route::get('/logs', [SuperAdminController::class, 'logs'])->name('logs');
    
    Route::match(['get', 'post'], '/profile', [SuperAdminController::class, 'profile'])->name('profile');
});


/*
|--------------------------------------------------------------------------
| TENANT ADMIN DASHBOARD
|--------------------------------------------------------------------------
*/

Route::prefix('tenantadmin')
    ->name('tenantadmin.')
    ->middleware(['auth', 'tenant.resolution'])
    ->group(function () {

    Route::get('/dashboard', [TenantAdminController::class, 'dashboard'])->name('dashboard');

    Route::get('/users', [TenantAdminController::class, 'users'])->name('users');
    Route::get('/providers', [TenantAdminController::class, 'providers'])->name('providers');
    Route::get('/bookings', [TenantAdminController::class, 'bookings'])->name('bookings');
    Route::get('/roles', [TenantAdminController::class, 'roles'])->name('roles');
    Route::get('/settings', [TenantAdminController::class, 'settings'])->name('settings');
    Route::get('/menu', [TenantAdminController::class, 'menu'])->name('menu');
});


/*
|--------------------------------------------------------------------------
| PROVIDER DASHBOARD
|--------------------------------------------------------------------------
*/

Route::prefix('provider')
    ->name('provider.')
    ->middleware(['auth', 'tenant.resolution'])
    ->group(function () {

    Route::get('/dashboard', [ProviderDashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/bookings', [ProviderDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/schedule', [ProviderDashboardController::class, 'schedule'])->name('schedule');
    Route::get('/services', [ProviderDashboardController::class, 'services'])->name('services');
    Route::get('/profile', [ProviderDashboardController::class, 'profile'])->name('profile');
    Route::get('/reviews', [ProviderDashboardController::class, 'reviews'])->name('reviews');
});


/*
|--------------------------------------------------------------------------
| OPS DASHBOARD
|--------------------------------------------------------------------------
*/

Route::prefix('ops')
    ->name('ops.')
    ->middleware(['auth', 'tenant.resolution'])
    ->group(function () {

    Route::get('/dashboard', [OpsDashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/workflows', [OpsDashboardController::class, 'workflows'])->name('workflows');
    Route::get('/approvals', [OpsDashboardController::class, 'approvals'])->name('approvals');
    Route::get('/reports', [OpsDashboardController::class, 'reports'])->name('reports');
    Route::get('/analytics', [OpsDashboardController::class, 'analytics'])->name('analytics');
    Route::get('/logs', [OpsDashboardController::class, 'logs'])->name('logs');
});


/*
|--------------------------------------------------------------------------
| CUSTOMER DASHBOARD
|--------------------------------------------------------------------------
*/

Route::prefix('customer')
    ->name('customer.')
    ->middleware(['auth', 'tenant.resolution'])
    ->group(function () {

    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');

    Route::get('/bookings', [CustomerDashboardController::class, 'bookings'])->name('bookings');
    Route::get('/services', [CustomerDashboardController::class, 'services'])->name('services');
    Route::get('/profile', [CustomerDashboardController::class, 'profile'])->name('profile');
    Route::get('/payments', [CustomerDashboardController::class, 'payments'])->name('payments');
    Route::get('/support', [CustomerDashboardController::class, 'support'])->name('support');
});

/*
|--------------------------------------------------------------------------
| Inertia Test Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'tenant.resolution'])->group(function () {
    Route::get('/users', [App\Http\Controllers\UserController::class, 'index'])->name('users.index');
});

// Test route for Inertia
Route::get('/test-inertia', function () {
    return Inertia\Inertia::render('Users/Index', [
        'users' => [
            'data' => [
                ['id' => 1, 'name' => 'John Doe', 'email' => 'john@example.com'],
                ['id' => 2, 'name' => 'Jane Smith', 'email' => 'jane@example.com'],
                ['id' => 3, 'name' => 'Bob Johnson', 'email' => 'bob@example.com'],
            ]
        ]
    ]);
})->name('test.inertia');