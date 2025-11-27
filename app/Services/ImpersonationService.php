<?php

namespace App\Services;

use App\Domains\Access\Models\ImpersonationLog;
use App\Domains\Identity\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class ImpersonationService
{
    const SESSION_KEY = 'impersonation_original_user_id';
    const LOG_ID_KEY = 'impersonation_log_id';

    /**
     * Start impersonation
     */
    public function start(User $impersonator, User $target): void
    {
        // 1. Security Checks
        if ($impersonator->id === $target->id) {
            throw new \Exception("Cannot impersonate yourself.");
        }

        if ($target->isSuperAdmin() && !$impersonator->isSuperAdmin()) {
             throw new \Exception("Cannot impersonate a Super Admin.");
        }

        // 2. Log the start
        $log = ImpersonationLog::create([
            'impersonator_id' => $impersonator->id,
            'impersonated_id' => $target->id,
            'tenant_id' => $target->tenant_id, // Context of the target
            'started_at' => now(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // 3. Store state in session
        Session::put(self::SESSION_KEY, $impersonator->id);
        Session::put(self::LOG_ID_KEY, $log->id);

        // 4. Login as target
        Auth::login($target);
    }

    /**
     * Stop impersonation
     */
    public function stop(): void
    {
        if (!$this->isImpersonating()) {
            return;
        }

        $originalUserId = Session::get(self::SESSION_KEY);
        $logId = Session::get(self::LOG_ID_KEY);

        // 1. Log the end
        if ($logId) {
            $log = ImpersonationLog::find($logId);
            if ($log) {
                $log->update(['ended_at' => now()]);
            }
        }

        // 2. Clear session
        Session::forget(self::SESSION_KEY);
        Session::forget(self::LOG_ID_KEY);

        // 3. Login back as original user
        $originalUser = User::find($originalUserId);
        if ($originalUser) {
            Auth::login($originalUser);
        } else {
            Auth::logout(); // Fallback if original user deleted
        }
    }

    /**
     * Check if currently impersonating
     */
    public function isImpersonating(): bool
    {
        return Session::has(self::SESSION_KEY);
    }

    /**
     * Get the original impersonator
     */
    public function getImpersonator(): ?User
    {
        if (!$this->isImpersonating()) {
            return null;
        }

        return User::find(Session::get(self::SESSION_KEY));
    }
}
