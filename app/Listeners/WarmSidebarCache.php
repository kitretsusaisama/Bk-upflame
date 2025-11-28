<?php

namespace App\Listeners;

use App\Services\SidebarService;
use Illuminate\Auth\Events\Login;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Facades\Log;

class WarmSidebarCache implements ShouldQueue
{
    private SidebarService $sidebarService;

    public function __construct(SidebarService $sidebarService)
    {
        $this->sidebarService = $sidebarService;
    }

    /**
     * Handle the login event - warm cache in background
     */
    public function handle(Login $event): void
    {
        try {
            $user = $event->user;
            
            // Warm cache in background (non-blocking)
            $this->sidebarService->buildForUser($user);
            
            Log::info('Sidebar cache warmed for user', [
                'user_id' => $user->id,
                'warmed_at' => now(),
            ]);
        } catch (\Exception $e) {
            // Don't block login on cache warming failure
            Log::warning('Failed to warm sidebar cache on login', [
                'user_id' => $event->user->id ?? null,
                'error' => $e->getMessage(),
            ]);
        }
    }
}
