<?php

namespace App\Domains\Identity\Contracts;

use App\Domains\Identity\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

interface UserRepositoryInterface
{
    public function findById(string $id): ?User;
    
    public function findByEmail(string $email, ?string $tenantId = null): ?User;
    
    public function findByTenant(string $tenantId, int $limit = 20): LengthAwarePaginator;
    
    public function findByStatus(string $status, ?string $tenantId = null, int $limit = 20): LengthAwarePaginator;
    
    public function create(array $data): User;
    
    public function update(string $id, array $data): bool;
    
    public function delete(string $id): bool;
    
    public function incrementFailedAttempts(string $userId): void;
    
    public function resetFailedAttempts(string $userId): void;
    
    public function lockAccount(string $userId, int $minutes = 30): void;
}