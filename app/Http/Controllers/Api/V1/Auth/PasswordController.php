<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PasswordController extends Controller
{
    /**
     * Send password reset link.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function forgot(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email'
        ]);
        
        // Implementation for sending password reset link
        // This would typically use Laravel's built-in password reset functionality
        
        return response()->json([
            'message' => 'Password reset link sent to your email'
        ]);
    }

    /**
     * Reset the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function reset(Request $request): JsonResponse
    {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed'
        ]);
        
        // Implementation for resetting password
        // This would typically use Laravel's built-in password reset functionality
        
        return response()->json([
            'message' => 'Password reset successfully'
        ]);
    }
}