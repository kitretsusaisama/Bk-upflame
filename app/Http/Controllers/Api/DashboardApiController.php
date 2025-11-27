<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\DashboardService;
use App\Services\SidebarService;
use Illuminate\Http\Request;

class DashboardApiController extends Controller
{
    protected DashboardService $dashboardService;
    protected SidebarService $sidebarService;

    public function __construct(
        DashboardService $dashboardService,
        SidebarService $sidebarService
    ) {
        $this->dashboardService = $dashboardService;
        $this->sidebarService = $sidebarService;
    }

    /**
     * Get dashboard data (for SPA/mobile)
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(Request $request)
    {
        $user = $request->user();
        
        $dashboardData = $this->dashboardService->getDashboardData($user);
        $menuItems = $this->sidebarService->buildForUser($user);
        
        return response()->json([
            'success' => true,
            'data' => array_merge($dashboardData, [
                'menu' => $menuItems,
            ]),
        ]);
    }

    /**
     * Get widgets data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function widgets(Request $request)
    {
        $user = $request->user();
        $widgets = $this->dashboardService->getWidgetsForUser($user);

        return response()->json([
            'success' => true,
            'data' => $widgets,
        ]);
    }

    /**
     * Get menu data
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function menu(Request $request)
    {
        $user = $request->user();
        $menuItems = $this->sidebarService->buildForUser($user);

        return response()->json([
            'user' => [
                'id' => $user->id,
                'name' => $user->name,
                'primary_role' => $user->roles->first()?->name,
            ],
            'tenant' => $user->tenant ? [
                'id' => $user->tenant->id,
                'name' => $user->tenant->name,
            ] : null,
            'sidebar' => $menuItems,
        ]);
    }

    /**
     * Get user stats
     *
     * @param Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function stats(Request $request)
    {
        $user = $request->user();
        $stats = $this->dashboardService->getStatsForUser($user);

        return response()->json([
            'success' => true,
            'data' => $stats,
        ]);
    }
}
