<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

// Debug the migration logic manually
echo "Debugging migration logic...\n";

// Check if default tenant exists
$tenant = DB::table('tenants')->where('domain', 'default.local')->first();
if ($tenant) {
    echo "Default tenant found: {$tenant->name} ({$tenant->id})\n";
    
    // Check if localhost domain exists
    $localhostExists = DB::table('tenant_domains')->where('domain', 'localhost')->exists();
    echo "Localhost domain exists: " . ($localhostExists ? 'Yes' : 'No') . "\n";
    
    // Check if 127.0.0.1 domain exists
    $ipExists = DB::table('tenant_domains')->where('domain', '127.0.0.1')->exists();
    echo "127.0.0.1 domain exists: " . ($ipExists ? 'Yes' : 'No') . "\n";
    
    // Try to add localhost domain manually
    if (!$localhostExists) {
        echo "Adding localhost domain...\n";
        try {
            DB::table('tenant_domains')->insert([
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'domain' => 'localhost',
                'is_primary' => false,
                'is_verified' => true,
                'created_at' => now(),
            ]);
            echo "Localhost domain added successfully\n";
        } catch (Exception $e) {
            echo "Error adding localhost domain: " . $e->getMessage() . "\n";
        }
    }
    
    // Try to add 127.0.0.1 domain manually
    if (!$ipExists) {
        echo "Adding 127.0.0.1 domain...\n";
        try {
            DB::table('tenant_domains')->insert([
                'id' => Str::uuid(),
                'tenant_id' => $tenant->id,
                'domain' => '127.0.0.1',
                'is_primary' => false,
                'is_verified' => true,
                'created_at' => now(),
            ]);
            echo "127.0.0.1 domain added successfully\n";
        } catch (Exception $e) {
            echo "Error adding 127.0.0.1 domain: " . $e->getMessage() . "\n";
        }
    }
} else {
    echo "Default tenant with domain 'default.local' not found\n";
    
    // Check what tenants exist
    $tenants = DB::table('tenants')->get();
    echo "Existing tenants:\n";
    foreach ($tenants as $t) {
        echo "  - {$t->name} ({$t->domain})\n";
    }
}