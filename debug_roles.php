<?php

require_once 'vendor/autoload.php';

// Load Laravel's bootstrap
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Now we can use Laravel's models
use App\Domains\Access\Models\Role;
use App\Domains\Access\Models\Permission;

echo "Debugging roles and permissions...\n";

// Get all roles
$roles = Role::all();
echo "Roles:\n";
foreach ($roles as $role) {
    echo "  - ID: {$role->id}, Name: {$role->name}\n";
    echo "    Length: " . strlen($role->id) . "\n";
}

// Get all permissions
$permissions = Permission::all();
echo "\nPermissions count: " . $permissions->count() . "\n";

// Try to get a specific role
$superAdminRole = Role::where('name', 'Super Admin')->first();
if ($superAdminRole) {
    echo "\nSuper Admin Role:\n";
    echo "  - ID: {$superAdminRole->id}\n";
    echo "  - Name: {$superAdminRole->name}\n";
    
    // Try to get all permissions
    $allPermissions = Permission::all();
    echo "  - All permissions count: " . $allPermissions->count() . "\n";
    
    // Try to sync some permissions
    $permissionIds = $allPermissions->pluck('id')->toArray();
    echo "  - Permission IDs to sync: " . implode(', ', array_slice($permissionIds, 0, 5)) . "...\n";
    
    // Check if permissions exist
    $existingPermissionIds = Permission::whereIn('id', $permissionIds)->pluck('id')->toArray();
    echo "  - Existing permission IDs count: " . count($existingPermissionIds) . "\n";
    
    // Try to sync (this is where the error occurs)
    try {
        echo "  - Attempting to sync permissions...\n";
        $superAdminRole->permissions()->sync(array_slice($permissionIds, 0, 3)); // Only first 3
        echo "  - Sync successful!\n";
    } catch (Exception $e) {
        echo "  - Sync failed: " . $e->getMessage() . "\n";
    }
} else {
    echo "Super Admin role not found!\n";
}

echo "\nDone.\n";