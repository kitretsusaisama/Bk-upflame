<?php

namespace Tests\Unit\Models;

use Tests\TestCase;
use App\Domain\Booking\Models\Booking;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Provider\Models\Provider;
use App\Domain\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_booking()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $provider = Provider::factory()->create(['tenant_id' => $tenant->id]);
        
        $booking = Booking::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'provider_id' => $provider->id,
            'scheduled_at' => now()->addDay(),
            'duration' => 60,
            'notes' => 'Regular checkup',
            'status' => 'pending'
        ]);

        $this->assertInstanceOf(Booking::class, $booking);
        $this->assertEquals($user->id, $booking->user_id);
        $this->assertEquals($provider->id, $booking->provider_id);
        $this->assertEquals('pending', $booking->status);
        $this->assertDatabaseHas('bookings', ['user_id' => $user->id]);
    }

    /** @test */
    public function it_has_correct_fillable_attributes()
    {
        $booking = new Booking();
        
        $expectedFillable = [
            'tenant_id', 'user_id', 'provider_id', 'uuid',
            'status', 'scheduled_at', 'duration', 'notes'
        ];
        $this->assertEquals($expectedFillable, $booking->getFillable());
    }

    /** @test */
    public function it_belongs_to_a_tenant()
    {
        $tenant = Tenant::factory()->create();
        $booking = Booking::factory()->create(['tenant_id' => $tenant->id]);

        $this->assertInstanceOf(Tenant::class, $booking->tenant);
        $this->assertEquals($tenant->id, $booking->tenant->id);
    }

    /** @test */
    public function it_belongs_to_a_user()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $booking = Booking::factory()->create(['user_id' => $user->id]);

        $this->assertInstanceOf(User::class, $booking->user);
        $this->assertEquals($user->id, $booking->user->id);
    }

    /** @test */
    public function it_belongs_to_a_provider()
    {
        $tenant = Tenant::factory()->create();
        $provider = Provider::factory()->create(['tenant_id' => $tenant->id]);
        $booking = Booking::factory()->create(['provider_id' => $provider->id]);

        $this->assertInstanceOf(Provider::class, $booking->provider);
        $this->assertEquals($provider->id, $booking->provider->id);
    }
}