<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Provider\Models\ProviderProfile;
use App\Domain\Provider\Models\Provider;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Provider\Models\ProviderProfile>
 */
class ProviderProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = ProviderProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'provider_id' => Provider::factory(),
            'bio' => $this->faker->optional()->paragraph(),
            'specialties' => [],
            'years_experience' => $this->faker->numberBetween(1, 30),
            'metadata' => [],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}