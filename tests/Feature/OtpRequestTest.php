<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Domains\Identity\Models\OtpRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtpRequestTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_an_otp_request()
    {
        $otpRequest = OtpRequest::create([
            'recipient' => 'test@example.com',
            'otp_hash' => bcrypt('123456'),
            'attempts' => 0,
            'used' => false,
            'expires_at' => now()->addMinutes(5),
            'ip' => '127.0.0.1',
            'user_agent' => 'Symfony'
        ]);

        $this->assertDatabaseHas('otp_requests', [
            'recipient' => 'test@example.com'
        ]);
    }
}