<?php

namespace App\Providers;

use Illuminate\Support\Facades\View;
use Illuminate\Routing\Route;
use Illuminate\Support\ServiceProvider;
use App\Domains\Menu\Services\MenuService;

class MenuServiceProvider extends ServiceProvider
{
  /**
   * Register services.
   */
  public function register(): void
  {
    //
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
          $tenant = app('tenant');
          $tenantId = $tenant ? $tenant->id : null;
          
          // Get all menus for the tenant (without permission filtering for admin views)
          $allMenus = $menuService->getAllForTenant($tenantId);
          
          // Structure menus into the expected format
          $verticalMenuData = $this->structureMenus($allMenus);
          $horizontalMenuData = $this->structureMenus($allMenus);
          
          // Share menu data with views
          $view->with('menuData', [$verticalMenuData, $horizontalMenuData]);
        } catch (\Exception $e) {
          // Fallback to static menus if dynamic loading fails
          $verticalMenuJson = file_get_contents(base_path('resources/menu/verticalMenu.json'));
          $verticalMenuData = json_decode($verticalMenuJson);
          $horizontalMenuJson = file_get_contents(base_path('resources/menu/horizontalMenu.json'));
          $horizontalMenuData = json_decode($horizontalMenuJson);
          
          $view->with('menuData', [$verticalMenuData, $horizontalMenuData]);
        }
      }
    });
  }
  
  /**
   * Structure menus into the expected JSON format
   */
  private function structureMenus($menus)
  {
    // Convert database menus to the expected JSON structure
    $menuItems = [];
    
    // Get root level menus (no parent)
    $rootMenus = $menus->filter(function ($menu) {
      return !$menu->parent_id;
    })->sortBy('order');
    
    foreach ($rootMenus as $menu) {
      $menuItems[] = $this->buildMenuItem($menu, $menus);
    }
    
    return (object) ['menu' => $menuItems];
  }
  
  /**
   * Recursively build menu items with children
   */
  private function buildMenuItem($menu, $allMenus)
  {
    $item = [
      'name' => $menu->label,
      'slug' => $menu->key,
    ];
    
    if ($menu->icon) {
      $item['icon'] = $menu->icon;
    }
    
    if ($menu->route) {
      $item['url'] = route($menu->route);
    } elseif ($menu->url) {
      $item['url'] = $menu->url;
    }
    
    if ($menu->type === 'heading') {
      $item['menuHeader'] = $menu->label;
      unset($item['name']);
      unset($item['slug']);
    }
    
    // Get children
    $children = $allMenus->filter(function ($child) use ($menu) {
      return $child->parent_id === $menu->id;
    })->sortBy('order');
    
    if ($children->count() > 0) {
      $submenu = [];
      foreach ($children as $child) {
        $submenu[] = $this->buildMenuItem($child, $allMenus);
      }
      $item['submenu'] = $submenu;
    }
    
    return $item;
  }
}