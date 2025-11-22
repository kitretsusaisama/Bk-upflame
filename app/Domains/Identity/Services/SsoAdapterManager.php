<?php

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Contracts\SsoAdapterInterface;
use App\Domains\Tenant\Models\Tenant;
use Illuminate\Support\Str;

class SsoAdapterManager
{
    protected $adapters = [];

    /**
     * Register an SSO adapter
     *
     * @param string $providerKey
     * @param SsoAdapterInterface $adapter
     * @return void
     */
    public function registerAdapter(string $providerKey, SsoAdapterInterface $adapter): void
    {
        $this->adapters[$providerKey] = $adapter;
    }

    /**
     * Get an SSO adapter by provider key
     *
     * @param string $providerKey
     * @return SsoAdapterInterface
     * @throws \Exception
     */
    public function getAdapter(string $providerKey): SsoAdapterInterface
    {
        if (!isset($this->adapters[$providerKey])) {
            throw new \Exception("SSO adapter for provider '{$providerKey}' not found");
        }

        return $this->adapters[$providerKey];
    }

    /**
     * Get the redirect URL for a provider
     *
     * @param string $providerKey
     * @param array $context
     * @return string
     */
    public function getRedirectUrl(string $providerKey, array $context = []): string
    {
        $adapter = $this->getAdapter($providerKey);
        return $adapter->getRedirectUrl($context);
    }

    /**
     * Handle the callback from a provider
     *
     * @param string $providerKey
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function handleCallback(string $providerKey, \Illuminate\Http\Request $request): array
    {
        $adapter = $this->getAdapter($providerKey);
        return $adapter->handleCallback($request);
    }

    /**
     * Load tenant-specific SSO providers
     *
     * @param Tenant $tenant
     * @return void
     */
    public function loadTenantProviders(Tenant $tenant): void
    {
        // In a real implementation, this would load providers from the database
        // For now, we'll leave it as a placeholder
    }
}