<?php

use Illuminate\Support\Facades\Route;
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


/*
|--------------------------------------------------------------------------
| Public Landing Redirect
|--------------------------------------------------------------------------
*/

Route::get('/', function () {

    if (Auth::check()) {
        $user = Auth::user()->load('roles');

        if ($user->hasRole('Super Admin')) {
            return redirect()->route('superadmin.dashboard');
        }

        if ($user->hasRole('Tenant Admin')) {
            return redirect()->route('tenantadmin.dashboard');
        }

        if ($user->hasRole('Provider')) {
            return redirect()->route('provider.dashboard');
        }

        if ($user->hasRole('Ops')) {
            return redirect()->route('ops.dashboard');
        }

        if ($user->hasRole('Premium Customer')) {
            return redirect()->route('customer.dashboard');
        }

        if ($user->hasRole('Customer')) {
            return redirect()->route('customer.dashboard'); // FIXED
        }

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

Route::prefix('sso/{provider}')->group(function () {
    Route::get('/redirect', [SsoController::class, 'redirect'])->name('sso.redirect');
    Route::get('/callback', [SsoController::class, 'callback'])->name('sso.callback');
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

    Route::get('/reports', function () {
        return view('superadmin.reports');
    })->name('reports');

    Route::get('/logs', function () {
        return view('superadmin.logs');
    })->name('logs');
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

    Route::view('/users', 'tenantadmin.users')->name('users');
    Route::view('/providers', 'tenantadmin.providers')->name('providers');
    Route::view('/bookings', 'tenantadmin.bookings')->name('bookings');
    Route::view('/roles', 'tenantadmin.roles')->name('roles');
    Route::view('/settings', 'tenantadmin.settings')->name('settings');
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

    Route::view('/bookings', 'provider.bookings')->name('bookings');
    Route::view('/schedule', 'provider.schedule')->name('schedule');
    Route::view('/services', 'provider.services')->name('services');
    Route::view('/profile', 'provider.profile')->name('profile');
    Route::view('/reviews', 'provider.reviews')->name('reviews');
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

    Route::view('/workflows', 'ops.workflows')->name('workflows');
    Route::view('/approvals', 'ops.approvals')->name('approvals');
    Route::view('/reports', 'ops.reports')->name('reports');
    Route::view('/analytics', 'ops.analytics')->name('analytics');
    Route::view('/logs', 'ops.logs')->name('logs');
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

    Route::view('/bookings', 'customer.bookings')->name('bookings');
    Route::view('/services', 'customer.services')->name('services');
    Route::view('/profile', 'customer.profile')->name('profile');
    Route::view('/payments', 'customer.payments')->name('payments');
    Route::view('/support', 'customer.support')->name('support');
});
