<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get a provider user
$user = App\Domains\Identity\Models\User::where('email', 'provider@example.com')->first();
$user->load('roles');

// Get tenant ID
$tenantId = '019ab4c5-0f16-73ff-b715-3b3f9b079cdd';

// Get all menus for this tenant
$allMenus = App\Domains\Menu\Models\Menu::where(function ($q) use ($tenantId) {
    $q->where('tenant_id', $tenantId)
      ->orWhereNull('tenant_id');
})
->enabled()
->orderBy('order')
->get();

echo "All menus for tenant: " . $allMenus->count() . "\n";

// Filter menus by user permissions (same logic as in MenuRepository)
$filteredMenus = $allMenus->filter(function($menu) use ($user) {
    // If no permission is required, show the menu item
    if (!$menu->permission) {
        return true;
    }
    
    // Check if user has the required permission
    $can = $user->can($menu->permission);
    return $can;
})->values();

echo "Filtered menus: " . $filteredMenus->count() . "\n";

// Test the buildMenuHierarchy method directly
$menuRepository = app(App\Domains\Menu\Repositories\MenuRepository::class);

// Get root level menus (no parent)
$rootMenus = $filteredMenus->filter(function ($menu) {
    return !$menu->parent_id;
})->sortBy('order');

echo "Root menus count: " . $rootMenus->count() . "\n";
echo "Root menus:\n";
foreach ($rootMenus as $menu) {
    echo "  - " . $menu->label . " (key: " . $menu->key . ", permission: " . ($menu->permission ?? 'null') . ")\n";
}

// Add children to each menu item
$rootMenus->each(function ($menu) use ($filteredMenus) {
    $menu->children = $filteredMenus->filter(function ($child) use ($menu) {
        return $child->parent_id === $menu->id;
    })->sortBy('order');
    
    echo "Menu '{$menu->label}' has " . $menu->children->count() . " children\n";
    
    // Add grandchildren recursively
    $menu->children->each(function ($child) use ($filteredMenus) {
        $child->children = $filteredMenus->filter(function ($grandchild) use ($child) {
            return $grandchild->parent_id === $child->id;
        })->sortBy('order');
        
        if ($child->children->count() > 0) {
            echo "  Child '{$child->label}' has " . $child->children->count() . " grandchildren\n";
        }
    });
});

echo "\nFinal hierarchical structure:\n";
echo "Number of root menus: " . $rootMenus->count() . "\n";

foreach ($rootMenus as $menu) {
    echo "Menu: " . $menu->label . " (key: " . $menu->key . ", permission: " . ($menu->permission ?? 'null') . ")\n";
    
    if ($menu->children) {
        foreach ($menu->children as $child) {
            echo "  - Child: " . $child->label . " (key: " . $child->key . ", permission: " . ($child->permission ?? 'null') . ")\n";
            
            if ($child->children) {
                foreach ($child->children as $grandchild) {
                    echo "    - Grandchild: " . $grandchild->label . " (key: " . $grandchild->key . ", permission: " . ($grandchild->permission ?? 'null') . ")\n";
                }
            }
        }
    }
}