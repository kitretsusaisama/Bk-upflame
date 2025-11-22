<?php

namespace Database\Factories\Tenant;

use App\Domains\Tenant\Models\TenantIdentityProvider;
use Illuminate\Database\Eloquent\Factories\Factory;

class TenantIdentityProviderFactory extends Factory
{
    protected $model = TenantIdentityProvider::class;

    public function definition()
    {
        return [
            'tenant_id' => null,
            'key' => $this->faker->slug,
            'display_name' => $this->faker->name,
            'config' => [
                'client_id' => $this->faker->uuid,
                'client_secret' => $this->faker->sha256,
                'redirect_uri' => $this->faker->url,
                'authorization_endpoint' => $this->faker->url,
                'token_endpoint' => $this->faker->url,
                'userinfo_endpoint' => $this->faker->url,
            ],
            'group_role_mapping' => [
                'admin_group' => 'admin',
                'user_group' => 'user'
            ],
            'enabled' => true
        ];
    }

    public function disabled()
    {
        return $this->state(function (array $attributes) {
            return [
                'enabled' => false,
            ];
        });
    }
}