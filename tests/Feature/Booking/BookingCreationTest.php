<?php

namespace Tests\Feature\Booking;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Identity\Models\User;
use App\Domain\Provider\Models\Provider;
use App\Domain\Booking\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingCreationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_new_booking()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);
        $provider = Provider::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->postJson('/api/v1/bookings', [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'scheduled_at' => '2024-12-01 10:00:00',
            'duration' => 60,
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'scheduled_at',
                    'duration',
                ]
            ]);

        $this->assertDatabaseHas('bookings', [
            'user_id' => $user->id,
            'provider_id' => $provider->id,
        ]);
    }

    /** @test */
    public function it_cannot_create_booking_with_invalid_data()
    {
        $response = $this->postJson('/api/v1/bookings', [
            // Missing required fields
        ]);

        $response->assertStatus(422);
    }
}