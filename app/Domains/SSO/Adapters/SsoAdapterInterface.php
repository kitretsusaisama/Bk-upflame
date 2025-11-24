<?php

namespace App\Domains\SSO\Adapters;

use App\Domains\SSO\Models\SsoProvider;

interface SsoAdapterInterface
{
    /**
     * Get the redirect URL for the SSO provider.
     *
     * @param  SsoProvider  $provider
     * @return string
     */
    public function getRedirectUrl(SsoProvider $provider): string;

    /**
     * Handle the callback from the SSO provider.
     *
     * @param  SsoProvider  $provider
     * @param  array  $data
     * @return array
     */
    public function handleCallback(SsoProvider $provider, array $data): array;

    /**
     * Get the user information from the SSO provider.
     *
     * @param  SsoProvider  $provider
     * @param  string  $token
     * @return array
     */
    public function getUserInfo(SsoProvider $provider, string $token): array;
}