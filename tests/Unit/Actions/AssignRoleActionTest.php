<?php

namespace Tests\Unit\Actions;

use Tests\TestCase;
use App\Domain\Authorization\Actions\AssignRoleAction;
use App\Domain\Authorization\Models\Role;
use App\Domain\Identity\Models\User;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class AssignRoleActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_assign_a_role_to_a_user()
    {
        $action = new AssignRoleAction();
        
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $role = Role::factory()->create(['tenant_id' => $tenant->id]);

        $result = $action->execute($user, $role);

        $this->assertTrue($result);
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role->id,
        ]);
    }

    /** @test */
    public function it_can_assign_multiple_roles_to_a_user()
    {
        $action = new AssignRoleAction();
        
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $role1 = Role::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Role 1']);
        $role2 = Role::factory()->create(['tenant_id' => $tenant->id, 'name' => 'Role 2']);

        $action->execute($user, $role1);
        $action->execute($user, $role2);

        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role1->id,
        ]);
        
        $this->assertDatabaseHas('role_user', [
            'user_id' => $user->id,
            'role_id' => $role2->id,
        ]);
    }
}