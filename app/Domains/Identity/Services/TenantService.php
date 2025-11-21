<?php

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Repositories\TenantRepository;
use Illuminate\Support\Str;

class TenantService
{
    protected $tenantRepository;

    public function __construct(TenantRepository $tenantRepository)
    {
        $this->tenantRepository = $tenantRepository;
    }

    public function createTenant(array $data): Tenant
    {
        $data['status'] = $data['status'] ?? 'active';
        
        $tenant = $this->tenantRepository->create($data);
        
        $this->createDefaultConfig($tenant);
        
        return $tenant;
    }

    public function resolveTenantFromDomain(string $domain): ?Tenant
    {
        // First check the main domain column in tenants table
        $tenant = $this->tenantRepository->findByDomain($domain);
        
        // If not found, check the tenant_domains table
        if (!$tenant) {
            $tenant = $this->tenantRepository->findByDomainThroughMapping($domain);
        }
        
        // Special case for default.local - if we're looking for it but didn't find it
        // through normal channels, try to find the default tenant
        if ($domain === 'default.local' && !$tenant) {
            $tenant = $this->tenantRepository->getModel()->where('domain', 'default.local')->first();
        }
        
        return $tenant;
    }

    public function findById(string $id): ?Tenant
    {
        return $this->tenantRepository->findById($id);
    }

    protected function createDefaultConfig(Tenant $tenant): void
    {
        $tenant->configs()->create([
            'branding_config' => [],
            'security_config' => [
                'password_min_length' => 12,
                'require_mfa' => false,
                'session_timeout' => 3600
            ],
            'feature_flags' => [
                'booking_enabled' => true,
                'notifications_enabled' => true
            ]
        ]);
    }

    public function enableModule(string $tenantId, string $moduleName, array $config = []): void
    {
        $tenant = $this->tenantRepository->findById($tenantId);
        
        $tenant->modules()->updateOrCreate(
            ['module_name' => $moduleName],
            ['is_enabled' => true, 'config' => $config]
        );
    }

    public function disableModule(string $tenantId, string $moduleName): void
    {
        $tenant = $this->tenantRepository->findById($tenantId);
        
        $tenant->modules()->where('module_name', $moduleName)
            ->update(['is_enabled' => false]);
    }

    public function getAllTenants($limit = 20)
    {
        return $this->tenantRepository->findAll($limit);
    }

    public function getTenantById($id)
    {
        $tenant = $this->tenantRepository->findById($id);
        return $tenant ? $tenant->load(['domains', 'configs']) : null;
    }

    public function updateTenant($id, array $data)
    {
        return $this->tenantRepository->update($id, $data);
    }

    public function createTenantWithAdmin(array $data)
    {
        $tenantData = [
            'id' => Str::uuid()->toString(),
            'name' => $data['name'],
            'domain' => $data['domain'],
            'status' => 'active'
        ];
        
        $tenant = $this->createTenant($tenantData);
        
        return $tenant->load(['domains', 'configs']);
    }

    public function addDomain($tenantId, array $data)
    {
        $tenant = $this->tenantRepository->findById($tenantId);
        
        if (!$tenant) {
            throw new \Exception('Tenant not found');
        }
        
        return $tenant->domains()->create([
            'id' => Str::uuid()->toString(),
            'domain' => $data['domain'],
            'is_primary' => $data['is_primary'] ?? false,
            'is_verified' => false
        ]);
    }
}