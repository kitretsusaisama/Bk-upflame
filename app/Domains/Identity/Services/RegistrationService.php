<?php

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Repositories\UserRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class RegistrationService
{
    protected $userRepository;

    public function __construct(UserRepository $userRepository)
    {
        $this->userRepository = $userRepository;
    }

    public function register(array $data): array
    {
        try {
            // Check if user already exists
            if ($this->userRepository->findByEmail($data['email'])) {
                return [
                    'success' => false,
                    'message' => 'User with this email already exists'
                ];
            }

            // Hash the password
            $data['password'] = Hash::make($data['password']);
            
            // Set default status
            $data['status'] = 'active';

            // Create the user
            $user = $this->userRepository->create($data);

            return [
                'success' => true,
                'message' => 'User registered successfully',
                'user' => $user
            ];
        } catch (\Exception $e) {
            return [
                'success' => false,
                'message' => 'Registration failed: ' . $e->getMessage()
            ];
        }
    }
}