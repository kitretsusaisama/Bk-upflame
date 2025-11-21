<?php

namespace App\Domains\Identity\Http\Controllers;

use App\Domains\Identity\Http\Requests\LoginRequest;
use App\Domains\Identity\Http\Requests\RegisterRequest;
use App\Domains\Identity\Services\AuthenticationService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class AuthController
{
    protected $authService;

    public function __construct(AuthenticationService $authService)
    {
        $this->authService = $authService;
    }

    public function login(LoginRequest $request): JsonResponse
    {
        try {
            $user = $this->authService->authenticate(
                $request->input('email'),
                $request->input('password'),
                $request->tenant_id ?? null
            );

            if (!$user) {
                return response()->json([
                    'status' => 'error',
                    'error' => [
                        'code' => 'INVALID_CREDENTIALS',
                        'message' => 'Invalid email or password'
                    ]
                ], 401);
            }

            $token = $this->authService->createAccessToken($user);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'access_token' => $token,
                    'token_type' => 'Bearer',
                    'expires_in' => 3600,
                    'user' => [
                        'id' => $user->id,
                        'email' => $user->email,
                        'status' => $user->status,
                        'tenant_id' => $user->tenant_id
                    ]
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'AUTHENTICATION_ERROR',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }

    public function register(RegisterRequest $request): JsonResponse
    {
        try {
            // Safely get tenant ID from the tenant binding or request
            $tenant = app('tenant');
            $tenantId = $request->tenant_id ?? ($tenant ? $tenant->id : null);
            
            // If we still don't have a tenant ID, throw an exception
            if (!$tenantId) {
                return response()->json([
                    'status' => 'error',
                    'error' => [
                        'code' => 'TENANT_NOT_FOUND',
                        'message' => 'Tenant not found'
                    ]
                ], 403);
            }
            
            $user = $this->authService->createUser([
                'tenant_id' => $tenantId,
                'email' => $request->input('email'),
                'password' => $request->input('password'),
                'status' => 'pending'
            ]);

            $user->profile()->create([
                'tenant_id' => $user->tenant_id,
                'first_name' => $request->input('first_name'),
                'last_name' => $request->input('last_name'),
                'phone' => $request->input('phone')
            ]);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'status' => $user->status,
                    'created_at' => $user->created_at
                ]
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'REGISTRATION_ERROR',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
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
}