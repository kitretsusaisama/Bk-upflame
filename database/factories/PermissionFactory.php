<?php

namespace Database\Factories;

use App\Domains\Access\Models\Permission;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Permission>
 */
class PermissionFactory extends Factory
{
    protected $model = Permission::class;

    public function definition(): array
    {
        $resource = $this->faker->randomElement(['booking', 'provider', 'service', 'user', 'tenant']);
        $action = $this->faker->randomElement(['view', 'create', 'update', 'delete', 'list']);

        return [
            'name' => sprintf('%s.%s.%s', $resource, $action, Str::lower(Str::random(4))),
            'resource' => $resource,
            'action' => $action,
            'description' => $this->faker->sentence(),
        ];
    }
}


