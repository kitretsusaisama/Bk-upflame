<?php

namespace App\Domains\Tenant\Repositories;

use App\Support\Domain\Shared\Contracts\Repository;
use App\Domains\Tenant\Models\Tenant;
use Illuminate\Database\Eloquent\Collection;

class TenantRepository implements Repository
{
    /**
     * Find a tenant by its ID.
     *
     * @param  mixed  $id
     * @return Tenant|null
     */
    public function find($id)
    {
        return Tenant::find($id);
    }

    /**
     * Find a tenant by its ID or throw an exception.
     *
     * @param  mixed  $id
     * @return Tenant
     */
    public function findOrFail($id)
    {
        return Tenant::findOrFail($id);
    }

    /**
     * Get all tenants.
     *
     * @return Collection
     */
    public function all()
    {
        return Tenant::all();
    }

    /**
     * Create a new tenant.
     *
     * @param  array  $data
     * @return Tenant
     */
    public function create(array $data)
    {
        return Tenant::create($data);
    }

    /**
     * Update a tenant.
     *
     * @param  mixed  $id
     * @param  array  $data
     * @return Tenant
     */
    public function update($id, array $data)
    {
        $tenant = $this->findOrFail($id);
        $tenant->update($data);
        return $tenant;
    }

    /**
     * Delete a tenant.
     *
     * @param  mixed  $id
     * @return bool
     */
    public function delete($id)
    {
        $tenant = $this->findOrFail($id);
        return $tenant->delete();
    }

    /**
     * Find a tenant by domain.
     *
     * @param  string  $domain
     * @return Tenant|null
     */
    public function findByDomain(string $domain)
    {
        return Tenant::where('domain', $domain)->first();
    }
}