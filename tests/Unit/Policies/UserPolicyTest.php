<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Policies\UserPolicy;
use App\Domain\Identity\Models\User;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected UserPolicy $userPolicy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->userPolicy = new UserPolicy();
    }

    /** @test */
    public function it_allows_tenant_admin_to_view_users_in_their_tenant()
    {
        $tenant = Tenant::factory()->create();
        $adminUser = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin'
        ]);
        $regularUser = User::factory()->create(['tenant_id' => $tenant->id]);

        $result = $this->userPolicy->view($adminUser, $regularUser);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_tenant_admin_from_viewing_users_in_other_tenants()
    {
        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();
        $adminUser = User::factory()->create([
            'tenant_id' => $tenant1->id,
            'role' => 'tenant_admin'
        ]);
        $regularUser = User::factory()->create(['tenant_id' => $tenant2->id]);

        $result = $this->userPolicy->view($adminUser, $regularUser);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_tenant_admin_to_create_users_in_their_tenant()
    {
        $tenant = Tenant::factory()->create();
        $adminUser = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin'
        ]);

        $result = $this->userPolicy->create($adminUser);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_allows_user_to_view_their_own_profile()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);

        $result = $this->userPolicy->view($user, $user);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_allows_user_to_update_their_own_profile()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);

        $result = $this->userPolicy->update($user, $user);

        $this->assertTrue($result);
    }
}