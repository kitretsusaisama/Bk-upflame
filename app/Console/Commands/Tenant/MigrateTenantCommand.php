<?php

namespace App\Console\Commands\Tenant;

use Illuminate\Console\Command;
use App\Domains\Tenant\Models\Tenant;
use Illuminate\Support\Facades\Artisan;

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
            $tenant = Tenant::find($tenantId);
            
            if (!$tenant) {
                $this->error("Tenant with ID {$tenantId} not found!");
                return 1;
            }
            
            $this->info("Running migrations for tenant: {$tenant->name} (ID: {$tenantId})");
            
            // Run migrations for the tenant
            $options = [];
            if ($fresh) {
                $options['--fresh'] = true;
            }
            
            Artisan::call('migrate', $options);
            $this->info(Artisan::output());
            
            // Seed data if requested
            if ($seed) {
                Artisan::call('db:seed');
                $this->info(Artisan::output());
            }
        } else {
            // Run migrations for all tenants
            $this->info("Running migrations for all tenants");
            
            // Run migrations
            $options = [];
            if ($fresh) {
                $options['--fresh'] = true;
            }
            
            Artisan::call('migrate', $options);
            $this->info(Artisan::output());
            
            // Seed data if requested
            if ($seed) {
                Artisan::call('db:seed');
                $this->info(Artisan::output());
            }
        }
        
        $this->info('Migrations completed successfully!');
        
        return 0;
    }
}