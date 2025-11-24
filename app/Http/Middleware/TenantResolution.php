<?php

namespace App\Http\Middleware;

use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\TenantDomain;
use Closure;
use Illuminate\Http\Request;

class TenantResolution
{
    public function handle($request, Closure $next)
{
    // SUPER ADMIN must bypass tenant logic but still have a tenant context
    if (auth()->check()) {
        $user = auth()->user();
        // Ensure roles are loaded
        if (!$user->relationLoaded('roles')) {
            $user->load('roles');
        }
        
        if ($user->hasRole('Super Admin')) {
            $fallbackTenant = Tenant::first();
            app()->instance('tenant', $fallbackTenant);
            return $next($request);
        }
    }

    $host = $this->normalizeHost($request->getHost());

    // 1. Domain resolution (production)
    $tenantDomain = TenantDomain::where('domain', $host)->first();
    if ($tenantDomain) {
        app()->instance('tenant', $tenantDomain->tenant);
        return $next($request);
    }

    // 2. Header override (local only)
    if (app()->environment('local') && $request->hasHeader('X-Tenant-ID')) {
        $tenant = Tenant::find($request->header('X-Tenant-ID'));
        if ($tenant) {
            app()->instance('tenant', $tenant);
            return $next($request);
        }
    }

    // 3. Session tenant (local only)
    if (app()->environment('local') && session('tenant_id')) {
        app()->instance('tenant', Tenant::find(session('tenant_id')));
        return $next($request);
    }

    // 4. Fallback for localhost
    if (app()->environment('local') && in_array($host, ['localhost', '127.0.0.1'])) {
        $fallbackTenant = Tenant::first();
        app()->instance('tenant', $fallbackTenant);
        return $next($request);
    }

    // Global routes fallback
    $fallbackTenant = Tenant::first();
    app()->instance('tenant', $fallbackTenant);
    return $next($request);
}
    

    protected function normalizeHost($host)
    {
        // Remove port number if present
        return preg_replace('/:\d+$/', '', $host);
    }
}