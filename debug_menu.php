<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$u = App\Domains\Identity\Models\User::where('email', 'tenant@example.com')->first();
if (!$u) {
    echo "User not found!\n";
    exit;
}
echo "User: " . $u->email . "\n";

foreach($u->roles as $role) {
    echo "Role: " . $role->name . "\n";
    echo "Permissions: " . $role->permissions->pluck('name')->implode(', ') . "\n";
}
