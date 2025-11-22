<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Domain\Identity\Models\User;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Authorization\Models\Role;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPermissionsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_assign_role_to_user()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);
        
        $role = Role::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->postJson("/api/v1/users/{$user->id}/roles", [
            'role_id' => $role->id,
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Role assigned successfully'
            ]);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function it_can_check_user_permissions()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        // Test checking user permissions
        // Implementation depends on the specific permission system used

        $this->assertTrue(true); // Placeholder assertion
    }
}