<?php

namespace Tests\Feature\Api;

use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class TenantManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_create_new_tenant(): void
    {
        $superAdmin = $this->createUserWithRole('Super Admin');
        Sanctum::actingAs($superAdmin);

        $response = $this->postJson('/api/v1/tenants', [
            'name' => 'Acme Corporation',
            'domain' => 'acme-' . Str::random(6) . '.example.com',
            'admin_email' => 'admin@acme.test',
            'admin_password' => 'SecurePass123!',
        ]);

        $response->assertCreated()
            ->assertJsonPath('data.name', 'Acme Corporation');

        $this->assertDatabaseHas('tenants', [
            'domain' => $response->json('data.domain'),
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'admin@acme.test',
        ]);
    }

    private function createUserWithRole(string $roleName): User
    {
        $tenant = Tenant::factory()->create();

        $role = Role::factory()->for($tenant, 'tenant')->create([
            'name' => $roleName,
            'role_family' => 'Internal',
            'is_system' => true,
        ]);

        $user = User::factory()->for($tenant, 'tenant')->create([
            'status' => 'active',
            'primary_role_id' => $role->id,
        ]);

        $user->roles()->attach($role->id, [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'assigned_by' => $user->id,
        ]);

        return $user->fresh('tenant', 'roles');
    }
}


