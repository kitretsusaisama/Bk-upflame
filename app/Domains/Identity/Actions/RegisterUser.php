<?php

namespace App\Domains\Identity\Actions;

use App\Domains\Identity\Services\AuthenticationService;
use App\Domains\Identity\Contracts\UserRepositoryInterface;
use App\Domains\Identity\Models\User;

class RegisterUser
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
     * Execute the action to register a new user
     *
     * @param array $data
     * @return array
     */
    public function execute(array $data): array
    {
        try {
            // Validate required fields
            if (!isset($data['email']) || !isset($data['password']) || !isset($data['tenant_id'])) {
                return [
                    'status' => 'error',
                    'error' => [
                        'code' => 'MISSING_FIELDS',
                        'message' => 'Email, password, and tenant_id are required'
                    ]
                ];
            }

            // Check if user already exists
            $existingUser = $this->userRepository->findByEmail($data['email'], $data['tenant_id']);
            
            if ($existingUser) {
                return [
                    'status' => 'error',
                    'error' => [
                        'code' => 'USER_EXISTS',
                        'message' => 'A user with this email already exists'
                    ]
                ];
            }

            // Create the user
            $user = $this->authService->createUser([
                'tenant_id' => $data['tenant_id'],
                'email' => $data['email'],
                'password' => $data['password'],
                'status' => $data['status'] ?? 'pending'
            ]);

            // Create user profile if provided
            if (isset($data['profile'])) {
                $user->profile()->create(array_merge(
                    ['tenant_id' => $user->tenant_id],
                    $data['profile']
                ));
            }

            return [
                'status' => 'success',
                'data' => [
                    'id' => $user->id,
                    'email' => $user->email,
                    'status' => $user->status,
                    'created_at' => $user->created_at
                ]
            ];
        } catch (\Exception $e) {
            return [
                'status' => 'error',
                'error' => [
                    'code' => 'REGISTRATION_ERROR',
                    'message' => $e->getMessage()
                ]
            ];
        }
    }
}