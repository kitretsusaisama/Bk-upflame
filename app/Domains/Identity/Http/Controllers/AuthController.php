<?php

namespace App\Domains\Identity\Http\Controllers;

use App\Domains\Identity\Http\Requests\LoginRequest;
use App\Domains\Identity\Http\Requests\RegisterRequest;
use App\Domains\Identity\Actions\LoginWithPassword;
use App\Domains\Identity\Actions\RegisterUser;
use App\Domains\Identity\Actions\RequestOtp;
use App\Domains\Identity\Actions\VerifyOtp;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController
{
    protected $loginAction;
    protected $registerAction;
    protected $requestOtpAction;
    protected $verifyOtpAction;

    public function __construct(
        LoginWithPassword $loginAction,
        RegisterUser $registerAction,
        RequestOtp $requestOtpAction,
        VerifyOtp $verifyOtpAction
    ) {
        $this->loginAction = $loginAction;
        $this->registerAction = $registerAction;
        $this->requestOtpAction = $requestOtpAction;
        $this->verifyOtpAction = $verifyOtpAction;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        $result = $this->loginAction->execute(
            $request->input('email'),
            $request->input('password'),
            $request->tenant_id ?? null
        );

        // Determine appropriate status code based on error type
        $statusCode = 200;
        if ($result['status'] !== 'success') {
            // Check if it's an authentication error (like inactive account)
            if (isset($result['error']['code']) && $result['error']['code'] === 'AUTHENTICATION_ERROR') {
                $statusCode = 400; // Bad Request for authentication errors
            } else {
                $statusCode = 401; // Unauthorized for invalid credentials
            }
        }

        return response()->json($result, $statusCode);
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        // Safely get tenant ID from the tenant binding or request
        $tenant = app()->bound('tenant') ? app('tenant') : null;
        $tenantId = $request->tenant_id ?? ($tenant ? $tenant->id : null);
        
        $result = $this->registerAction->execute([
            'tenant_id' => $tenantId,
            'email' => $request->input('email'),
            'password' => $request->input('password'),
            'status' => 'pending',
            'profile' => [
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone' => $request->input('phone')
            ]
        ]);

        return response()->json($result, $result['status'] === 'success' ? 201 : 400);
    }

    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Successfully logged out'
            ]
        ]);
    }

    public function me(Request $request): JsonResponse
    {
        $user = $request->user()->load(['profile', 'roles']);

        return response()->json([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'email' => $user->email,
                'tenant_id' => $user->tenant_id,
                'status' => $user->status,
                'mfa_enabled' => $user->mfa_enabled,
                'profile' => [
                    'first_name' => $user->profile->first_name ?? null,
                    'last_name' => $user->profile->last_name ?? null,
                    'phone' => $user->profile->phone ?? null,
                    'avatar_url' => $user->profile->avatar_url ?? null
                ],
                'roles' => $user->roles->pluck('name')
            ]
        ]);
    }

    public function requestOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);

        $tenant = app()->bound('tenant') ? app('tenant') : null;
        $tenantId = $request->tenant_id ?? ($tenant ? $tenant->id : null);

        $result = $this->requestOtpAction->execute(
            $request->input('email'),
            $tenantId
        );

        return response()->json($result);
    }

    public function verifyOtp(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'otp' => 'required|string'
        ]);

        $tenant = app()->bound('tenant') ? app('tenant') : null;
        $tenantId = $request->tenant_id ?? ($tenant ? $tenant->id : null);

        $result = $this->verifyOtpAction->execute(
            $request->input('email'),
            $request->input('otp'),
            $tenantId
        );

        return response()->json($result);
    }
}