<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;
use App\Http\Middleware\TenantResolution;
use App\Http\Middleware\TenantScope;
use App\Http\Middleware\ResolveTenant;
use App\Http\Middleware\ApplyTenantScope;
use App\Http\Middleware\EnsurePermission;
use App\Http\Middleware\ThrottleOtpRequests;
use App\Http\Middleware\DebugCsrfToken;

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
            'resolve.tenant' => ResolveTenant::class,
            'apply.tenant.scope' => ApplyTenantScope::class,
            'permission' => EnsurePermission::class,
            'throttle.otp' => ThrottleOtpRequests::class,
        ]);
        
        // Add global middleware for debugging
        $middleware->append(DebugCsrfToken::class);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })->create();