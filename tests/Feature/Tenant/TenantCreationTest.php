<?php

namespace Tests\Feature\Tenant;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_new_tenant()
    {
        $response = $this->postJson('/api/v1/tenants', [
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
            'domain' => 'test.example.com',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'name',
                    'slug',
                    'domain',
                ]
            ]);

        $this->assertDatabaseHas('tenants', [
            'name' => 'Test Tenant',
            'slug' => 'test-tenant',
        ]);
    }

    /** @test */
    public function it_cannot_create_tenant_with_duplicate_slug()
    {
        $tenant = Tenant::factory()->create([
            'slug' => 'existing-tenant',
        ]);

        $response = $this->postJson('/api/v1/tenants', [
            'name' => 'Another Tenant',
            'slug' => 'existing-tenant',
            'domain' => 'another.example.com',
        ]);

        $response->assertStatus(422);
    }
}