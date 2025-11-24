<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Access\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Str;

class SuperAdminUserManagementTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected $superAdmin;
    protected $tenant;
    protected $roles;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create a tenant
        $this->tenant = Tenant::factory()->create();
        
        // Create roles
        $this->roles = [
            'Super Admin' => Role::factory()->create([
                'name' => 'Super Admin',
                'tenant_id' => null,
                'is_system' => true
            ]),
            'Tenant Admin' => Role::factory()->create([
                'name' => 'Tenant Admin',
                'tenant_id' => $this->tenant->id,
                'is_system' => true
            ]),
            'Provider' => Role::factory()->create([
                'name' => 'Provider',
                'tenant_id' => $this->tenant->id,
                'is_system' => true
            ])
        ];
        
        // Create super admin user
        $this->superAdmin = User::factory()->create([
            'email' => 'superadmin@example.com',
            'tenant_id' => null,
            'status' => 'active'
        ]);
        
        // Assign super admin role
        $this->superAdmin->roles()->attach($this->roles['Super Admin']->id, [
            'id' => Str::uuid()->toString(),
            'tenant_id' => null,
            'assigned_by' => $this->superAdmin->id
        ]);
    }

    /** @test */
    public function super_admin_can_view_users_list()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        
        $response = $this->get('/api/v1/superadmin/users');
        
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success'
        ]);
    }

    /** @test */
    public function super_admin_can_create_user()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        
        $userData = [
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'first_name' => 'New',
            'last_name' => 'User',
            'tenant_id' => $this->tenant->id,
            'role' => 'Tenant Admin'
        ];
        
        $response = $this->post('/api/v1/superadmin/users', $userData);
        
        $response->assertStatus(201);
        $response->assertJson([
            'status' => 'success'
        ]);
        
        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com'
        ]);
    }

    /** @test */
    public function super_admin_can_view_user_details()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'tenant_id' => $this->tenant->id
        ]);
        
        $response = $this->get('/api/v1/superadmin/users/' . $user->id);
        
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success',
            'data' => [
                'id' => $user->id,
                'email' => 'testuser@example.com'
            ]
        ]);
    }

    /** @test */
    public function super_admin_can_update_user()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'tenant_id' => $this->tenant->id
        ]);
        
        $updateData = [
            'email' => 'updateduser@example.com',
            'first_name' => 'Updated',
            'last_name' => 'User',
            'tenant_id' => $this->tenant->id,
            'role' => 'Provider'
        ];
        
        $response = $this->put('/api/v1/superadmin/users/' . $user->id, $updateData);
        
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success'
        ]);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'updateduser@example.com'
        ]);
    }

    /** @test */
    public function super_admin_can_delete_user()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'tenant_id' => $this->tenant->id
        ]);
        
        $response = $this->delete('/api/v1/superadmin/users/' . $user->id);
        
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success'
        ]);
        
        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
            'email' => 'testuser@example.com'
        ]);
    }

    /** @test */
    public function super_admin_can_activate_user()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'tenant_id' => $this->tenant->id,
            'status' => 'inactive'
        ]);
        
        $response = $this->post('/api/v1/superadmin/users/' . $user->id . '/activate');
        
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success'
        ]);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'active'
        ]);
    }

    /** @test */
    public function super_admin_can_deactivate_user()
    {
        $this->actingAs($this->superAdmin, 'sanctum');
        
        $user = User::factory()->create([
            'email' => 'testuser@example.com',
            'tenant_id' => $this->tenant->id,
            'status' => 'active'
        ]);
        
        $response = $this->post('/api/v1/superadmin/users/' . $user->id . '/deactivate');
        
        $response->assertStatus(200);
        $response->assertJson([
            'status' => 'success'
        ]);
        
        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'status' => 'inactive'
        ]);
    }
}