<?php

namespace App\Http\Controllers\Api\V1\Sso;

use App\Http\Controllers\Controller;
use App\Domain\SSO\Services\SsoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SsoController extends Controller
{
    /**
     * Initiate SSO login with the specified provider.
     *
     * @param  string  $provider
     * @param  \App\Domain\SSO\Services\SsoService  $ssoService
     * @return \Illuminate\Http\JsonResponse
     */
    public function login(string $provider, SsoService $ssoService): JsonResponse
    {
        try {
            $redirectUrl = $ssoService->initiateLogin($provider);
            
            return response()->json([
                'message' => 'SSO login initiated',
                'redirect_url' => $redirectUrl
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'Failed to initiate SSO login: ' . $e->getMessage()
            ], 422);
        }
    }
}