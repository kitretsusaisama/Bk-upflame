<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Identity\Models\OtpCode;
use App\Domain\Identity\Models\User;
use App\Domain\Tenant\Models\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Identity\Models\OtpCode>
 */
class OtpCodeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = OtpCode::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'user_id' => User::factory(),
            'code' => $this->faker->numerify('######'),
            'type' => $this->faker->randomElement(['login', 'verification', 'password_reset']),
            'expires_at' => now()->addMinutes(10),
            'verified_at' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}