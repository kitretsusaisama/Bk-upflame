<?php

namespace Database\Factories;

use App\Domains\Identity\Models\Tenant;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends Factory<Tenant>
 */
class TenantFactory extends Factory
{
    protected $model = Tenant::class;

    public function definition(): array
    {
        $name = $this->faker->company . ' ' . $this->faker->unique()->word();

        return [
            'name' => $name,
            'slug' => Str::slug($name) . '-' . Str::lower(Str::random(4)),
            'domain' => $this->faker->unique()->domainName(),
            'status' => 'active',
            'tier' => $this->faker->randomElement(['free', 'basic', 'premium']),
            'timezone' => $this->faker->timezone(),
            'locale' => 'en-US',
        ];
    }
}


