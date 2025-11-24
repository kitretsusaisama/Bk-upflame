<?php

namespace Tests\Unit\Actions;

use Tests\TestCase;
use App\Domain\Booking\Actions\CreateBookingAction;
use App\Domain\Booking\Models\Booking;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Provider\Models\Provider;
use App\Domain\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class CreateBookingActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_booking()
    {
        $action = new CreateBookingAction();
        
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $provider = Provider::factory()->create(['tenant_id' => $tenant->id]);

        $bookingData = [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'scheduled_at' => now()->addDay(),
            'duration' => 60,
            'notes' => 'Regular checkup',
            'status' => 'pending'
        ];

        $booking = $action->execute($bookingData);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals($user->id, $booking->user_id);
        $this->assertEquals($provider->id, $booking->provider_id);
        $this->assertEquals('pending', $booking->status);
        $this->assertDatabaseHas('bookings', ['user_id' => $user->id]);
    }

    /** @test */
    public function it_creates_booking_with_default_status_if_not_provided()
    {
        $action = new CreateBookingAction();
        
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $provider = Provider::factory()->create(['tenant_id' => $tenant->id]);

        $bookingData = [
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'scheduled_at' => now()->addDay(),
            'duration' => 30,
            'notes' => 'Quick consultation'
        ];

        $booking = $action->execute($bookingData);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals('pending', $booking->status); // Assuming default status is 'pending'
    }
}