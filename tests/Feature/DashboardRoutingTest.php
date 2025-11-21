<?php

namespace Tests\Feature;

use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use App\Domains\Provider\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class DashboardRoutingTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_is_routed_to_superadmin_dashboard(): void
    {
        $user = $this->createUserWithRole('Super Admin', 'Internal');

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('superadmin.dashboard'));
    }

    public function test_tenant_admin_is_routed_to_tenant_dashboard(): void
    {
        $user = $this->createUserWithRole('Tenant Admin', 'Internal');

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('tenantadmin.dashboard'));
    }

    public function test_provider_is_routed_to_provider_dashboard(): void
    {
        $user = $this->createUserWithRole('Provider', 'Provider');
        Provider::factory()->for($user, 'user')->for($user->tenant, 'tenant')->create([
            'status' => 'active',
        ]);

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('provider.dashboard'));
    }

    public function test_ops_user_is_routed_to_ops_dashboard(): void
    {
        $user = $this->createUserWithRole('Ops', 'Internal');

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('ops.dashboard'));
    }

    public function test_customer_defaults_to_customer_dashboard(): void
    {
        $user = $this->createUserWithRole('Customer', 'Customer');

        $this->actingAs($user)
            ->get('/')
            ->assertRedirect(route('customer.dashboard'));
    }

    private function createUserWithRole(string $roleName, string $roleFamily): User
    {
        $tenant = Tenant::factory()->create();

        $role = Role::factory()
            ->for($tenant, 'tenant')
            ->create([
                'name' => $roleName,
                'role_family' => $roleFamily,
                'is_system' => true,
            ]);

        $user = User::factory()
            ->for($tenant, 'tenant')
            ->create([
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


