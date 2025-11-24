<?php

namespace App\Http\Controllers\Api\V1\Menu;

use App\Http\Controllers\Controller;
use App\Domains\Menu\Models\Menu;
use App\Domains\Menu\Services\MenuService;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class MenuController extends Controller
{
    protected MenuService $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Get the current user's menu items.
     */
    public function index(): JsonResponse
    {
        $menuItems = $this->menuService->getSidebarForCurrentUser();
        
        return response()->json([
            'menus' => $menuItems
        ]);
    }

    /**
     * Get all menu items for the current tenant.
     */
    public function all(): JsonResponse
    {
        // Safely get tenant ID, handling cases where tenant binding doesn't exist
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : null;
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, use null for global menus
            $tenantId = null;
        }
        
        $menuItems = $this->menuService->getAllForTenant($tenantId);
        
        return response()->json([
            'menus' => $menuItems
        ]);
    }

    /**
     * Store a newly created menu item.
     */
    public function store(Request $request): JsonResponse
    {
        $data = $request->validate([
            'key' => 'required|string|unique:menus',
            'label' => 'required|string',
            'icon' => 'nullable|string',
            'route' => 'nullable|string',
            'url' => 'nullable|string',
            'permission' => 'nullable|string',
            'order' => 'integer',
            'parent_id' => 'nullable|exists:menus,id',
            'type' => 'required|in:link,heading,separator,module',
            'is_enabled' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        // Safely get tenant ID, handling cases where tenant binding doesn't exist
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : null;
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, use null for global menus
            $tenantId = null;
        }

        $data['tenant_id'] = $tenantId;

        $menu = $this->menuService->createMenuItem($data);

        return response()->json([
            'message' => 'Menu item created successfully',
            'menu' => $menu
        ], 201);
    }

    /**
     * Display the specified menu item.
     */
    public function show(Menu $menu): JsonResponse
    {
        return response()->json([
            'menu' => $menu
        ]);
    }

    /**
     * Update the specified menu item.
     */
    public function update(Request $request, Menu $menu): JsonResponse
    {
        $data = $request->validate([
            'key' => 'string|unique:menus,key,' . $menu->id,
            'label' => 'string',
            'icon' => 'nullable|string',
            'route' => 'nullable|string',
            'url' => 'nullable|string',
            'permission' => 'nullable|string',
            'order' => 'integer',
            'parent_id' => 'nullable|exists:menus,id',
            'type' => 'in:link,heading,separator,module',
            'is_enabled' => 'boolean',
            'metadata' => 'nullable|array',
        ]);

        $updatedMenu = $this->menuService->updateMenuItem($menu, $data);

        return response()->json([
            'message' => 'Menu item updated successfully',
            'menu' => $updatedMenu
        ]);
    }

    /**
     * Remove the specified menu item.
     */
    public function destroy(Menu $menu): JsonResponse
    {
        $this->menuService->deleteMenuItem($menu);

        return response()->json([
            'message' => 'Menu item deleted successfully'
        ]);
    }
    
    /**
     * Clear menu cache for the current tenant
     */
    public function clearCache(): JsonResponse
    {
        // Safely get tenant ID, handling cases where tenant binding doesn't exist
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : null;
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, use null for global menus
            $tenantId = null;
        }
        
        $this->menuService->clearTenantCache($tenantId);

        return response()->json([
            'message' => 'Menu cache cleared successfully'
        ]);
    }
}