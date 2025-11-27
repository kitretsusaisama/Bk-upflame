<?php

namespace App\Http\Controllers;

use App\Services\DashboardService;
use App\Services\SidebarService;
use App\Services\WidgetResolver;
use Illuminate\Http\Request;

class UnifiedDashboardController extends Controller
{
    protected DashboardService $dashboardService;
    protected SidebarService $sidebarService;
    protected WidgetResolver $widgetResolver;

    public function __construct(
        DashboardService $dashboardService,
        SidebarService $sidebarService,
        WidgetResolver $widgetResolver
    ) {
        $this->dashboardService = $dashboardService;
        $this->sidebarService = $sidebarService;
        $this->widgetResolver = $widgetResolver;
    }

    /**
     * Display the unified dashboard
     *
     * @param Request $request
     * @return \Illuminate\View\View|\Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        try {
            // Get dashboard data
            $dashboardData = $this->dashboardService->getDashboardData($user);
            $menuItems = $this->sidebarService->buildForUser($user);
            $widgets = $this->widgetResolver->resolveForUser($user);
            
            // Return JSON for API requests
            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => true,
                    'data' => array_merge($dashboardData, [
                        'menu' => $menuItems,
                        'widgets' => $widgets,
                    ]),
                ]);
            }

            // Return HTML view
            return view('dashboard.index', [
                'user' => $user,
                'role' => $dashboardData['role_context']['name'] ?? 'User',
                'stats' => $dashboardData['stats'],
                'widgets' => $widgets,
                'menuItems' => $menuItems,
            ]);
        } catch (\Exception $e) {
            \Illuminate\Support\Facades\Log::error('Dashboard Error: ' . $e->getMessage(), [
                'user_id' => $user->id,
                'trace' => $e->getTraceAsString()
            ]);

            if ($request->expectsJson() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'Failed to load dashboard data.',
                    'error' => config('app.debug') ? $e->getMessage() : null
                ], 500);
            }

            // Fallback view with minimal data
            return view('dashboard.index', [
                'user' => $user,
                'role' => 'User',
                'stats' => [],
                'widgets' => [],
                'menuItems' => [],
                'error' => 'Failed to load dashboard data. Please try again later.'
            ]);
        }
    }
}
