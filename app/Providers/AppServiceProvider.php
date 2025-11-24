<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use App\Services\UserManagementService;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        $this->app->singleton(UserManagementService::class, function ($app) {
            return new UserManagementService(
                $app->make(\App\Domains\Identity\Repositories\UserRepository::class),
                $app->make(\App\Domains\Identity\Repositories\TenantRepository::class),
                $app->make(\App\Domains\Access\Repositories\RoleRepository::class)
            );
        });
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        \Illuminate\Support\Facades\Route::aliasMiddleware('tenant.resolution', \App\Http\Middleware\TenantResolution::class);
        \Illuminate\Support\Facades\Route::aliasMiddleware('tenant.scope', \App\Http\Middleware\TenantScope::class);
    }
}