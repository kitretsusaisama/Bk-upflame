<?php

namespace App\Domains\Identity\Http\Controllers;

use App\Domains\Identity\Services\SsoAdapterManager;
use App\Domains\Tenant\Services\TenantManager;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class SsoController
{
    protected $ssoManager;
    protected $tenantManager;

    public function __construct(SsoAdapterManager $ssoManager, TenantManager $tenantManager)
    {
        $this->ssoManager = $ssoManager;
        $this->tenantManager = $tenantManager;
    }

    /**
     * Redirect to SSO provider
     *
     * @param string $provider
     * @param Request $request
     * @return JsonResponse
     */
    public function redirect(string $provider, Request $request): JsonResponse
    {
        try {
            $context = [
                'tenant_id' => $this->tenantManager->getTenantId(),
                'redirect_url' => $request->input('redirect_url', '/')
            ];

            $redirectUrl = $this->ssoManager->getRedirectUrl($provider, $context);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'redirect_url' => $redirectUrl
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SSO_REDIRECT_ERROR',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }

    /**
     * Handle SSO callback
     *
     * @param string $provider
     * @param Request $request
     * @return JsonResponse
     */
    public function callback(string $provider, Request $request): JsonResponse
    {
        try {
            $result = $this->ssoManager->handleCallback($provider, $request);

            if ($result['status'] === 'success') {
                return response()->json([
                    'status' => 'success',
                    'data' => [
                        'access_token' => $result['access_token'],
                        'token_type' => 'Bearer',
                        'expires_in' => 3600,
                        'user' => $result['user']
                    ]
                ]);
            } else {
                return response()->json([
                    'status' => 'error',
                    'error' => $result['error']
                ], 400);
            }
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SSO_CALLBACK_ERROR',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }
}