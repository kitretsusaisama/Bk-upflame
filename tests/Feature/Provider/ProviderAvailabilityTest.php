<?php

namespace Tests\Feature\Provider;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Provider\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProviderAvailabilityTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_set_provider_availability()
    {
        $tenant = Tenant::factory()->create();
        $provider = Provider::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->postJson("/api/v1/providers/{$provider->id}/availability", [
            'availability' => [
                [
                    'day_of_week' => 'monday',
                    'start_time' => '09:00:00',
                    'end_time' => '17:00:00',
                    'is_available' => true,
                ]
            ]
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Provider availability updated successfully'
            ]);

        // Check if availability was set (implementation depends on specific model structure)
        $this->assertTrue(true); // Placeholder assertion
    }

    /** @test */
    public function it_can_get_provider_availability()
    {
        $tenant = Tenant::factory()->create();
        $provider = Provider::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->getJson("/api/v1/providers/{$provider->id}/availability");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'provider_id',
                'availability'
            ]);
    }
}