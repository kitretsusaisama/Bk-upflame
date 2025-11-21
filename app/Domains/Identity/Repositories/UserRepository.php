<?php

namespace App\Domains\Identity\Repositories;

use App\Support\BaseRepository;
use App\Domains\Identity\Models\User;

class UserRepository extends BaseRepository
{
    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    public function findByEmail(string $email, ?string $tenantId = null)
    {
        $query = $this->model->where('email', $email);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->first();
    }

    public function findByTenant(string $tenantId, int $limit = 20)
    {
        return $this->model->where('tenant_id', $tenantId)->paginate($limit);
    }

    public function findByStatus(string $status, ?string $tenantId = null, int $limit = 20)
    {
        $query = $this->model->where('status', $status);
        
        if ($tenantId) {
            $query->where('tenant_id', $tenantId);
        }
        
        return $query->paginate($limit);
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
