<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Policies\TenantPolicy;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected TenantPolicy $tenantPolicy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantPolicy = new TenantPolicy();
    }

    /** @test */
    public function it_allows_super_admin_to_view_any_tenant()
    {
        $user = User::factory()->create(['role' => 'super_admin']);
        $tenant = Tenant::factory()->create();

        $result = $this->tenantPolicy->viewAny($user);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_allows_tenant_admin_to_view_their_own_tenant()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin'
        ]);

        $result = $this->tenantPolicy->view($user, $tenant);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_tenant_admin_from_viewing_other_tenants()
    {
        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant1->id,
            'role' => 'tenant_admin'
        ]);

        $result = $this->tenantPolicy->view($user, $tenant2);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_super_admin_to_create_tenant()
    {
        $user = User::factory()->create(['role' => 'super_admin']);

        $result = $this->tenantPolicy->create($user);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_non_super_admin_from_creating_tenant()
    {
        $user = User::factory()->create(['role' => 'tenant_admin']);

        $result = $this->tenantPolicy->create($user);

        $this->assertFalse($result);
    }
}