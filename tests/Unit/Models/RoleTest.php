<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Domain\Authorization\Models\Role;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RoleTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_role()
    {
        $tenant = Tenant::factory()->create();
        
        $role = Role::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Administrator',
            'slug' => 'admin',
            'description' => 'System administrator with full access',
            'is_system' => false
        ]);

        $this->assertInstanceOf(Role::class, $role);
        $this->assertEquals('Administrator', $role->name);
        $this->assertEquals('admin', $role->slug);
        $this->assertEquals($tenant->id, $role->tenant_id);
        $this->assertFalse($role->is_system);
        $this->assertDatabaseHas('roles', ['slug' => 'admin']);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $role = new Role();
        
        $expectedFillable = [
            'tenant_id', 'name', 'slug', 'description', 'is_system'
        ];
        $this->assertEquals($expectedFillable, $role->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_tenant()
    {
        $tenant = Tenant::factory()->create();
        $role = Role::factory()->create(['tenant_id' => $tenant->id]);

        $this->assertInstanceOf(Tenant::class, $role->tenant);
        $this->assertEquals($tenant->id, $role->tenant->id);
    }
}