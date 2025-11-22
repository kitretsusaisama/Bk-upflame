<?php

namespace Tests\Feature\Booking;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Identity\Models\User;
use App\Domain\Provider\Models\Provider;
use App\Domain\Booking\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingCancellationTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_cancel_a_booking()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);
        $provider = Provider::factory()->create([
            'tenant_id' => $tenant->id,
        ]);
        $booking = Booking::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'status' => 'confirmed',
        ]);

        $response = $this->deleteJson("/api/v1/bookings/{$booking->id}/cancel");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Booking cancelled successfully'
            ]);

        $this->assertDatabaseHas('bookings', [
            'id' => $booking->id,
            'status' => 'cancelled',
        ]);
    }

    /** @test */
    public function it_cannot_cancel_an_already_cancelled_booking()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);
        $provider = Provider::factory()->create([
            'tenant_id' => $tenant->id,
        ]);
        $booking = Booking::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'status' => 'cancelled',
        ]);

        $response = $this->deleteJson("/api/v1/bookings/{$booking->id}/cancel");

        $response->assertStatus(422);
    }
}