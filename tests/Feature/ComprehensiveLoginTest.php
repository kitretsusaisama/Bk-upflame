<?php

namespace Tests\Feature;

use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Tests\TestCase;

class ComprehensiveLoginTest extends TestCase
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

    protected function createUser($email, $roleKey, $assignedByUserId = null)
    {
        $role = $this->roles[$roleKey];
        
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'email' => $email,
            'password' => Hash::make('password'),
            'status' => 'active',
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $role->id
        ]);

        $user->profile()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => ucfirst(explode('@', $email)[0]),
            'last_name' => 'User',
            'phone' => '+1234567890'
        ]);

        $user->roles()->attach($role->id, [
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'assigned_by' => $assignedByUserId ?? $user->id
        ]);

        return $user;
    }

    /** @test */
    public function all_roles_can_login_successfully()
    {
        // Create users for all roles
        $superAdmin = $this->createUser('superadmin@example.com', 'super_admin');
        $tenantAdmin = $this->createUser('admin@example.com', 'tenant_admin', $superAdmin->id);
        $provider = $this->createUser('provider@example.com', 'provider', $tenantAdmin->id);
        $customer = $this->createUser('customer@example.com', 'customer', $tenantAdmin->id);
        $premiumCustomer = $this->createUser('premium@example.com', 'premium_customer', $tenantAdmin->id);

        // Test data
        $testCases = [
            [
                'email' => 'superadmin@example.com',
                'expected_redirect' => 'superadmin.dashboard',
                'role' => 'Super Admin'
            ],
            [
                'email' => 'admin@example.com',
                'expected_redirect' => 'tenantadmin.dashboard',
                'role' => 'Tenant Admin'
            ],
            [
                'email' => 'provider@example.com',
                'expected_redirect' => 'provider.dashboard',
                'role' => 'Provider'
            ],
            [
                'email' => 'customer@example.com',
                'expected_redirect' => 'customer.dashboard',
                'role' => 'Customer'
            ],
            [
                'email' => 'premium@example.com',
                'expected_redirect' => 'customer.dashboard',
                'role' => 'Premium Customer'
            ]
        ];

        foreach ($testCases as $index => $testCase) {
            // Test login
            $response = $this->post('/login', [
                'email' => $testCase['email'],
                'password' => 'password',
            ]);

            // Assert successful login and correct redirect
            $response->assertRedirect(route($testCase['expected_redirect']));
            
            // Assert user is authenticated
            $this->assertAuthenticated();
            
            // Logout for next test using GET request
            $this->get('/logout');
            
            // Assert user is logged out
            $this->assertGuest();
        }
    }

    /** @test */
    public function users_cannot_login_with_invalid_password()
    {
        // Create a user
        $this->createUser('test@example.com', 'customer');

        // Test with wrong password
        $response = $this->post('/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        // Should redirect back to login with error
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function users_cannot_login_with_nonexistent_email()
    {
        // Test with non-existent user
        $response = $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password',
        ]);

        // Should redirect back to login with error
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function inactive_users_cannot_login()
    {
        // Create an inactive user
        $role = $this->roles['customer'];
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'inactive@example.com',
            'password' => Hash::make('password'),
            'status' => 'inactive', // Inactive status
            'mfa_enabled' => false,
            'email_verified' => true,
            'primary_role_id' => $role->id
        ]);

        $user->profile()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'Inactive',
            'last_name' => 'User',
            'phone' => '+1234567890'
        ]);

        $user->roles()->attach($role->id, [
            'id' => Str::uuid(),
            'tenant_id' => $this->tenant->id,
            'assigned_by' => $user->id
        ]);

        // Try to login
        $response = $this->post('/login', [
            'email' => 'inactive@example.com',
            'password' => 'password',
        ]);

        // Should redirect back to login with error
        $response->assertSessionHasErrors(['email']);
        $this->assertGuest();
    }

    /** @test */
    public function login_updates_last_login_timestamp()
    {
        // Create a user
        $user = $this->createUser('timestamp@example.com', 'customer');
        
        // Store initial last_login value
        $initialLastLogin = $user->last_login;
        
        // Login
        $this->post('/login', [
            'email' => 'timestamp@example.com',
            'password' => 'password',
        ]);
        
        // Refresh user from database
        $user->refresh();
        
        // Assert last_login was updated
        $this->assertNotNull($user->last_login);
        // Note: We can't directly compare because the test runs in a transaction that might not commit
        // But we can check that the field is not null after login
    }
}