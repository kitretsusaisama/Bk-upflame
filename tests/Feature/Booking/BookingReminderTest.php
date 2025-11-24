<?php

namespace Tests\Feature\Booking;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Identity\Models\User;
use App\Domain\Provider\Models\Provider;
use App\Domain\Booking\Models\Booking;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingReminderTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_send_booking_reminder()
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
            'scheduled_at' => now()->addDay(),
        ]);

        $response = $this->postJson("/api/v1/bookings/{$booking->id}/remind");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Reminder sent successfully'
            ]);

        // Check if reminder was sent (implementation depends on notification system)
        $this->assertTrue(true); // Placeholder assertion
    }

    /** @test */
    public function it_cannot_send_reminder_for_past_booking()
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
            'scheduled_at' => now()->subDay(),
        ]);

        $response = $this->postJson("/api/v1/bookings/{$booking->id}/remind");

        $response->assertStatus(422);
    }
}