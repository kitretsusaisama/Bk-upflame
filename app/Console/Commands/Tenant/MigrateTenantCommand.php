<?php

namespace App\Console\Commands\Tenant;

use Illuminate\Console\Command;

class MigrateTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:migrate {id?} {--fresh} {--seed}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run migrations for a tenant';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $tenantId = $this->argument('id');
        $fresh = $this->option('fresh');
        $seed = $this->option('seed');
        
        if ($tenantId) {
            // Run migrations for a specific tenant
            $this->info("Running migrations for tenant ID: {$tenantId}");
        } else {
            // Run migrations for all tenants
            $this->info("Running migrations for all tenants");
        }
        
        // This would typically involve:
        // 1. If using separate databases per tenant, switching to the tenant's database
        // 2. Running Laravel's migration commands
        // 3. Optionally seeding data
        
        return 0;
    }
}