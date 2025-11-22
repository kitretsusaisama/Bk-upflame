<?php

namespace App\Console\Commands\Tenant;

use Illuminate\Console\Command;
use App\Domains\Tenant\Models\Tenant;
use App\Domains\Identity\Models\User;

class SuspendTenantCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tenant:suspend {id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Suspend a tenant';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $id = $this->argument('id');
        
        // Find the tenant by ID
        $tenant = Tenant::find($id);
        
        if (!$tenant) {
            $this->error("Tenant with ID {$id} not found!");
            return 1;
        }
        
        // Update the tenant's status to 'suspended'
        $tenant->update(['status' => 'suspended']);
        
        // Revoke all active sessions for the tenant's users
        $users = User::where('tenant_id', $tenant->id)->get();
        foreach ($users as $user) {
            $user->tokens()->delete();
        }
        
        // Log the suspension
        $this->info("Tenant '{$tenant->name}' (ID: {$id}) has been suspended successfully.");
        $this->info("All active sessions for {$users->count()} users have been revoked.");
        
        return 0;
    }
}