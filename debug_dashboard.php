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

$roles = $u->roles;
echo "Roles Count: " . $roles->count() . "\n";
foreach($roles as $r) {
    echo "Role: {$r->name} | ID: {$r->id} | Priority: " . ($r->priority ?? 'NULL') . "\n";
    print_r($r->toArray());
}
