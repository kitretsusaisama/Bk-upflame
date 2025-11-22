<?php

namespace App\Console\Commands\Cache;

use Illuminate\Console\Command;

class WarmPermissionCacheCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'cache:warm-permissions {tenant?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Warm the permission cache for a tenant or all tenants';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tenant = $this->argument('tenant');
        
        if ($tenant) {
            // Warm cache for a specific tenant
            $this->info("Warming permission cache for tenant: {$tenant}");
        } else {
            // Warm cache for all tenants
            $this->info("Warming permission cache for all tenants");
        }
        
        // This would typically involve:
        // 1. Loading all permissions for the tenant(s)
        // 2. Storing them in the cache for faster access
        // 3. This improves performance for permission checks
        
        return 0;
    }
}