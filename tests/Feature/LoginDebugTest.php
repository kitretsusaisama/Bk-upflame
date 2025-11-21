<?php

namespace Tests\Feature;

use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\UserProfile;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class LoginDebugTest extends TestCase
{
    use RefreshDatabase;

    protected $tenant;
    protected $roles = [];

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create tenant
        $this->tenant = Tenant::create([
            'name' => 'Test Tenant',
            'domain' => 'test.local',
            'status' => 'active',
        ]);
        
        // Create roles
        $this->createRoles();
        
        // Create users for all roles
        $this->createTestUsers();
    }

    protected function createRoles()
    {
        // Super Admin Role
        $this->roles['super_admin'] = Role::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Super Admin',
            'role_family' => 'Internal',
            'description' => 'Super administrator with full access to all features',
            'is_system' => true
        ]);

        // Tenant Admin Role
        $this->roles['tenant_admin'] = Role::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Tenant Admin',
            'role_family' => 'Internal',
            'description' => 'Tenant administrator with access to tenant management features',
            'is_system' => true
        ]);

        // Provider Role
        $this->roles['provider'] = Role::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Provider',
            'role_family' => 'Provider',
            'description' => 'Service provider role',
            'is_system' => true
        ]);

        // Customer Role
        $this->roles['customer'] = Role::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Customer',
            'role_family' => 'Customer',
            'description' => 'End customer role',
            'is_system' => true
        ]);

        // Premium Customer Role
        $this->roles['premium_customer'] = Role::create([
            'tenant_id' => $this->tenant->id,
            'name' => 'Premium Customer',
            'role_family' => 'Customer',
            'description' => 'Premium customer with additional privileges',
            'is_system' => true
        ]);
    }

    protected function createTestUsers()
    {
        // Super Admin User
        $superAdminUser = User::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'superadmin@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $this->roles['super_admin']->id
        ]);

        $superAdminUser->profile()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'Super',
            'last_name' => 'Admin',
            'phone' => '+1234567890'
        ]);

        $superAdminUser->roles()->attach($this->roles['super_admin']->id, [
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'assigned_by' => $superAdminUser->id
        ]);

        // Tenant Admin User
        $tenantAdminUser = User::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $this->roles['tenant_admin']->id
        ]);

        $tenantAdminUser->profile()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'Tenant',
            'last_name' => 'Admin',
            'phone' => '+1234567891'
        ]);

        $tenantAdminUser->roles()->attach($this->roles['tenant_admin']->id, [
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'assigned_by' => $superAdminUser->id
        ]);

        // Provider User
        $providerUser = User::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'provider@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $this->roles['provider']->id
        ]);

        $providerUser->profile()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'Service',
            'last_name' => 'Provider',
            'phone' => '+1234567892'
        ]);

        $providerUser->roles()->attach($this->roles['provider']->id, [
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'assigned_by' => $tenantAdminUser->id
        ]);

        // Customer User
        $customerUser = User::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'customer@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $this->roles['customer']->id
        ]);

        $customerUser->profile()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'Regular',
            'last_name' => 'Customer',
            'phone' => '+1234567893'
        ]);

        $customerUser->roles()->attach($this->roles['customer']->id, [
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'assigned_by' => $tenantAdminUser->id
        ]);

        // Premium Customer User
        $premiumCustomerUser = User::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'premium@example.com',
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $this->roles['premium_customer']->id
        ]);

        $premiumCustomerUser->profile()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'Premium',
            'last_name' => 'Customer',
            'phone' => '+1234567894'
        ]);

        $premiumCustomerUser->roles()->attach($this->roles['premium_customer']->id, [
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'assigned_by' => $tenantAdminUser->id
        ]);
    }

    /** @test */
    public function debug_login_for_all_roles()
    {
        // Get all test users
        $users = User::all();
        
        echo "\n=== LOGIN DEBUG TEST FOR ALL ROLES ===\n";
        echo "Total users found: " . $users->count() . "\n\n";
        
        foreach ($users as $user) {
            echo "Testing login for: {$user->email}\n";
            echo "User ID: {$user->id}\n";
            echo "Status: {$user->status}\n";
            
            // Load roles to check what role the user has
            $user->load('roles');
            $roleNames = $user->roles->pluck('name')->implode(', ');
            echo "Roles: " . ($roleNames ?: 'None') . "\n";
            
            // Attempt login
            $response = $this->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);
            
            // Debug information
            echo "Response status: " . $response->getStatusCode() . "\n";
            
            if ($response->isRedirect()) {
                echo "Redirected to: " . $response->headers->get('Location') . "\n";
                echo "Login SUCCESS\n";
            } else {
                echo "Login FAILED\n";
                // Check for errors
                if (session('errors')) {
                    $errors = session('errors')->all();
                    echo "Errors: " . implode(', ', $errors) . "\n";
                }
            }
            
            // Check if user is authenticated
            if (auth()->check()) {
                echo "User is authenticated\n";
                auth()->logout(); // Logout for next test
            } else {
                echo "User is NOT authenticated\n";
            }
            
            echo str_repeat('-', 50) . "\n";
        }
    }
    
    /** @test */
    public function test_specific_login_scenarios()
    {
        // Test each specific role
        $testUsers = [
            'superadmin@example.com' => 'Super Admin',
            'admin@example.com' => 'Tenant Admin',
            'provider@example.com' => 'Provider',
            'customer@example.com' => 'Customer',
            'premium@example.com' => 'Premium Customer',
        ];
        
        echo "\n=== SPECIFIC LOGIN SCENARIOS ===\n";
        
        foreach ($testUsers as $email => $expectedRole) {
            echo "\nTesting {$expectedRole} ({$email})\n";
            
            $user = User::where('email', $email)->first();
            if (!$user) {
                echo "User not found!\n";
                continue;
            }
            
            // Check user status
            echo "Status: {$user->status}\n";
            
            // Check if user has the expected role
            $user->load('roles');
            $actualRoles = $user->roles->pluck('name')->toArray();
            echo "Actual roles: " . implode(', ', $actualRoles) . "\n";
            
            // Attempt login
            $response = $this->post('/login', [
                'email' => $email,
                'password' => 'password',
            ]);
            
            echo "Response status: " . $response->getStatusCode() . "\n";
            
            if ($response->isRedirect()) {
                $location = $response->headers->get('Location');
                echo "Redirected to: {$location}\n";
                
                // Check if redirected to correct dashboard
                if (strpos($location, 'dashboard') !== false) {
                    echo "✓ Correctly redirected to dashboard\n";
                } else {
                    echo "✗ Unexpected redirect location\n";
                }
            } else {
                echo "✗ Login failed\n";
                // Check for validation errors
                $errors = session('errors');
                if ($errors) {
                    foreach ($errors->all() as $error) {
                        echo "Error: {$error}\n";
                    }
                }
            }
            
            // Logout if authenticated
            if (auth()->check()) {
                auth()->logout();
            }
        }
    }
}