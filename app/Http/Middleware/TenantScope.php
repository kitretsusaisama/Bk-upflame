<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class TenantScope
{
    public function handle(Request $request, Closure $next)
    {
        if (auth()->check()) {
            $user = auth()->user();
            $tenant = app()->bound('tenant') ? app('tenant') : null;
            
            // If there's no tenant binding, allow the request to proceed
            // This might be the case for super admins or other special cases
            if ($tenant && $user->tenant_id !== $tenant->id) {
                return response()->json([
                    'status' => 'error',
                    'error' => [
                        'code' => 'UNAUTHORIZED_TENANT',
                        'message' => 'User does not belong to this tenant'
                    ]
                ], 403);
            }
        }
        
        return $next($request);
    }
}