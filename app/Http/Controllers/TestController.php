<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Domains\Menu\Services\MenuService;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class TestController extends Controller
{
    public function testMenu()
    {
        $user = Auth::user();
        
        if (!$user) {
            return response()->json(['error' => 'Not authenticated'], 401);
        }
        
        $menuService = app(MenuService::class);
        try {
            $menuCollection = $menuService->getSidebarForCurrentUser();
            $menuArray = $menuCollection->toArray();
            
            return response()->json([
                'user' => $user->only(['id', 'email', 'name']),
                'roles' => $user->roles->pluck('name'),
                'permissions' => $user->getAllPermissions()->pluck('name'),
                'menu' => $menuArray,
                'menu_count' => count($menuArray)
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ], 500);
        }
    }
    
    public function testMenuPage()
    {
        return Inertia::render('TestMenu');
    }
    
    public function testLogout()
    {
        return Inertia::render('TestLogout');
    }
}