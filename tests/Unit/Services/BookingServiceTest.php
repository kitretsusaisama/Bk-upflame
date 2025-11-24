<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Domain\Booking\Services\BookingService;
use App\Domain\Booking\Models\Booking;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Provider\Models\Provider;
use App\Domain\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingServiceTest extends TestCase
{
    use RefreshDatabase;

    protected BookingService $bookingService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookingService = new BookingService();
    }

    /** @test */
    public function it_can_create_a_new_booking()
    {
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

        $booking = $this->bookingService->createBooking($bookingData);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals($user->id, $booking->user_id);
        $this->assertEquals($provider->id, $booking->provider_id);
        $this->assertEquals('pending', $booking->status);
        $this->assertDatabaseHas('bookings', ['user_id' => $user->id]);
    }

    /** @test */
    public function it_can_find_a_booking_by_id()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $provider = Provider::factory()->create(['tenant_id' => $tenant->id]);
        $booking = Booking::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'status' => 'confirmed',
        ]);

        $foundBooking = $this->bookingService->findById($booking->id);

        $this->assertNotNull($foundBooking);
        $this->assertEquals($booking->id, $foundBooking->id);
        $this->assertEquals('confirmed', $foundBooking->status);
    }

    /** @test */
    public function it_returns_null_when_booking_not_found_by_id()
    {
        $booking = $this->bookingService->findById(999999);

        $this->assertNull($booking);
    }
}