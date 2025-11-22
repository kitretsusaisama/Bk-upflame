<?php

namespace App\Domains\Access\Repositories;

use App\Domains\Access\Contracts\RoleRepositoryInterface;
use App\Domains\Access\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentRoleRepository implements RoleRepositoryInterface
{
    protected $model;

    public function __construct(Role $model)
    {
        $this->model = $model;
    }

    public function findById(string $id): ?Role
    {
        return $this->model->find($id);
    }

    public function findByName(string $name, ?string $tenantId = null): ?Role
    {
        $query = $this->model->where('name', $name);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        } else {
            $query->whereNull('tenant_id');
        }
        
        return $query->first();
    }

    public function findByTenant(string $tenantId, int $limit = 20): LengthAwarePaginator
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function all(int $limit = 20): LengthAwarePaginator
    {
        return $this->model->paginate($limit);
    }

    public function create(array $data): Role
    {
        return $this->model->create($data);
    }

    public function update(string $id, array $data): bool
    {
        return $this->model->where('id', $id)->update($data);
    }

    public function delete(string $id): bool
    {
        return $this->model->where('id', $id)->delete();
    }

    public function attachPermissions(string $roleId, array $permissionIds): void
    {
        $this->model->find($roleId)->permissions()->attach($permissionIds);
    }

    public function detachPermissions(string $roleId, array $permissionIds): void
    {
        $this->model->find($roleId)->permissions()->detach($permissionIds);
    }
}