<?php

namespace App\Domains\Access\Repositories;

use App\Domains\Access\Models\Role;
use App\Support\BaseRepository;

class RoleRepository extends BaseRepository
{
    public function __construct(Role $model)
    {
        parent::__construct($model);
    }

    public function findByName($name, $tenantId = null)
    {
        $query = $this->model->where('name', $name);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->first();
    }

    public function findByTenant($tenantId, $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findByRoleFamily($roleFamily, $tenantId = null, $limit = 20)
    {
        $query = $this->model->where('role_family', $roleFamily);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }

    public function attachPermission($roleId, $permissionId)
    {
        $role = $this->findById($roleId);
        if ($role) {
            $role->permissions()->attach($permissionId);
            return true;
        }
        return false;
    }

    public function detachPermission($roleId, $permissionId)
    {
        $role = $this->findById($roleId);
        if ($role) {
            $role->permissions()->detach($permissionId);
            return true;
        }
        return false;
    }
}
