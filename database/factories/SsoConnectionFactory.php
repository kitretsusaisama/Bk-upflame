<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\SSO\Models\SsoConnection;
use App\Domain\SSO\Models\SsoProvider;
use App\Domain\Identity\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\SSO\Models\SsoConnection>
 */
class SsoConnectionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SsoConnection::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'user_id' => User::factory(),
            'provider_id' => SsoProvider::factory(),
            'external_id' => $this->faker->uuid(),
            'external_email' => $this->faker->email(),
            'metadata' => [],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}