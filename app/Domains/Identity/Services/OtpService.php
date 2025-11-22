<?php

namespace App\Domains\Identity\Services;

use App\Domains\Identity\Models\OtpRequest;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class OtpService
{
    protected $ttl;
    protected $maxAttempts;

    public function __construct()
    {
        $this->ttl = config('auth.otp.ttl', 300); // 5 minutes default
        $this->maxAttempts = config('auth.otp.max_attempts', 3);
    }

    /**
     * Generate a secure OTP
     *
     * @param string $recipient
     * @param string|null $tenantId
     * @return string
     */
    public function generateOtp(string $recipient, ?string $tenantId = null): string
    {
        // Generate a 6-digit numeric OTP
        $otp = str_pad(random_int(0, 999999), 6, '0', STR_PAD_LEFT);
        
        // Hash the OTP for secure storage
        $hashedOtp = Hash::make($otp);
        
        // Store the OTP request
        $data = [
            'recipient' => $recipient,
            'otp_hash' => $hashedOtp,
            'attempts' => 0,
            'used' => false,
            'expires_at' => now()->addSeconds($this->ttl),
            'ip' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ];
        
        // Only add tenant_id if it's not null
        if ($tenantId !== null) {
            $data['tenant_id'] = $tenantId;
        }
        
        OtpRequest::create($data);
        
        return $otp;
    }

    /**
     * Validate an OTP
     *
     * @param string $recipient
     * @param string $otp
     * @param string|null $tenantId
     * @return bool
     */
    public function validateOtp(string $recipient, string $otp, ?string $tenantId = null): bool
    {
        // Find the latest unused OTP for this recipient
        $query = OtpRequest::where('recipient', $recipient)
            ->where('used', false)
            ->where('expires_at', '>', now())
            ->orderBy('created_at', 'desc');
            
        // Add tenant_id condition
        if ($tenantId !== null) {
            $query->where('tenant_id', $tenantId);
        } else {
            $query->whereNull('tenant_id');
        }
        
        $otpRequest = $query->first();

        if (!$otpRequest) {
            return false;
        }

        // Check if max attempts exceeded
        if ($otpRequest->attempts >= $this->maxAttempts) {
            return false;
        }

        // Increment attempts
        $otpRequest->increment('attempts');

        // Verify the OTP
        if (!Hash::check($otp, $otpRequest->otp_hash)) {
            return false;
        }

        // Mark as used
        $otpRequest->update(['used' => true]);

        return true;
    }

    /**
     * Clean expired OTPs
     *
     * @return int Number of deleted records
     */
    public function cleanExpiredOtps(): int
    {
        return OtpRequest::where('expires_at', '<', now())->delete();
    }
}