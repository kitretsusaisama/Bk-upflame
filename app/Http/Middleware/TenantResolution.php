<?php

namespace App\Http\Middleware;

use App\Domains\Identity\Services\TenantService;
use Closure;
use Illuminate\Http\Request;

class TenantResolution
{
    protected $tenantService;

    public function __construct(TenantService $tenantService)
    {
        $this->tenantService = $tenantService;
    }

    public function handle(Request $request, Closure $next)
    {
        $tenant = $this->resolveTenant($request);
        
        if (!$tenant) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'TENANT_NOT_FOUND',
                    'message' => 'Tenant not found'
                ]
            ], 404);
        }

        if (!$tenant->isActive()) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'TENANT_INACTIVE',
                    'message' => 'Tenant is not active'
                ]
            ], 403);
        }
        
        $request->attributes->set('tenant', $tenant);
        $request->merge(['tenant_id' => $tenant->id]);
        app()->instance('tenant', $tenant);
        
        return $next($request);
    }

    protected function resolveTenant(Request $request)
    {
        if ($tenantId = $request->header('X-Tenant-ID')) {
            return $this->tenantService->findById($tenantId);
        }
        
        $host = $request->getHost();
        if ($host && $host !== 'localhost' && !filter_var($host, FILTER_VALIDATE_IP)) {
            return $this->tenantService->resolveTenantFromDomain($host);
        }
        
        if ($tenantId = $request->query('tenant_id')) {
            return $this->tenantService->findById($tenantId);
        }
        
        return null;
    }
}
