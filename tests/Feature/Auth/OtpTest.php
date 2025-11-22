<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Domain\Identity\Models\User;
use App\Domain\Identity\Models\OtpCode;
use Illuminate\Foundation\Testing\RefreshDatabase;

class OtpTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_generate_an_otp()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $response = $this->postJson('/api/v1/auth/otp/generate', [
            'email' => 'test@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'OTP sent successfully'
            ]);

        $this->assertDatabaseHas('otp_codes', [
            'user_id' => $user->id,
        ]);
    }

    /** @test */
    public function it_can_verify_an_otp()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
        ]);

        $otpCode = OtpCode::factory()->create([
            'user_id' => $user->id,
            'code' => '123456',
        ]);

        $response = $this->postJson('/api/v1/auth/otp/verify', [
            'email' => 'test@example.com',
            'otp' => '123456',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'user',
                'token'
            ]);
    }
}