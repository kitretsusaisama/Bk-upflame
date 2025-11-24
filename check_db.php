<?php

require_once 'vendor/autoload.php';

// Load Laravel's bootstrap
$app = require_once 'bootstrap/app.php';

// Bootstrap the application
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Now we can use Laravel's DB facade
use Illuminate\Support\Facades\DB;

echo "Checking database contents...\n";

// Check tenants
$tenants = DB::table('tenants')->get();
echo "Tenants:\n";
foreach ($tenants as $tenant) {
    echo "  - ID: {$tenant->id}, Name: {$tenant->name}\n";
}

// Check roles
$roles = DB::table('roles')->get();
echo "\nRoles:\n";
foreach ($roles as $role) {
    echo "  - ID: {$role->id}, Name: {$role->name}\n";
}

// Check permissions
$permissions = DB::table('permissions')->get();
echo "\nPermissions:\n";
foreach ($permissions as $permission) {
    echo "  - ID: {$permission->id}, Name: {$permission->name}\n";
}

echo "\nDone.\n";