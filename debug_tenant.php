<?php

require_once __DIR__ . '/vendor/autoload.php';

// Bootstrap Laravel
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\TenantDomain;
use Illuminate\Support\Facades\Auth;
use App\Domains\Identity\Models\User;

// Check tenant configuration
echo "Debugging tenant resolution...\n";

// Get all tenants and their domains
$tenants = Tenant::all();
echo "Total tenants: " . $tenants->count() . "\n";

foreach ($tenants as $tenant) {
    echo "Tenant: {$tenant->name} ({$tenant->id})\n";
    echo "  Status: {$tenant->status}\n";
    
    $domains = $tenant->domains;
    echo "  Domains: " . $domains->pluck('domain')->implode(', ') . "\n";
}

echo "\n";

// Check if we have localhost domains
$localhostDomains = TenantDomain::where('domain', 'localhost')->get();
echo "Localhost domains: " . $localhostDomains->count() . "\n";
foreach ($localhostDomains as $domain) {
    echo "  Domain: {$domain->domain} for tenant {$domain->tenant->name}\n";
}

echo "\n";

// Check tenant resolution for localhost
echo "Testing tenant resolution for localhost...\n";
$host = 'localhost';

// Try to resolve tenant domain
$tenantDomain = TenantDomain::where('domain', $host)->first();
if ($tenantDomain) {
    echo "Found tenant domain: {$tenantDomain->domain} for tenant {$tenantDomain->tenant->name}\n";
} else {
    echo "No tenant domain found for {$host}\n";
    
    // Check fallback
    $fallbackTenant = Tenant::first();
    if ($fallbackTenant) {
        echo "Fallback tenant: {$fallbackTenant->name}\n";
    }
}

echo "\n";

// Check if user can access tenant
$user = User::where('email', 'provider@example.com')->first();
if ($user) {
    echo "User tenant ID: {$user->tenant_id}\n";
    
    if ($tenantDomain) {
        echo "Tenant domain tenant ID: {$tenantDomain->tenant->id}\n";
        echo "Match: " . ($user->tenant_id === $tenantDomain->tenant->id ? 'Yes' : 'No') . "\n";
    }
    
    if ($fallbackTenant) {
        echo "Fallback tenant ID: {$fallbackTenant->id}\n";
        echo "Match: " . ($user->tenant_id === $fallbackTenant->id ? 'Yes' : 'No') . "\n";
    }
}