<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Domain\Notification\Models\NotificationTemplate;
use App\Domain\Tenant\Models\Tenant;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Domain\Notification\Models\NotificationTemplate>
 */
class NotificationTemplateFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = NotificationTemplate::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'tenant_id' => Tenant::factory(),
            'name' => $this->faker->word(),
            'type' => $this->faker->randomElement(['email', 'sms', 'push']),
            'subject' => $this->faker->optional()->sentence(),
            'body' => $this->faker->paragraph(),
            'variables' => [],
            'created_at' => now(),
            'updated_at' => now(),
        ];
    }
}