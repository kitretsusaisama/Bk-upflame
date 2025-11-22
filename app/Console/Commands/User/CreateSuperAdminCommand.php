<?php

namespace App\Console\Commands\User;

use Illuminate\Console\Command;

class CreateSuperAdminCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'user:create-super-admin {email} {name} {password}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a super admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $email = $this->argument('email');
        $name = $this->argument('name');
        $password = $this->argument('password');
        
        // Logic to create a super admin user
        $this->info("Creating super admin user: {$name} ({$email})");
        
        // This would typically involve:
        // 1. Creating a user record with super admin privileges
        // 2. Assigning the super admin role
        // 3. Setting the password
        
        return 0;
    }
}