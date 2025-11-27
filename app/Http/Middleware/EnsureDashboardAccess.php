<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class EnsureDashboardAccess
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        // Ensure user is active
        if (!$user->isActive()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is not active.');
        }

        // Ensure user is not locked
        if ($user->isLocked()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'Your account is temporarily locked.');
        }

        // Load roles and permissions if not already loaded
        if (!$user->relationLoaded('roles')) {
            $user->load('roles.permissions');
        }

        // Ensure user has at least one role
        if ($user->roles->isEmpty()) {
            auth()->logout();
            return redirect()->route('login')->with('error', 'No roles assigned to your account.');
        }

        return $next($request);
    }
}
