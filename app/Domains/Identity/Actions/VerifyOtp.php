<?php

namespace App\Domains\Identity\Actions;

use App\Domains\Identity\Services\OtpService;
use App\Domains\Identity\Contracts\UserRepositoryInterface;
use App\Domains\Identity\Services\AuthenticationService;
use App\Domains\Identity\Models\User;

class VerifyOtp
{
    protected $otpService;
    protected $userRepository;
    protected $authService;

    public function __construct(
        OtpService $otpService, 
        UserRepositoryInterface $userRepository,
        AuthenticationService $authService
    ) {
        $this->otpService = $otpService;
        $this->userRepository = $userRepository;
        $this->authService = $authService;
    }

    /**
     * Execute the action to verify an OTP
     *
     * @param string $email
     * @param string $otp
     * @param string|null $tenantId
     * @return array
     */
    public function execute(string $email, string $otp, ?string $tenantId = null): array
    {
        // Normalize the email
        $email = strtolower(trim($email));
        
        // Validate the OTP
        $isValid = $this->otpService->validateOtp($email, $otp, $tenantId);
        
        if (!$isValid) {
            return [
                'status' => 'error',
                'message' => 'Invalid or expired OTP'
            ];
        }
        
        // Find or create user
        $user = $this->userRepository->findByEmail($email, $tenantId);
        
        if (!$user) {
            // Create a new user if they don't exist
            $user = $this->authService->createUser([
                'tenant_id' => $tenantId,
                'email' => $email,
                'password' => '', // Will be set later during registration completion
                'status' => 'active'
            ]);
        }
        
        // Issue token/session
        $token = $this->authService->createAccessToken($user);
        
        return [
            'status' => 'success',
            'message' => 'OTP verified successfully',
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
    }
}