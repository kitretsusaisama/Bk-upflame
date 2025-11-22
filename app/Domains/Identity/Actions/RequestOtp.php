<?php

namespace App\Domains\Identity\Actions;

use App\Domains\Identity\Services\OtpService;
use App\Domains\Identity\Contracts\UserRepositoryInterface;

class RequestOtp
{
    protected $otpService;
    protected $userRepository;

    public function __construct(OtpService $otpService, UserRepositoryInterface $userRepository)
    {
        $this->otpService = $otpService;
        $this->userRepository = $userRepository;
    }

    /**
     * Execute the action to request an OTP
     *
     * @param string $email
     * @param string|null $tenantId
     * @return array
     */
    public function execute(string $email, ?string $tenantId = null): array
    {
        // Normalize the email
        $email = strtolower(trim($email));
        
        // Check if user exists
        $user = $this->userRepository->findByEmail($email, $tenantId);
        
        // Even if user doesn't exist, we still generate OTP to prevent user enumeration
        $otp = $this->otpService->generateOtp($email, $tenantId);
        
        // In a real implementation, you would dispatch a job to send the OTP via email/SMS
        // For now, we'll just return success
        
        return [
            'status' => 'success',
            'message' => 'OTP sent successfully',
            'user_exists' => $user !== null
        ];
    }
}