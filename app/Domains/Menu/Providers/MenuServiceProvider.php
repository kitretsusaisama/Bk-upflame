<?php

namespace App\Domains\Menu\Providers;

use App\Domains\Menu\Repositories\MenuRepository;
use App\Domains\Menu\Services\MenuService;
use Illuminate\Support\Facades\View;
use Illuminate\Support\ServiceProvider;

class MenuServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        $this->app->singleton(MenuRepository::class, function ($app) {
            return new MenuRepository();
        });

        $this->app->singleton(MenuService::class, function ($app) {
            return new MenuService(
                $app->make(MenuRepository::class)
            );
        });
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        // Register a view composer to load dynamic menus
        View::composer('*', function ($view) {
            // Only load menus for views that need them
            if (request()->is('*/menu*') || request()->is('*/dashboard*') || request()->is('*/admin*') || request()->is('/')) {
                try {
                    $menuService = app(MenuService::class);
                    
                    // Safely get tenant ID, handling cases where tenant binding doesn't exist
                    $tenantId = null;
                    try {
                        $tenant = app('tenant');
                        $tenantId = $tenant ? $tenant->id : null;
                    } catch (\Exception $e) {
                        // Tenant binding doesn't exist, use null for global menus
                        $tenantId = null;
                    }
                    
                    // Get menu items for the current user
                    $menuItems = $menuService->getSidebarForCurrentUser();
                    
                    // Share menu data with views
                    $view->with('menuItems', $menuItems);
                } catch (\Exception $e) {
                    // Log the error and provide empty menu items as fallback
                    \Log::error('Failed to load dynamic menus: ' . $e->getMessage());
                    $view->with('menuItems', collect());
                }
            }
        });
    }
}