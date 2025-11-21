<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\SuperAdminController;
use App\Http\Controllers\TenantAdminController;
use App\Http\Controllers\ProviderDashboardController;
use App\Http\Controllers\OpsDashboardController;
use App\Http\Controllers\CustomerDashboardController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\Auth\ResetPasswordController;

// Public routes
Route::get('/', function () {
    return redirect()->route('customer.dashboard');
});

// Authentication routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/login', [LoginController::class, 'login']);
    
    Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/register', [RegisterController::class, 'register']);
    
    Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
    Route::post('/forgot-password', [ForgotPasswordController::class, 'sendResetLinkEmail'])->name('password.email');
    
    Route::get('/reset-password/{token}', [ResetPasswordController::class, 'showResetForm'])->name('password.reset');
    Route::post('/reset-password', [ResetPasswordController::class, 'reset'])->name('password.update');
});

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Super Admin Dashboard Routes
Route::prefix('superadmin')->name('superadmin.')->middleware(['auth'])->group(function () {
    Route::get('/dashboard', [SuperAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/tenants', [SuperAdminController::class, 'tenants'])->name('tenants');
    Route::get('/users', [SuperAdminController::class, 'users'])->name('users');
    Route::get('/system', [SuperAdminController::class, 'system'])->name('system');
    Route::get('/reports', function() { return view('superadmin.dashboard'); })->name('reports');
    Route::get('/logs', function() { return view('superadmin.dashboard'); })->name('logs');
});

// Tenant Admin Dashboard Routes
Route::prefix('tenantadmin')->name('tenantadmin.')->middleware(['auth', 'tenant.resolution', 'tenant.scope'])->group(function () {
    Route::get('/dashboard', [TenantAdminController::class, 'dashboard'])->name('dashboard');
    Route::get('/users', function() { return view('tenantadmin.dashboard'); })->name('users');
    Route::get('/providers', function() { return view('tenantadmin.dashboard'); })->name('providers');
    Route::get('/bookings', function() { return view('tenantadmin.dashboard'); })->name('bookings');
    Route::get('/roles', function() { return view('tenantadmin.dashboard'); })->name('roles');
    Route::get('/settings', function() { return view('tenantadmin.dashboard'); })->name('settings');
});

// Provider Dashboard Routes
Route::prefix('provider')->name('provider.')->middleware(['auth', 'tenant.resolution', 'tenant.scope'])->group(function () {
    Route::get('/dashboard', [ProviderDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', function() { return view('provider.dashboard'); })->name('bookings');
    Route::get('/schedule', function() { return view('provider.dashboard'); })->name('schedule');
    Route::get('/services', function() { return view('provider.dashboard'); })->name('services');
    Route::get('/profile', function() { return view('provider.dashboard'); })->name('profile');
    Route::get('/reviews', function() { return view('provider.dashboard'); })->name('reviews');
});

// Operations Dashboard Routes
Route::prefix('ops')->name('ops.')->middleware(['auth', 'tenant.resolution', 'tenant.scope'])->group(function () {
    Route::get('/dashboard', [OpsDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/workflows', function() { return view('ops.dashboard'); })->name('workflows');
    Route::get('/approvals', function() { return view('ops.dashboard'); })->name('approvals');
    Route::get('/reports', function() { return view('ops.dashboard'); })->name('reports');
    Route::get('/analytics', function() { return view('ops.dashboard'); })->name('analytics');
    Route::get('/logs', function() { return view('ops.dashboard'); })->name('logs');
});

// Customer Dashboard Routes
Route::prefix('customer')->name('customer.')->middleware(['auth', 'tenant.resolution', 'tenant.scope'])->group(function () {
    Route::get('/dashboard', [CustomerDashboardController::class, 'dashboard'])->name('dashboard');
    Route::get('/bookings', function() { return view('customer.dashboard'); })->name('bookings');
    Route::get('/services', function() { return view('customer.dashboard'); })->name('services');
    Route::get('/profile', function() { return view('customer.dashboard'); })->name('profile');
    Route::get('/payments', function() { return view('customer.dashboard'); })->name('payments');
    Route::get('/support', function() { return view('customer.dashboard'); })->name('support');
});