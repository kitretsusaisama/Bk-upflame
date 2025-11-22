<?php

namespace App\Http\Middleware;

use App\Domains\Access\Services\AccessEvaluationService;
use Closure;
use Illuminate\Http\Request;

class EnsurePermission
{
    protected $accessEvaluationService;

    public function __construct(AccessEvaluationService $accessEvaluationService)
    {
        $this->accessEvaluationService = $accessEvaluationService;
    }

    public function handle(Request $request, Closure $next, string $permission)
    {
        // Check if user is authenticated
        if (!$request->user()) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'UNAUTHORIZED',
                    'message' => 'Authentication required'
                ]
            ], 401);
        }

        // Check if user has the required permission
        if (!$this->accessEvaluationService->can($request->user(), $permission)) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'FORBIDDEN',
                    'message' => 'Insufficient permissions'
                ]
            ], 403);
        }

        return $next($request);
    }
}