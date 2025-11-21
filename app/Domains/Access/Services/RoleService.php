<?php

namespace App\Domains\Access\Services;

use App\Domains\Access\Repositories\RoleRepository;
use App\Domains\Access\Repositories\PermissionRepository;
use Illuminate\Support\Str;

class RoleService
{
    protected $roleRepository;
    protected $permissionRepository;

    public function __construct(
        RoleRepository $roleRepository,
        PermissionRepository $permissionRepository
    ) {
        $this->roleRepository = $roleRepository;
        $this->permissionRepository = $permissionRepository;
    }

    public function createRole($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        $role = $this->roleRepository->create($data);
        
        if (isset($data['permissions']) && is_array($data['permissions'])) {
            foreach ($data['permissions'] as $permissionName) {
                $permission = $this->permissionRepository->findByName($permissionName);
                if ($permission) {
                    $this->roleRepository->attachPermission($role->id, $permission->id);
                }
            }
        }
        
        return $role->load('permissions');
    }

    public function updateRole($id, $data)
    {
        $role = $this->roleRepository->update($id, $data);
        
        if ($role && isset($data['permissions']) && is_array($data['permissions'])) {
            $permissionIds = [];
            foreach ($data['permissions'] as $permissionName) {
                $permission = $this->permissionRepository->findByName($permissionName);
                if ($permission) {
                    $permissionIds[] = $permission->id;
                }
            }
            
            $role->permissions()->sync($permissionIds);
        }
        
        return $role ? $role->load('permissions') : null;
    }

    public function deleteRole($id)
    {
        return $this->roleRepository->delete($id);
    }

    public function getRolesByTenant($tenantId, $limit = 20)
    {
        return $this->roleRepository->findByTenant($tenantId, $limit);
    }

    public function getRoleById($id)
    {
        $role = $this->roleRepository->findById($id);
        return $role ? $role->load('permissions') : null;
    }

    public function attachPermission($roleId, $permissionId)
    {
        return $this->roleRepository->attachPermission($roleId, $permissionId);
    }

    public function detachPermission($roleId, $permissionId)
    {
        return $this->roleRepository->detachPermission($roleId, $permissionId);
    }
}
