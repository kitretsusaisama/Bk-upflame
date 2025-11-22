<?php

namespace App\Http\Controllers\Api\V1\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Api\Auth\RegisterRequest;
use App\Domain\Identity\Services\RegistrationService;
use Illuminate\Http\JsonResponse;

class RegisterController extends Controller
{
    /**
     * Handle the incoming request.
     *
     * @param  \App\Http\Requests\Api\Auth\RegisterRequest  $request
     * @param  \App\Domain\Identity\Services\RegistrationService  $registrationService
     * @return \Illuminate\Http\JsonResponse
     */
    public function __invoke(RegisterRequest $request, RegistrationService $registrationService): JsonResponse
    {
        $data = $request->validated();
        
        $result = $registrationService->register($data);
        
        if (!$result['success']) {
            return response()->json([
                'message' => $result['message']
            ], 422);
        }
        
        return response()->json([
            'message' => 'Registration successful',
            'user' => $result['user']
        ], 201);
    }
}