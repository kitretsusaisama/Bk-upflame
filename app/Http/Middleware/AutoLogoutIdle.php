<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class AutoLogoutIdle
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $idleTimeout = config('dashboard.idle_timeout', 30); // minutes
            
            if ($idleTimeout > 0) {
                $lastActivity = session('last_activity_time');
                $currentTime = time();

                if ($lastActivity) {
                    $idleTime = $currentTime - $lastActivity;
                    $maxIdleTime = $idleTimeout * 60; // Convert to seconds

                    if ($idleTime > $maxIdleTime) {
                        // User has been idle too long, logout
                        Auth::logout();
                        $request->session()->invalidate();
                        $request->session()->regenerateToken();

                        if ($request->expectsJson()) {
                            return response()->json([
                                'message' => 'Session expired due to inactivity.',
                                'error' => 'idle_timeout',
                            ], 401);
                        }

                        return redirect()->route('login')
                            ->with('warning', 'You have been logged out due to inactivity.');
                    }
                }

                // Update last activity time
                session(['last_activity_time' => $currentTime]);
            }
        }

        return $next($request);
    }
}
