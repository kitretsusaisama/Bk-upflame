<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\LoginRequest;
use App\Domain\Identity\Services\AuthenticationService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Api\Auth\LoginRequest  $request
     * @param  \App\Domain\Identity\Services\AuthenticationService  $authService
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(LoginRequest $request, AuthenticationService $authService): JsonResponse
    {
        $credentials = $request->validated();
        
        $result = $authService->login($credentials);
        
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 401);
        }
        
        return response()->json([
            'message' => 'Login successful',
            'user' => $result['user'],
            'token' => $result['token']
        ]);
    }
}