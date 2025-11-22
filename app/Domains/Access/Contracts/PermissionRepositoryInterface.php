<?php

namespace App\Domains\Access\Contracts;

use App\Domains\Access\Models\Permission;
use Illuminate\Pagination\LengthAwarePaginator;

interface PermissionRepositoryInterface
{
    public function findById(string $id): ?Permission;
    
    public function findByName(string $name, ?string $tenantId = null): ?Permission;
    
    public function findByTenant(string $tenantId, int $limit = 20): LengthAwarePaginator;
    
    public function all(int $limit = 20): LengthAwarePaginator;
    
    public function create(array $data): Permission;
    
    public function update(string $id, array $data): bool;
    
    public function delete(string $id): bool;
}