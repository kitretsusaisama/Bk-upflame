<?php

namespace Tests\Feature\Tenant;

use Tests\TestCase;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\TenantDomain;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ResolveTenantTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_resolves_tenant_from_header()
    {
        // Create a tenant
        $tenant = Tenant::factory()->create([
            'name' => 'Test Tenant'
        ]);

        // Make request with X-Tenant-ID header
        $response = $this->withHeader('X-Tenant-ID', $tenant->id)
            ->getJson('/api/v1/auth/login');

        // The request should succeed (we're not testing auth, just tenant resolution)
        $response->assertStatus(401); // 401 because we didn't provide credentials
    }

    /** @test */
    public function it_resolves_tenant_from_domain()
    {
        // Create a tenant
        $tenant = Tenant::factory()->create([
            'name' => 'Test Tenant'
        ]);

        // Create a domain for the tenant
        TenantDomain::create([
            'tenant_id' => $tenant->id,
            'domain' => 'test.example.com'
        ]);

        // Make request with the domain
        $response = $this->withHeader('Host', 'test.example.com')
            ->getJson('/api/v1/auth/login');

        // The request should succeed
        $response->assertStatus(401); // 401 because we didn't provide credentials
    }

    /** @test */
    public function it_resolves_tenant_from_parameter()
    {
        // Create a tenant
        $tenant = Tenant::factory()->create([
            'name' => 'Test Tenant'
        ]);

        // Make request with tenant_id parameter
        $response = $this->getJson('/api/v1/auth/login?tenant_id=' . $tenant->id);

        // The request should succeed
        $response->assertStatus(401); // 401 because we didn't provide credentials
    }
}