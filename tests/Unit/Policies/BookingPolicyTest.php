<?php

namespace Tests\Unit\Policies;

use Tests\TestCase;
use App\Policies\BookingPolicy;
use App\Domain\Booking\Models\Booking;
use App\Domain\Identity\Models\User;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Provider\Models\Provider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class BookingPolicyTest extends TestCase
{
    use RefreshDatabase;

    protected BookingPolicy $bookingPolicy;

    protected function setUp(): void
    {
        parent::setUp();
        $this->bookingPolicy = new BookingPolicy();
    }

    /** @test */
    public function it_allows_tenant_admin_to_view_bookings_in_their_tenant()
    {
        $tenant = Tenant::factory()->create();
        $adminUser = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin'
        ]);
        $provider = Provider::factory()->create(['tenant_id' => $tenant->id]);
        $booking = Booking::factory()->create([
            'tenant_id' => $tenant->id,
            'provider_id' => $provider->id
        ]);

        $result = $this->bookingPolicy->view($adminUser, $booking);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_denies_tenant_admin_from_viewing_bookings_in_other_tenants()
    {
        $tenant1 = Tenant::factory()->create();
        $tenant2 = Tenant::factory()->create();
        $adminUser = User::factory()->create([
            'tenant_id' => $tenant1->id,
            'role' => 'tenant_admin'
        ]);
        $provider = Provider::factory()->create(['tenant_id' => $tenant2->id]);
        $booking = Booking::factory()->create([
            'tenant_id' => $tenant2->id,
            'provider_id' => $provider->id
        ]);

        $result = $this->bookingPolicy->view($adminUser, $booking);

        $this->assertFalse($result);
    }

    /** @test */
    public function it_allows_provider_to_view_their_own_bookings()
    {
        $tenant = Tenant::factory()->create();
        $providerUser = User::factory()->create(['tenant_id' => $tenant->id]);
        $provider = Provider::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $providerUser->id
        ]);
        $booking = Booking::factory()->create([
            'tenant_id' => $tenant->id,
            'provider_id' => $provider->id
        ]);

        $result = $this->bookingPolicy->view($providerUser, $booking);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_allows_user_to_view_their_own_bookings()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create(['tenant_id' => $tenant->id]);
        $provider = Provider::factory()->create(['tenant_id' => $tenant->id]);
        $booking = Booking::factory()->create([
            'tenant_id' => $tenant->id,
            'user_id' => $user->id,
            'provider_id' => $provider->id
        ]);

        $result = $this->bookingPolicy->view($user, $booking);

        $this->assertTrue($result);
    }

    /** @test */
    public function it_allows_tenant_admin_to_create_bookings()
    {
        $tenant = Tenant::factory()->create();
        $adminUser = User::factory()->create([
            'tenant_id' => $tenant->id,
            'role' => 'tenant_admin'
        ]);

        $result = $this->bookingPolicy->create($adminUser);

        $this->assertTrue($result);
    }
}