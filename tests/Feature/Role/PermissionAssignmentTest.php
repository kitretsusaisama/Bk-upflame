<?php

namespace Tests\Feature\Role;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Authorization\Models\Role;
use App\Domain\Authorization\Models\Permission;
use Illuminate\Foundation\Testing\RefreshDatabase;

class PermissionAssignmentTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_assign_permission_to_role()
    {
        $tenant = Tenant::factory()->create();
        $role = Role::factory()->create([
            'tenant_id' => $tenant->id,
        ]);
        
        $permission = Permission::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->postJson("/api/v1/roles/{$role->id}/permissions", [
            'permission_id' => $permission->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Permission assigned successfully'
            ]);

        $this->assertDatabaseHas('permission_role', [
            'permission_id' => $permission->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function it_can_remove_permission_from_role()
    {
        $tenant = Tenant::factory()->create();
        $role = Role::factory()->create([
            'tenant_id' => $tenant->id,
        ]);
        
        $permission = Permission::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Assign permission first
        $role->permissions()->attach($permission);

        $response = $this->deleteJson("/api/v1/roles/{$role->id}/permissions/{$permission->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Permission removed successfully'
            ]);

        $this->assertDatabaseMissing('permission_role', [
            'permission_id' => $permission->id,
            'role_id' => $role->id,
        ]);
    }
}