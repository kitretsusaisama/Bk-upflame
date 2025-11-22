<?php

namespace Tests\Unit\Actions;

use Tests\TestCase;
use App\Domain\Tenant\Actions\CreateTenantAction;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateTenantActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_tenant()
    {
        $action = new CreateTenantAction();
        
        $tenantData = [
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'domain' => 'test.example.com',
            'status' => 'active'
        ];

        $tenant = $action->execute($tenantData);

        $this->assertInstanceOf(Tenant::class, $tenant);
        $this->assertEquals('Test Tenant', $tenant->name);
        $this->assertEquals('test-tenant', $tenant->slug);
        $this->assertEquals('test.example.com', $tenant->domain);
        $this->assertDatabaseHas('tenants', ['slug' => 'test-tenant']);
    }

    /** @test */
    public function it_creates_tenant_with_default_status_if_not_provided()
    {
        $action = new CreateTenantAction();
        
        $tenantData = [
            'name' => 'Another Tenant',
            'slug' => 'another-tenant',
            'domain' => 'another.example.com'
        ];

        $tenant = $action->execute($tenantData);

        $this->assertInstanceOf(Tenant::class, $tenant);
        $this->assertEquals('pending', $tenant->status); // Assuming default status is 'pending'
    }
}