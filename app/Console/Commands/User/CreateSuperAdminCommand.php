<?php

namespace App\Console\Commands\User;

use Illuminate\Console\Command;
use App\Domains\Identity\Models\User;
use App\Domains\Access\Models\Role;
use Illuminate\Support\Facades\Hash;

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
        
        // Check if user already exists
        if (User::where('email', $email)->exists()) {
            $this->error("User with email {$email} already exists!");
            return 1;
        }
        
        // Create the super admin user
        $user = User::create([
            'name' => $name,
            'email' => $email,
            'password' => Hash::make($password),
            'status' => 'active',
        ]);
        
        // Create or get the super admin role
        $role = Role::firstOrCreate(
            ['name' => 'Super Admin'], // Only search by name
            [
                'description' => 'Super administrator with full access',
                'is_system' => true,
            ]
        );
        
        // Assign the role to the user
        $user->roles()->attach($role->id);
        
        $this->info("Super admin user created successfully: {$name} ({$email})");
        $this->info("Role assigned: {$role->name}");
        
        return 0;
    }
}