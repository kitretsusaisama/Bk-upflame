<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Log;
use App\Services\SidebarService;
use Symfony\Component\HttpFoundation\Response;

class ShareSidebarData
{
    private static array $requestCache = [];
    private SidebarService $sidebarService;

    public function __construct(SidebarService $sidebarService)
    {
        $this->sidebarService = $sidebarService;
    }

    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();
        
        // Share menu data with all views
        View::composer(
            config('sidebar.composer.target_views', ['dashboard.layout', 'dashboard.partials.sidebar']),
            function ($view) use ($user) {
                $variableName = config('sidebar.composer.variable_name', 'menuItems');
                $view->with($variableName, $this->getMenuItems($user));
            }
        );

        return $next($request);
    }

    /**
     * Get menu items with multi-layer caching and error handling
     */
    private function getMenuItems($user): array
    {
        // Security check
        if (!$user && config('sidebar.security.require_auth', true)) {
            return [];
        }

        // Check user status
        if ($user && config('sidebar.security.check_user_status', true)) {
            if (method_exists($user, 'isActive') && !$user->isActive()) {
                Log::warning('Inactive user attempted to access sidebar', [
                    'user_id' => $user->id,
                ]);
                return [];
            }
        }

        // Request-level memoization (prevent duplicate calls in same request)
        if (config('sidebar.performance.request_cache', true)) {
            $userId = $user->id ?? 'guest';
            if (isset(self::$requestCache[$userId])) {
                return self::$requestCache[$userId];
            }
        }

        try {
            $startTime = microtime(true);
            
            // Get menu items from service (with caching)
            $menuItems = $this->sidebarService->buildForUser($user);
            
            // Performance monitoring
            $this->monitorPerformance($startTime, $user);
            
            // Store in request cache
            if (config('sidebar.performance.request_cache', true)) {
                self::$requestCache[$user->id ?? 'guest'] = $menuItems;
            }
            
            return $menuItems;
            
        } catch (\Exception $e) {
            return $this->handleError($e, $user);
        }
    }

    /**
     * Handle errors with graceful degradation
     */
    private function handleError(\Exception $e, $user): array
    {
        if (config('sidebar.error.logging_enabled', true)) {
            Log::error('Sidebar menu generation failed', [
                'user_id' => $user->id ?? null,
                'error' => $e->getMessage(),
                'trace' => config('app.debug') ? $e->getTraceAsString() : null,
            ]);
        }

        // Graceful degradation: return fallback or empty array
        if (config('sidebar.error.graceful_degradation', true)) {
            return config('sidebar.error.fallback_items', []);
        }

        // In strict mode, re-throw exception
        throw $e;
    }

    /**
     * Monitor performance and log slow queries
     */
    private function monitorPerformance(float $startTime, $user): void
    {
        if (!config('sidebar.monitoring.enabled', false)) {
            return;
        }

        $executionTime = (microtime(true) - $startTime) * 1000;
        $threshold = config('sidebar.monitoring.slow_threshold_ms', 100);

        if ($executionTime > $threshold) {
            Log::warning('Slow sidebar generation detected', [
                'user_id' => $user->id ?? null,
                'execution_time_ms' => round($executionTime, 2),
                'threshold_ms' => $threshold,
            ]);
        }
    }

    /**
     * Clear request cache (useful for testing)
     */
    public static function clearRequestCache(): void
    {
        self::$requestCache = [];
    }
}
