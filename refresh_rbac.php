<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\User;

echo "Truncating RBAC tables...\n";
DB::statement('SET FOREIGN_KEY_CHECKS=0;');
DB::table('role_permissions')->truncate();
DB::table('user_roles')->truncate();
DB::table('permissions')->truncate();
DB::table('roles')->truncate();
DB::table('menus')->truncate(); // Also refresh menus to be safe
DB::statement('SET FOREIGN_KEY_CHECKS=1;');

echo "Running Seeders...\n";
$seeders = [
    \Database\Seeders\PermissionSeeder::class,
    \Database\Seeders\RoleSeeder::class,
    \Database\Seeders\MenuSeeder::class,
];

foreach ($seeders as $seeder) {
    echo "Running " . class_basename($seeder) . "...\n";
    (new $seeder)->run();
}

echo "Re-assigning Role to User...\n";
$user = User::where('email', 'tenant@example.com')->first();
if ($user) {
    $role = Role::where('name', 'Tenant Admin')->first();
    if ($role) {
        $user->roles()->attach($role->id, ['tenant_id' => $user->tenant_id]);
        echo "Assigned 'Tenant Admin' to {$user->email}\n";
    } else {
        echo "Error: 'Tenant Admin' role not found!\n";
    }
} else {
    echo "Error: User 'tenant@example.com' not found!\n";
}

echo "Done!\n";
