<?php

namespace Tests\Feature;

use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Str;
use Tests\TestCase;

class TenantMiddlewareTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();

        Route::middleware(['tenant.resolution', 'tenant.scope'])
            ->get('/__tenant-test-route', fn () => response()->json(['ok' => true]));
    }

    public function test_request_with_matching_tenant_header_succeeds(): void
    {
        $tenant = Tenant::factory()->create();
        $user = $this->createTenantUser($tenant);

        $this->actingAs($user)
            ->get('/__tenant-test-route', ['X-Tenant-ID' => $tenant->id])
            ->assertOk()
            ->assertJson(['ok' => true]);
    }

    public function test_request_with_other_tenant_header_is_blocked(): void
    {
        $tenant = Tenant::factory()->create();
        $otherTenant = Tenant::factory()->create();
        $user = $this->createTenantUser($tenant);

        $this->actingAs($user)
            ->get('/__tenant-test-route', ['X-Tenant-ID' => $otherTenant->id])
            ->assertStatus(403);
    }

    private function createTenantUser(Tenant $tenant): User
    {
        $role = Role::factory()->for($tenant, 'tenant')->create([
            'name' => 'Tenant Admin',
            'role_family' => 'Internal',
            'is_system' => true,
        ]);

        $user = User::factory()->for($tenant, 'tenant')->create(['status' => 'active']);

        $user->roles()->attach($role->id, [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenant->id,
            'assigned_by' => $user->id,
        ]);

        return $user->fresh('tenant', 'roles');
    }
}


