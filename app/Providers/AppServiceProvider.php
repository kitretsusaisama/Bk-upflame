<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
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
