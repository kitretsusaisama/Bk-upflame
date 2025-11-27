<?php

namespace App\Http\Middleware;

use App\Services\SessionManager;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SessionSecurity
{
    protected SessionManager $sessionManager;

    public function __construct(SessionManager $sessionManager)
    {
        $this->sessionManager = $sessionManager;
    }

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if ($user) {
            $sessionId = session()->getId();
            
            // Update last activity for this session
            $this->sessionManager->updateActivity($sessionId);

            // Periodically regenerate session to prevent session fixation
            if ($this->shouldRegenerateSession()) {
                $request->session()->regenerate();
            }
        }

        return $next($request);
    }

    /**
     * Determine if session should be regenerated
     * Regenerate every 15 minutes to prevent session fixation
     *
     * @return bool
     */
    protected function shouldRegenerateSession(): bool
    {
        $lastRegeneration = session('last_session_regeneration', 0);
        $regenerationInterval = 900; // 15 minutes in seconds

        if (time() - $lastRegeneration > $regenerationInterval) {
            session(['last_session_regeneration' => time()]);
            return true;
        }

        return false;
    }
}
