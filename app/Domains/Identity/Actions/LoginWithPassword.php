<?php

namespace App\Domains\Identity\Actions;

use App\Domains\Identity\Services\AuthenticationService;
use App\Domains\Identity\Contracts\UserRepositoryInterface;
use App\Domains\Identity\Models\User;

class LoginWithPassword
{
    protected $authService;
    protected $userRepository;

    public function __construct(
        AuthenticationService $authService,
        UserRepositoryInterface $userRepository
    ) {
        $this->authService = $authService;
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the action to login with email and password
     *
     * @param string $email
     * @param string $password
     * @param string|null $tenantId
     * @return array
     */
    public function execute(string $email, string $password, ?string $tenantId = null): array
    {
        try {
            $user = $this->authService->authenticate(
                $email,
                $password,
                $tenantId
            );

            if (!$user) {
                return [
                    'status' => 'error',
                    'error' => [
                        'code' => 'INVALID_CREDENTIALS',
                        'message' => 'Invalid email or password'
                    ]
                ];
            }

            $token = $this->authService->createAccessToken($user);

            return [
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
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => [
                    'code' => 'AUTHENTICATION_ERROR',
                    'message' => $e->getMessage()
                ]
            ];
        }
    }
}