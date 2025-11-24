<?php

require_once 'vendor/autoload.php';

// Bootstrap Laravel
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Get a provider user
$user = App\Domains\Identity\Models\User::where('email', 'provider@example.com')->first();
$user->load('roles');

echo "User roles: " . json_encode($user->roles->pluck('name')->toArray()) . "\n";
echo "User permissions: \n";
foreach ($user->roles as $role) {
    $permissions = DB::table('role_permissions')
        ->join('permissions', 'role_permissions.permission_id', '=', 'permissions.id')
        ->where('role_permissions.role_id', $role->id)
        ->pluck('permissions.name')
        ->toArray();
    echo "  Role {$role->name}: " . json_encode($permissions) . "\n";
}

// Test specific permission
echo "\nTesting specific permissions:\n";
echo "Can view-provider-dashboard: " . ($user->can('view-provider-dashboard') ? 'YES' : 'NO') . "\n";
echo "Can manage-provider-services: " . ($user->can('manage-provider-services') ? 'YES' : 'NO') . "\n";
echo "Can view-provider-bookings: " . ($user->can('view-provider-bookings') ? 'YES' : 'NO') . "\n";

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

echo "\nAll menus for tenant: " . $allMenus->count() . "\n";

// Filter menus by user permissions (same logic as in MenuRepository)
$filteredMenus = $allMenus->filter(function($menu) use ($user) {
    // If no permission is required, show the menu item
    if (!$menu->permission) {
        return true;
    }
    
    // Check if user has the required permission
    $can = $user->can($menu->permission);
    if ($menu->key === 'provider-dashboard') {
        echo "Provider dashboard menu - permission: {$menu->permission}, user can: " . ($can ? 'YES' : 'NO') . "\n";
    }
    return $can;
})->values();

echo "Filtered menus: " . $filteredMenus->count() . "\n";

// Show which menus the user can see
echo "\nMenus user can see:\n";
foreach ($filteredMenus as $menu) {
    echo "Menu: " . $menu->label . " (key: " . $menu->key . ", permission: " . ($menu->permission ?? 'null') . ")\n";
    if ($menu->parent_id) {
        $parent = $allMenus->firstWhere('id', $menu->parent_id);
        if ($parent) {
            echo "  Parent: " . $parent->label . " (key: " . $parent->key . ")\n";
        }
    }
}

// Now test the buildMenuHierarchy method
$menuRepository = app(App\Domains\Menu\Repositories\MenuRepository::class);
$hierarchicalMenus = $menuRepository->getMenuForTenantAndUser($tenantId, $user);

echo "\nHierarchical menus:\n";
echo "Number of root menus: " . $hierarchicalMenus->count() . "\n";

foreach ($hierarchicalMenus as $menu) {
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