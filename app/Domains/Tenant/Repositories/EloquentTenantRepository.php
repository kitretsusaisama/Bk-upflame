<?php

namespace App\Domains\Tenant\Repositories;

use App\Domains\Tenant\Contracts\TenantRepositoryInterface;
use App\Domains\Tenant\Models\Tenant;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentTenantRepository implements TenantRepositoryInterface
{
    protected $model;

    public function __construct(Tenant $model)
    {
        $this->model = $model;
    }

    public function findById(string $id): ?Tenant
    {
        return $this->model->find($id);
    }

    public function findByDomain(string $domain): ?Tenant
    {
        return $this->model->whereHas('domains', function ($query) use ($domain) {
            $query->where('domain', $domain);
        })->first();
    }

    public function findByName(string $name): ?Tenant
    {
        return $this->model->where('name', $name)->first();
    }

    public function all(int $limit = 20): LengthAwarePaginator
    {
        return $this->model->paginate($limit);
    }

    public function create(array $data): Tenant
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