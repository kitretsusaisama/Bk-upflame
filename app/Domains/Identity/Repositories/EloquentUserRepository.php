<?php

namespace App\Domains\Identity\Repositories;

use App\Domains\Identity\Contracts\UserRepositoryInterface;
use App\Domains\Identity\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class EloquentUserRepository implements UserRepositoryInterface
{
    protected $model;

    public function __construct(User $model)
    {
        $this->model = $model;
    }

    public function findById(string $id): ?User
    {
        return $this->model->find($id);
    }

    public function findByEmail(string $email, ?string $tenantId = null): ?User
    {
        $query = $this->model->where('email', $email);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->first();
    }

    public function findByTenant(string $tenantId, int $limit = 20): LengthAwarePaginator
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findByStatus(string $status, ?string $tenantId = null, int $limit = 20): LengthAwarePaginator
    {
        $query = $this->model->where('status', $status);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
    }

    public function create(array $data): User
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

    public function incrementFailedAttempts(string $userId): void
    {
        $this->model->where('id', $userId)->increment('failed_login_attempts');
    }

    public function resetFailedAttempts(string $userId): void
    {
        $this->model->where('id', $userId)->update([
            'failed_login_attempts' => 0,
            'locked_until' => null
        ]);
    }

    public function lockAccount(string $userId, int $minutes = 30): void
    {
        $this->model->where('id', $userId)->update([
            'locked_until' => now()->addMinutes($minutes)
        ]);
    }
}