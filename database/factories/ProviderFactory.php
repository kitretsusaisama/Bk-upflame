<?php

namespace Database\Factories;

use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use App\Domains\Provider\Models\Provider;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Provider>
 */
class ProviderFactory extends Factory
{
    protected $model = Provider::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'provider_type' => $this->faker->randomElement(['pandit', 'vendor', 'partner', 'astrologer']),
            'first_name' => $this->faker->firstName(),
            'last_name' => $this->faker->lastName(),
            'email' => $this->faker->unique()->safeEmail(),
            'phone' => $this->faker->phoneNumber(),
            'status' => $this->faker->randomElement(['pending', 'verified', 'active']),
            'profile_json' => [
                'experience' => $this->faker->numberBetween(1, 10) . ' years',
            ],
        ];
    }
}


