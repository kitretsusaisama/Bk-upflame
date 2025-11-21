<?php

namespace Database\Factories;

use App\Domains\Booking\Models\Service;
use App\Domains\Identity\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Service>
 */
class ServiceFactory extends Factory
{
    protected $model = Service::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->unique()->words(3, true),
            'description' => $this->faker->sentence(),
            'category' => $this->faker->randomElement(['Consultation', 'Ceremony', 'Virtual']),
            'base_price' => $this->faker->randomFloat(2, 100, 5000),
            'currency' => 'USD',
            'duration_minutes' => $this->faker->numberBetween(30, 240),
            'is_active' => true,
        ];
    }
}


