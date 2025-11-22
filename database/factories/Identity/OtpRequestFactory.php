<?php

namespace Database\Factories\Identity;

use App\Domains\Identity\Models\OtpRequest;
use Illuminate\Database\Eloquent\Factories\Factory;

class OtpRequestFactory extends Factory
{
    protected $model = OtpRequest::class;

    public function definition()
    {
        return [
            'tenant_id' => null,
            'recipient' => $this->faker->email,
            'otp_hash' => bcrypt($this->faker->numerify('######')),
            'attempts' => 0,
            'used' => false,
            'expires_at' => now()->addMinutes(5),
            'ip' => $this->faker->ipv4,
            'user_agent' => $this->faker->userAgent
        ];
    }

    public function used()
    {
        return $this->state(function (array $attributes) {
            return [
                'used' => true,
            ];
        });
    }

    public function expired()
    {
        return $this->state(function (array $attributes) {
            return [
                'expires_at' => now()->subMinutes(5),
            ];
        });
    }
}