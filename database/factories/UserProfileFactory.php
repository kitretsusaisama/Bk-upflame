<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Identity\Models\UserProfile;
use App\Domain\Identity\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Identity\Models\UserProfile>
 */
class UserProfileFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = UserProfile::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'avatar' => $this->faker->optional()->imageUrl(),
            'date_of_birth' => $this->faker->optional()->date(),
            'timezone' => $this->faker->timezone(),
            'locale' => $this->faker->locale(),
            'metadata' => [],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}