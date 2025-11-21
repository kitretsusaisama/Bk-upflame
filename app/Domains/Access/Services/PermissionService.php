<?php

namespace App\Domains\Access\Services;

use App\Domains\Access\Repositories\PermissionRepository;
use Illuminate\Support\Str;

class PermissionService
{
    protected $permissionRepository;

    public function __construct(PermissionRepository $permissionRepository)
    {
        $this->permissionRepository = $permissionRepository;
    }

    public function createPermission($data)
    {
        $data['id'] = Str::uuid()->toString();
        
        if (!isset($data['name']) && isset($data['resource']) && isset($data['action'])) {
            $data['name'] = $data['resource'] . '.' . $data['action'];
        }
        
        return $this->permissionRepository->create($data);
    }

    public function updatePermission($id, $data)
    {
        return $this->permissionRepository->update($id, $data);
    }

    public function deletePermission($id)
    {
        return $this->permissionRepository->delete($id);
    }

    public function getPermissionById($id)
    {
        return $this->permissionRepository->findById($id);
    }

    public function getAllPermissions($limit = 20)
    {
        return $this->permissionRepository->findAll($limit);
    }
}
