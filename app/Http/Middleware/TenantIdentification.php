<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class TenantIdentification
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        // Get tenant identifier based on configured strategy
        $tenantId = $this->identifyTenant($request);
        
        // If tenant identified, set in application context
        if ($tenantId) {
            // Set the tenant context in the application
            app()->singleton('tenant.id', function() use ($tenantId) {
                return $tenantId;
            });
            
            // Also set it in the request for easy access
            $request->attributes->set('tenant_id', $tenantId);
        }
        
        return $next($request);
    }
    
    /**
     * Identify tenant based on request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function identifyTenant(Request $request)
    {
        $strategy = config('tenant.identification_strategy', 'domain');
        
        switch ($strategy) {
            case 'domain':
                return $this->identifyByDomain($request);
            case 'subdomain':
                return $this->identifyBySubdomain($request);
            case 'header':
                return $this->identifyByHeader($request);
            case 'parameter':
                return $this->identifyByParameter($request);
            default:
                return null;
        }
    }
    
    /**
     * Identify tenant by domain.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function identifyByDomain(Request $request)
    {
        $host = $request->getHost();
        // Logic to resolve tenant from domain
        // This would typically query the tenant_domains table
        return null;
    }
    
    /**
     * Identify tenant by subdomain.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function identifyBySubdomain(Request $request)
    {
        $host = $request->getHost();
        $parts = explode('.', $host);
        
        if (count($parts) >= 3) {
            return $parts[0]; // Assuming subdomain is the first part
        }
        
        return null;
    }
    
    /**
     * Identify tenant by header.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function identifyByHeader(Request $request)
    {
        return $request->header('X-Tenant-ID');
    }
    
    /**
     * Identify tenant by parameter.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string|null
     */
    protected function identifyByParameter(Request $request)
    {
        return $request->get('tenant_id');
    }
}