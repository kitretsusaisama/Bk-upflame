<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Domain\Authorization\Services\RoleService;
use App\Domain\Authorization\Models\Role;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleServiceTest extends TestCase
{
    use RefreshDatabase;

    protected RoleService $roleService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->roleService = new RoleService();
    }

    /** @test */
    public function it_can_create_a_new_role()
    {
        $tenant = Tenant::factory()->create();
        
        $roleData = [
            'tenant_id' => $tenant->id,
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'System administrator with full access'
        ];

        $role = $this->roleService->createRole($roleData);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('Administrator', $role->name);
        $this->assertEquals('admin', $role->slug);
        $this->assertEquals($tenant->id, $role->tenant_id);
        $this->assertDatabaseHas('roles', ['slug' => 'admin']);
    }

    /** @test */
    public function it_can_find_a_role_by_slug_and_tenant()
    {
        $tenant = Tenant::factory()->create();
        $role = Role::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Editor',
            'slug' => 'editor',
        ]);

        $foundRole = $this->roleService->findBySlugAndTenant('editor', $tenant->id);

        $this->assertNotNull($foundRole);
        $this->assertEquals($role->id, $foundRole->id);
        $this->assertEquals('Editor', $foundRole->name);
    }

    /** @test */
    public function it_returns_null_when_role_not_found_by_slug()
    {
        $tenant = Tenant::factory()->create();
        $role = $this->roleService->findBySlugAndTenant('non-existent-role', $tenant->id);

        $this->assertNull($role);
    }
}