<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TenantResolution;
use App\Http\Middleware\TenantScope;
use App\Http\Middleware\EnsureDashboardAccess;
use App\Http\Middleware\SessionSecurity;
use App\Http\Middleware\AutoLogoutIdle;

return Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        api: __DIR__.'/../routes/api.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->alias([
            'tenant.resolution' => TenantResolution::class,
            'tenant.scope' => TenantScope::class,
            'dashboard.access' => EnsureDashboardAccess::class,
            'session.security' => SessionSecurity::class,
            'auto.logout' => AutoLogoutIdle::class,
            'impersonation.check' => \App\Http\Middleware\CheckImpersonation::class,
        ]);

        $middleware->web(append: [
            \App\Http\Middleware\CheckImpersonation::class,
            \App\Http\Middleware\ShareSidebarData::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();