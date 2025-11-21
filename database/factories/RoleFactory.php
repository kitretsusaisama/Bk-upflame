<?php

namespace Database\Factories;

use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Role>
 */
class RoleFactory extends Factory
{
    protected $model = Role::class;

    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->unique()->jobTitle(),
            'description' => $this->faker->sentence(),
            'role_family' => $this->faker->randomElement(['Internal', 'Provider', 'Customer']),
            'is_system' => false,
        ];
    }
}


