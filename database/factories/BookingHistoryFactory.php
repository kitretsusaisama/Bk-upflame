<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Booking\Models\BookingHistory;
use App\Domain\Booking\Models\Booking;
use App\Domain\Identity\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Booking\Models\BookingHistory>
 */
class BookingHistoryFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = BookingHistory::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'booking_id' => Booking::factory(),
            'status' => $this->faker->randomElement(['pending', 'confirmed', 'cancelled', 'completed']),
            'changed_by' => User::factory(),
            'reason' => $this->faker->optional()->sentence(),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}