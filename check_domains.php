<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;
use App\Domains\Identity\Models\TenantDomain;

// Check tenant domains
echo "Checking tenant domains...\n";

$domains = TenantDomain::all();
echo "Total domains: " . $domains->count() . "\n";

foreach ($domains as $domain) {
    echo "Domain: {$domain->domain} for tenant {$domain->tenant->name}\n";
}

echo "\n";

// Check specifically for localhost domains
$localhostDomains = TenantDomain::where('domain', 'localhost')->get();
echo "Localhost domains: " . $localhostDomains->count() . "\n";

$ipDomains = TenantDomain::where('domain', '127.0.0.1')->get();
echo "127.0.0.1 domains: " . $ipDomains->count() . "\n";

// Check if default tenant exists
$defaultTenant = DB::table('tenants')->where('domain', 'default.local')->first();
if ($defaultTenant) {
    echo "Default tenant exists: {$defaultTenant->name}\n";
    
    // Check if localhost domain exists for this tenant
    $localhostForTenant = DB::table('tenant_domains')->where([
        'tenant_id' => $defaultTenant->id,
        'domain' => 'localhost'
    ])->exists();
    
    echo "Localhost domain for default tenant exists: " . ($localhostForTenant ? 'Yes' : 'No') . "\n";
} else {
    echo "Default tenant does not exist\n";
}