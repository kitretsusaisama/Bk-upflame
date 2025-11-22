<?php

namespace App\Domains\Tenant\Contracts;

use App\Domains\Tenant\Models\Tenant;
use Illuminate\Pagination\LengthAwarePaginator;

interface TenantRepositoryInterface
{
    public function findById(string $id): ?Tenant;
    
    public function findByDomain(string $domain): ?Tenant;
    
    public function findByName(string $name): ?Tenant;
    
    public function all(int $limit = 20): LengthAwarePaginator;
    
    public function create(array $data): Tenant;
    
    public function update(string $id, array $data): bool;
    
    public function delete(string $id): bool;
}