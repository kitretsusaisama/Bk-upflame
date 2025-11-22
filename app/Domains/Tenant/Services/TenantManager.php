<?php

namespace App\Domains\Tenant\Services;

use App\Domains\Tenant\Models\Tenant;

class TenantManager
{
    protected $tenant;

    /**
     * Set the current tenant
     *
     * @param Tenant|null $tenant
     * @return void
     */
    public function setTenant(?Tenant $tenant): void
    {
        $this->tenant = $tenant;
    }

    /**
     * Get the current tenant
     *
     * @return Tenant|null
     */
    public function getTenant(): ?Tenant
    {
        return $this->tenant;
    }

    /**
     * Get the current tenant ID
     *
     * @return string|null
     */
    public function getTenantId(): ?string
    {
        return $this->tenant ? $this->tenant->id : null;
    }

    /**
     * Check if a tenant is set
     *
     * @return bool
     */
    public function hasTenant(): bool
    {
        return $this->tenant !== null;
    }

    /**
     * Get tenant config value
     *
     * @param string $key
     * @param mixed $default
     * @return mixed
     */
    public function getConfig(string $key, $default = null)
    {
        if (!$this->tenant) {
            return $default;
        }

        $config = $this->tenant->config;
        return $config && isset($config[$key]) ? $config[$key] : $default;
    }
}