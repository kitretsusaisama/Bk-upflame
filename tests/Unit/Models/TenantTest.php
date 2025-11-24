<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_tenant()
    {
        $tenant = Tenant::factory()->create([
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'domain' => 'test.example.com',
            'status' => 'active'
        ]);

        $this->assertInstanceOf(Tenant::class, $tenant);
        $this->assertEquals('Test Tenant', $tenant->name);
        $this->assertEquals('test-tenant', $tenant->slug);
        $this->assertEquals('test.example.com', $tenant->domain);
        $this->assertEquals('active', $tenant->status);
        $this->assertDatabaseHas('tenants', ['slug' => 'test-tenant']);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $tenant = new Tenant();
        
        $expectedFillable = ['name', 'slug', 'domain', 'status', 'settings'];
        $this->assertEquals($expectedFillable, $tenant->getFillable());
    }

    /** @test */
    public function it_has_correct_casts()
    {
        $tenant = new Tenant();
        
        $expectedCasts = [
            'settings' => 'array',
            'expires_at' => 'datetime',
        ];
        
        foreach ($expectedCasts as $key => $value) {
            $this->assertEquals($value, $tenant->getCasts()[$key]);
        }
    }
}