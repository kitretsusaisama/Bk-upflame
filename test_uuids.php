<?php

require_once 'vendor/autoload.php';

// Load Laravel's bootstrap
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use the Role model
use App\Domains\Access\Models\Role;
use Illuminate\Support\Str;

echo "Testing UUID handling...\n";

// Create a new role with a full UUID
$fullUuid = Str::uuid()->toString();
echo "Full UUID: {$fullUuid}\n";
echo "Full UUID length: " . strlen($fullUuid) . "\n";

// Try to create a role
$role = new Role();
$role->id = $fullUuid;
$role->tenant_id = '019aab08-6071-719d-9884-df6b124e6962'; // Default tenant ID
$role->name = 'Test Role ' . time(); // Make it unique
$role->description = 'Test role for debugging';
$role->is_system = true;
$role->role_family = 'Internal';

// Save the role
try {
    $role->save();
    echo "Role saved successfully!\n";
    echo "Role ID after save: {$role->id}\n";
    echo "Role ID length after save: " . strlen($role->id) . "\n";
    
    // Now try to retrieve the role
    $retrievedRole = Role::find($fullUuid);
    if ($retrievedRole) {
        echo "Role retrieved successfully!\n";
        echo "Retrieved role ID: {$retrievedRole->id}\n";
        echo "Retrieved role ID length: " . strlen($retrievedRole->id) . "\n";
    } else {
        echo "Could not retrieve role!\n";
    }
} catch (Exception $e) {
    echo "Error saving role: " . $e->getMessage() . "\n";
}

echo "\nDone.\n";