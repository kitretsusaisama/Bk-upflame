<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Tenant\Models\TenantSettings;
use App\Domain\Tenant\Models\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Tenant\Models\TenantSettings>
 */
class TenantSettingsFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = TenantSettings::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'key' => $this->faker->word(),
            'value' => $this->faker->sentence(),
            'type' => $this->faker->randomElement(['string', 'integer', 'boolean', 'json']),
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}