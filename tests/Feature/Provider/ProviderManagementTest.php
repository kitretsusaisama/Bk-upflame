<?php

namespace Tests\Feature\Provider;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Provider\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProviderManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_new_provider()
    {
        $tenant = Tenant::factory()->create();

        $response = $this->postJson('/api/v1/providers', [
            'tenant_id' => $tenant->id,
            'type' => 'doctor',
            'status' => 'active',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                    'status',
                ]
            ]);

        $this->assertDatabaseHas('providers', [
            'type' => 'doctor',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function it_can_list_providers()
    {
        $tenant = Tenant::factory()->create();
        Provider::factory()->count(3)->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->getJson('/api/v1/providers');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'type', 'status']
                ]
            ]);
    }

    /** @test */
    public function it_can_update_a_provider()
    {
        $tenant = Tenant::factory()->create();
        $provider = Provider::factory()->create([
            'tenant_id' => $tenant->id,
            'type' => 'doctor',
        ]);

        $response = $this->putJson("/api/v1/providers/{$provider->id}", [
            'type' => 'lawyer',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'type',
                ]
            ]);

        $this->assertDatabaseHas('providers', [
            'id' => $provider->id,
            'type' => 'lawyer',
        ]);
    }

    /** @test */
    public function it_can_delete_a_provider()
    {
        $tenant = Tenant::factory()->create();
        $provider = Provider::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->deleteJson("/api/v1/providers/{$provider->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Provider deleted successfully'
            ]);

        $this->assertDatabaseMissing('providers', [
            'id' => $provider->id,
        ]);
    }
}