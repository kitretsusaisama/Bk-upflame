<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Domain\Tenant\Services\TenantService;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantServiceTest extends TestCase
{
    use RefreshDatabase;

    protected TenantService $tenantService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->tenantService = new TenantService();
    }

    /** @test */
    public function it_can_create_a_new_tenant()
    {
        $tenantData = [
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'domain' => 'test.example.com',
            'status' => 'active'
        ];

        $tenant = $this->tenantService->createTenant($tenantData);

        $this->assertInstanceOf(Tenant::class, $tenant);
        $this->assertEquals('Test Tenant', $tenant->name);
        $this->assertEquals('test-tenant', $tenant->slug);
        $this->assertEquals('test.example.com', $tenant->domain);
        $this->assertDatabaseHas('tenants', ['slug' => 'test-tenant']);
    }

    /** @test */
    public function it_can_find_a_tenant_by_slug()
    {
        $tenant = Tenant::factory()->create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'domain' => 'test.example.com',
        ]);

        $foundTenant = $this->tenantService->findBySlug('test-tenant');

        $this->assertNotNull($foundTenant);
        $this->assertEquals($tenant->id, $foundTenant->id);
        $this->assertEquals('Test Tenant', $foundTenant->name);
    }

    /** @test */
    public function it_returns_null_when_tenant_not_found_by_slug()
    {
        $tenant = $this->tenantService->findBySlug('non-existent-tenant');

        $this->assertNull($tenant);
    }
}