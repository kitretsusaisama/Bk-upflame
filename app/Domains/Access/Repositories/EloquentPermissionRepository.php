<?php

namespace App\Domains\Access\Repositories;

use App\Domains\Access\Contracts\PermissionRepositoryInterface;
use App\Domains\Access\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentPermissionRepository implements PermissionRepositoryInterface
{
    protected $model;

    public function __construct(Permission $model)
    {
        $this->model = $model;
    }

    public function findById(string $id): ?Permission
    {
        return $this->model->find($id);
    }

    public function findByName(string $name, ?string $tenantId = null): ?Permission
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

    public function create(array $data): Permission
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
}