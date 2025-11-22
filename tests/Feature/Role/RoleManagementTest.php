<?php

namespace Tests\Feature\Role;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Authorization\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_new_role()
    {
        $tenant = Tenant::factory()->create();

        $response = $this->postJson('/api/v1/roles', [
            'tenant_id' => $tenant->id,
            'name' => 'Editor',
            'slug' => 'editor',
            'description' => 'Can edit content',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                ]
            ]);

        $this->assertDatabaseHas('roles', [
            'name' => 'Editor',
            'slug' => 'editor',
        ]);
    }

    /** @test */
    public function it_can_list_roles()
    {
        $tenant = Tenant::factory()->create();
        Role::factory()->count(3)->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->getJson('/api/v1/roles');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'name', 'slug']
                ]
            ]);
    }

    /** @test */
    public function it_can_update_a_role()
    {
        $tenant = Tenant::factory()->create();
        $role = Role::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Old Role',
        ]);

        $response = $this->putJson("/api/v1/roles/{$role->id}", [
            'name' => 'Updated Role',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                ]
            ]);

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'Updated Role',
        ]);
    }

    /** @test */
    public function it_can_delete_a_role()
    {
        $tenant = Tenant::factory()->create();
        $role = Role::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->deleteJson("/api/v1/roles/{$role->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Role deleted successfully'
            ]);

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    }
}