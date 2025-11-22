<?php

namespace App\Domains\Access\Services;

use App\Domains\Access\Contracts\PermissionRepositoryInterface;

class PermissionService
{
    protected $permissionRepository;

    public function __construct(PermissionRepositoryInterface $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Create a permission for a specific tenant
     *
     * @param string $tenantId
     * @param string $name
     * @param string $description
     * @return mixed
     */
    public function createForTenant(string $tenantId, string $name, string $description)
    {
        return $this->permissionRepository->create([
            'tenant_id' => $tenantId,
            'name' => $name,
            'description' => $description
        ]);
    }

    /**
     * Create a global permission (not tenant-specific)
     *
     * @param string $name
     * @param string $description
     * @return mixed
     */
    public function createGlobal(string $name, string $description)
    {
        return $this->permissionRepository->create([
            'name' => $name,
            'description' => $description
        ]);
    }
}