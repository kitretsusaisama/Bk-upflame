<?php

namespace App\Http\Controllers\Api\V1\Sso;

use App\Http\Controllers\Controller;
use App\Domain\SSO\Services\SsoService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SsoCallbackController extends Controller
{
    /**
     * Handle SSO callback from the specified provider.
     *
     * @param  string  $provider
     * @param  \App\Domain\SSO\Services\SsoService  $ssoService
     * @return \Illuminate\Http\JsonResponse
     */
    public function callback(string $provider, SsoService $ssoService): JsonResponse
    {
        try {
            $result = $ssoService->handleCallback($provider, request()->all());
            
            if (!$result['success']) {
                return response()->json([
                    'message' => $result['message']
                ], 422);
            }
            
            return response()->json([
                'message' => 'SSO login successful',
                'user' => $result['user'],
                'token' => $result['token']
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'message' => 'SSO callback failed: ' . $e->getMessage()
            ], 500);
        }
    }
}