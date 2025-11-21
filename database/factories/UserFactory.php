<?php

namespace Database\Factories;

use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password = null;

    protected $model = User::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'email' => $this->faker->unique()->safeEmail(),
            'password' => static::$password ??= Hash::make('password'),
            'status' => 'active',
            'primary_role_id' => null,
            'mfa_enabled' => false,
            'email_verified' => true,
            'phone_verified' => false,
            'failed_login_attempts' => 0,
            'remember_token' => Str::random(10),
        ];
    }

    public function suspended(): static
    {
        return $this->state(fn () => ['status' => 'suspended']);
    }

    public function unverified(): static
    {
        return $this->state(fn () => ['email_verified' => false]);
    }
}
