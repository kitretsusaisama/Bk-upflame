<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\OtpRequest;
use App\Domain\Identity\Services\OtpService;
use Illuminate\Http\JsonResponse;

class OtpController extends Controller
{
    /**
     * Generate OTP for the user.
     *
     * @param  \App\Http\Requests\Api\Auth\OtpRequest  $request
     * @param  \App\Domain\Identity\Services\OtpService  $otpService
     * @return \Illuminate\Http\JsonResponse
     */
    public function generate(OtpRequest $request, OtpService $otpService): JsonResponse
    {
        $data = $request->validated();
        
        $result = $otpService->generateOtp($data['email']);
        
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 422);
        }
        
        return response()->json([
            'message' => 'OTP sent successfully'
        ]);
    }

    /**
     * Verify OTP for the user.
     *
     * @param  \App\Http\Requests\Api\Auth\OtpRequest  $request
     * @param  \App\Domain\Identity\Services\OtpService  $otpService
     * @return \Illuminate\Http\JsonResponse
     */
    public function verify(OtpRequest $request, OtpService $otpService): JsonResponse
    {
        $data = $request->validated();
        
        $result = $otpService->verifyOtp($data['email'], $data['otp']);
        
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 401);
        }
        
        return response()->json([
            'message' => 'OTP verified successfully',
            'user' => $result['user'],
            'token' => $result['token']
        ]);
    }
}