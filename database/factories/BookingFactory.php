<?php

namespace Database\Factories;

use App\Domains\Booking\Models\Booking;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Booking>
 */
class BookingFactory extends Factory
{
    protected $model = Booking::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'service_id' => null,
            'provider_id' => null,
            'booking_reference' => 'BK-' . now()->format('Ymd') . '-' . strtoupper(Str::random(5)),
            'status' => $this->faker->randomElement(['processing', 'confirmed', 'completed', 'cancelled']),
            'scheduled_at' => now()->addDays($this->faker->numberBetween(1, 30)),
            'duration_minutes' => $this->faker->numberBetween(30, 240),
            'amount' => $this->faker->randomFloat(2, 50, 2000),
            'currency' => 'USD',
            'metadata' => [],
        ];
    }
}


