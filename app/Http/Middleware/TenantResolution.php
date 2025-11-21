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
            // For local development, set a default tenant if none found
            if (app()->environment('local')) {
                $tenant = $this->tenantService->findById('default');
                if (!$tenant) {
                    // Try to get any tenant as fallback for local development
                    $tenant = $this->tenantService->getAllTenants(1)->first();
                }
            }
            
            // If still no tenant, return error
            if (!$tenant) {
                return response()->json([
                    'status' => 'error',
                    'error' => [
                        'code' => 'TENANT_NOT_FOUND',
                        'message' => 'Tenant not found'
                    ]
                ], 404);
            }
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
        // 1. Header override (local only)
        if (app()->environment('local') && $request->hasHeader('X-Tenant-ID')) {
            return $this->tenantService->findById($request->header('X-Tenant-ID'));
        }
        
        // 2. Query parameter override (local only)
        if (app()->environment('local') && $request->query('tenant_id')) {
            return $this->tenantService->findById($request->query('tenant_id'));
        }
        
        // 3. Domain resolution
        $host = $request->getHost();
        if ($host && $host !== 'localhost' && !filter_var($host, FILTER_VALIDATE_IP)) {
            return $this->tenantService->resolveTenantFromDomain($host);
        }
        
        // 4. Fallback for localhost in local environment
        if (app()->environment('local') && ($host === 'localhost' || $host === '127.0.0.1')) {
            // Try to find a default tenant
            return $this->tenantService->findById('default') ?? 
                   $this->tenantService->getAllTenants(1)->first();
        }
        
        return null;
    }
}