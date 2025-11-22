<?php

namespace App\Domains\Access\Contracts;

use App\Domains\Access\Models\Role;
use Illuminate\Pagination\LengthAwarePaginator;

interface RoleRepositoryInterface
{
    public function findById(string $id): ?Role;
    
    public function findByName(string $name, ?string $tenantId = null): ?Role;
    
    public function findByTenant(string $tenantId, int $limit = 20): LengthAwarePaginator;
    
    public function all(int $limit = 20): LengthAwarePaginator;
    
    public function create(array $data): Role;
    
    public function update(string $id, array $data): bool;
    
    public function delete(string $id): bool;
    
    public function attachPermissions(string $roleId, array $permissionIds): void;
    
    public function detachPermissions(string $roleId, array $permissionIds): void;
}