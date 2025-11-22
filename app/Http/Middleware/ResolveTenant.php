<?php

namespace App\Http\Middleware;

use App\Domains\Tenant\Services\TenantManager;
use App\Domains\Tenant\Contracts\TenantRepositoryInterface;
use Closure;
use Illuminate\Http\Request;

class ResolveTenant
{
    protected $tenantManager;
    protected $tenantRepository;

    public function __construct(TenantManager $tenantManager, TenantRepositoryInterface $tenantRepository)
    {
        $this->tenantManager = $tenantManager;
        $this->tenantRepository = $tenantRepository;
    }

    public function handle(Request $request, Closure $next)
    {
        // 1. X-Tenant-ID header (for API clients)
        if ($request->hasHeader('X-Tenant-ID')) {
            $tenant = $this->tenantRepository->findById($request->header('X-Tenant-ID'));
            if ($tenant) {
                $this->tenantManager->setTenant($tenant);
                return $next($request);
            }
        }

        // 2. Hostname/subdomain resolution
        $host = $this->normalizeHost($request->getHost());
        $tenant = $this->tenantRepository->findByDomain($host);
        if ($tenant) {
            $this->tenantManager->setTenant($tenant);
            return $next($request);
        }

        // 3. Token claim (for authenticated requests)
        if ($request->user()) {
            $tenant = $this->tenantRepository->findById($request->user()->tenant_id);
            if ($tenant) {
                $this->tenantManager->setTenant($tenant);
                return $next($request);
            }
        }

        // 4. tenant_id parameter (for specific routes)
        if ($request->filled('tenant_id')) {
            $tenant = $this->tenantRepository->findById($request->input('tenant_id'));
            if ($tenant) {
                $this->tenantManager->setTenant($tenant);
                return $next($request);
            }
        }

        // If no tenant found, proceed without tenant context
        return $next($request);
    }

    protected function normalizeHost($host)
    {
        // Remove port number if present
        return preg_replace('/:\d+$/', '', $host);
    }
}