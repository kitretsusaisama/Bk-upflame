<?php

namespace App\Services;

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\UserSession;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SessionManager
{
    /**
     * Create a new session record for the user
     *
     * @param User $user
     * @param Request $request
     * @param string|null $sessionId
     * @return UserSession
     */
    public function createSession(User $user, Request $request, ?string $sessionId = null): UserSession
    {
        // Get device info
        $userAgent = $request->userAgent();
        $ipAddress = $request->ip();
        
        return UserSession::create([
            'id' => Str::uuid()->toString(),
            'user_id' => $user->id,
            'tenant_id' => $user->tenant_id,
            'session_id' => $sessionId ?? session()->getId(),
            'device_id' => $this->generateDeviceId($request),
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'last_activity' => now(),
            'expires_at' => now()->addMinutes(config('session.lifetime', 120)),
        ]);
    }

    /**
     * Update session activity timestamp
     *
     * @param string $sessionId
     * @return bool
     */
    public function updateActivity(string $sessionId): bool
    {
        return UserSession::where('session_id', $sessionId)
            ->update([
                'last_activity' => now(),
                'expires_at' => now()->addMinutes(config('session.lifetime', 120)),
            ]);
    }

    /**
     * Terminate a specific session
     *
     * @param string $sessionId
     * @return bool
     */
    public function terminateSession(string $sessionId): bool
    {
        return UserSession::where('session_id', $sessionId)->delete();
    }

    /**
     * Terminate all sessions for a user
     * Optionally keep the current session
     *
     * @param User $user
     * @param string|null $exceptSessionId
     * @return int
     */
    public function terminateAllSessions(User $user, ?string $exceptSessionId = null): int
    {
        return $user->terminateAllSessions($exceptSessionId);
    }

    /**
     * Get all active sessions for a user
     *
     * @param User $user
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getActiveSessions(User $user)
    {
        return $user->getActiveSessions();
    }

    /**
     * Check if user has reached session limit
     *
     * @param User $user
     * @return bool
     */
    public function hasReachedSessionLimit(User $user): bool
    {
        $limit = config('dashboard.session_limit', 0);
        
        if ($limit === 0) {
            return false; // Unlimited sessions
        }

        $activeCount = $user->getActiveSessions()->count();
        
        return $activeCount >= $limit;
    }

    /**
     * Enforce session limit by removing oldest sessions
     *
     * @param User $user
     * @param string|null $exceptSessionId
     * @return void
     */
    public function enforceSessionLimit(User $user, ?string $exceptSessionId = null): void
    {
        $limit = config('dashboard.session_limit', 0);
        
        if ($limit === 0) {
            return; // Unlimited sessions
        }

        $sessions = $user->getActiveSessions();
        
        if ($sessions->count() >= $limit) {
            // Remove oldest sessions to make room
            $toRemove = $sessions->count() - $limit + 1;
            
            $sessionsToDelete = $sessions
                ->sortBy('last_activity')
                ->take($toRemove)
                ->pluck('session_id')
                ->toArray();

            UserSession::whereIn('session_id', $sessionsToDelete)->delete();
        }
    }

    /**
     * Clean up expired sessions
     *
     * @return int Number of sessions deleted
     */
    public function cleanupExpiredSessions(): int
    {
        return UserSession::where('expires_at', '<', now())->delete();
    }

    /**
     * Generate a device ID from request
     *
     * @param Request $request
     * @return string
     */
    protected function generateDeviceId(Request $request): string
    {
        $components = [
            $request->userAgent(),
            $request->ip(),
        ];

        return hash('sha256', implode('|', $components));
    }
}
