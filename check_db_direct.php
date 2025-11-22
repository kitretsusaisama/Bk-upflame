<?php

require_once 'vendor/autoload.php';

// Load Laravel's bootstrap
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Use direct database connection
use Illuminate\Support\Facades\DB;

echo "Checking database contents directly...\n";

// Check tenants
$tenants = DB::select('SELECT id, name FROM tenants');
echo "Tenants:\n";
foreach ($tenants as $tenant) {
    echo "  - ID: {$tenant->id}, Name: {$tenant->name}\n";
    echo "    Length: " . strlen($tenant->id) . "\n";
}

// Check roles
$roles = DB::select('SELECT id, name FROM roles');
echo "\nRoles:\n";
foreach ($roles as $role) {
    echo "  - ID: {$role->id}, Name: {$role->name}\n";
    echo "    Length: " . strlen($role->id) . "\n";
}

// Check permissions
$permissions = DB::select('SELECT id, name FROM permissions LIMIT 5');
echo "\nPermissions (first 5):\n";
foreach ($permissions as $permission) {
    echo "  - ID: {$permission->id}, Name: {$permission->name}\n";
    echo "    Length: " . strlen($permission->id) . "\n";
}

// Check role_permissions table structure
echo "\nRole_permissions table structure:\n";
try {
    $columns = DB::select('SHOW COLUMNS FROM role_permissions');
    foreach ($columns as $column) {
        echo "  - {$column->Field}: {$column->Type}\n";
    }
} catch (Exception $e) {
    echo "  Error: " . $e->getMessage() . "\n";
}

echo "\nDone.\n";