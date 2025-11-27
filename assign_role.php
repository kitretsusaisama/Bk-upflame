<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = App\Domains\Identity\Models\User::where('email', 'tenant@example.com')->first();
if (!$user) {
    echo "User not found!\n";
    exit;
}

$role = App\Domains\Access\Models\Role::where('name', 'Tenant Admin')->first();
if (!$role) {
    echo "Role 'Tenant Admin' not found!\n";
    exit;
}

// Check if already assigned
$exists = Illuminate\Support\Facades\DB::table('user_roles')
    ->where('user_id', $user->id)
    ->where('role_id', $role->id)
    ->where('tenant_id', $user->tenant_id)
    ->exists();

if ($exists) {
    echo "Role already assigned.\n";
} else {
    Illuminate\Support\Facades\DB::table('user_roles')->insert([
        'id' => (string) Illuminate\Support\Str::uuid(),
        'user_id' => $user->id,
        'role_id' => $role->id,
        'tenant_id' => $user->tenant_id,
        'created_at' => now(),
        'updated_at' => now(),
    ]);
    echo "Assigned 'Tenant Admin' to {$user->email}\n";
}

// Verify
$user->load('roles');
echo "Current Roles: " . $user->roles->pluck('name')->implode(', ') . "\n";
