<?php

namespace App\Http\Middleware;

use App\Domains\Tenant\Services\TenantManager;
use Closure;
use Illuminate\Http\Request;

class ApplyTenantScope
{
    protected $tenantManager;

    public function __construct(TenantManager $tenantManager)
    {
        $this->tenantManager = $tenantManager;
    }

    public function handle(Request $request, Closure $next)
    {
        // Register global Eloquent scope for tenant-scoped models
        if ($this->tenantManager->hasTenant()) {
            $tenantId = $this->tenantManager->getTenantId();
            
            // This would typically register a global scope on models
            // For now, we'll just make the tenant ID available in the request
            $request->attributes->set('tenant_id', $tenantId);
        }

        return $next($request);
    }
}