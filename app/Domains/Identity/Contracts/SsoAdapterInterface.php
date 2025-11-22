<?php

namespace App\Domains\Identity\Contracts;

use Illuminate\Http\Request;

interface SsoAdapterInterface
{
    /**
     * Get the redirect URL for the SSO flow
     *
     * @param array $context
     * @return string
     */
    public function getRedirectUrl(array $context): string;

    /**
     * Handle the callback from the SSO provider
     *
     * @param Request $request
     * @return array
     */
    public function handleCallback(Request $request): array;

    /**
     * Map external user data to local user format
     *
     * @param array $payload
     * @return array
     */
    public function mapExternalUser(array $payload): array;
}