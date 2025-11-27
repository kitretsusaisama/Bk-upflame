<?php

use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\Tenant;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

try {
    $tenant = Tenant::where('slug', 'system')->first();
    echo "Tenant found: " . ($tenant ? $tenant->id : 'No') . "\n";

    $user = User::create([
        'name' => 'Debug User',
        'email' => 'debug@example.com',
        'password' => bcrypt('password'),
        'tenant_id' => $tenant ? $tenant->id : null,
        'status' => 'active',
    ]);
    echo "User created: " . $user->id . "\n";
} catch (\Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString();
}
