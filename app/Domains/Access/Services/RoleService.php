<?php

namespace App\Domains\Access\Services;

use App\Domains\Access\Contracts\RoleRepositoryInterface;
use App\Domains\Access\Contracts\PermissionRepositoryInterface;

class RoleService
{
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(
        RoleRepositoryInterface $roleRepository,
        PermissionRepositoryInterface $permissionRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    /**
     * Create a role for a specific tenant
     *
     * @param string $tenantId
     * @param string $name
     * @param string $description
     * @param array $permissionNames
     * @return mixed
     */
    public function createForTenant(string $tenantId, string $name, string $description, array $permissionNames = [])
    {
        // Create the role
        $role = $this->roleRepository->create([
            'tenant_id' => $tenantId,
            'name' => $name,
            'description' => $description
        ]);

        // Attach permissions if provided
        if (!empty($permissionNames)) {
            $permissionIds = [];
            foreach ($permissionNames as $permissionName) {
                $permission = $this->permissionRepository->findByName($permissionName, $tenantId);
                if ($permission) {
                    $permissionIds[] = $permission->id;
                }
            }
            
            if (!empty($permissionIds)) {
                $this->roleRepository->attachPermissions($role->id, $permissionIds);
            }
        }

        return $role;
    }

    /**
     * Assign permissions to a role
     *
     * @param string $roleId
     * @param array $permissionNames
     * @return void
     */
    public function assignPermissions(string $roleId, array $permissionNames): void
    {
        $role = $this->roleRepository->findById($roleId);
        if (!$role) {
            throw new \Exception("Role not found");
        }

        $permissionIds = [];
        foreach ($permissionNames as $permissionName) {
            $permission = $this->permissionRepository->findByName($permissionName, $role->tenant_id);
            if ($permission) {
                $permissionIds[] = $permission->id;
            }
        }
        
        if (!empty($permissionIds)) {
            $this->roleRepository->attachPermissions($roleId, $permissionIds);
        }
    }

    /**
     * Remove permissions from a role
     *
     * @param string $roleId
     * @param array $permissionNames
     * @return void
     */
    public function removePermissions(string $roleId, array $permissionNames): void
    {
        $role = $this->roleRepository->findById($roleId);
        if (!$role) {
            throw new \Exception("Role not found");
        }

        $permissionIds = [];
        foreach ($permissionNames as $permissionName) {
            $permission = $this->permissionRepository->findByName($permissionName, $role->tenant_id);
            if ($permission) {
                $permissionIds[] = $permission->id;
            }
        }
        
        if (!empty($permissionIds)) {
            $this->roleRepository->detachPermissions($roleId, $permissionIds);
        }
    }
}