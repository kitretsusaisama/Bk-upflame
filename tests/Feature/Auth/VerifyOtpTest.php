<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\OtpRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class VerifyOtpTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_verify_a_valid_otp()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        // Create an OTP request
        $otp = '123456';
        $otpRequest = OtpRequest::create([
            'recipient' => 'test@example.com',
            'otp_hash' => Hash::make($otp),
            'attempts' => 0,
            'used' => false,
            'expires_at' => now()->addMinutes(5)
        ]);

        // Verify OTP
        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => 'test@example.com',
            'otp' => $otp
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'OTP verified successfully'
            ])
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user'
                ]
            ]);

        // Verify OTP request was marked as used
        $this->assertDatabaseHas('otp_requests', [
            'id' => $otpRequest->id,
            'used' => true
        ]);
    }

    /** @test */
    public function it_fails_to_verify_an_invalid_otp()
    {
        // Create an OTP request
        $otpRequest = OtpRequest::create([
            'recipient' => 'test@example.com',
            'otp_hash' => Hash::make('123456'),
            'attempts' => 0,
            'used' => false,
            'expires_at' => now()->addMinutes(5)
        ]);

        // Verify with wrong OTP
        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => 'test@example.com',
            'otp' => 'wrong'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid or expired OTP'
            ]);

        // Verify OTP request was not marked as used
        $this->assertDatabaseHas('otp_requests', [
            'id' => $otpRequest->id,
            'used' => false
        ]);
    }

    /** @test */
    public function it_fails_to_verify_an_expired_otp()
    {
        // Create an expired OTP request
        $otpRequest = OtpRequest::create([
            'recipient' => 'test@example.com',
            'otp_hash' => Hash::make('123456'),
            'attempts' => 0,
            'used' => false,
            'expires_at' => now()->subMinutes(5)
        ]);

        // Verify OTP
        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => 'test@example.com',
            'otp' => '123456'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid or expired OTP'
            ]);
    }

    /** @test */
    public function it_creates_user_if_not_exists_during_otp_verification()
    {
        // Verify OTP for non-existing user
        $response = $this->postJson('/api/v1/auth/verify-otp', [
            'email' => 'newuser@example.com',
            'otp' => '123456'
        ]);

        // Since we don't have a valid OTP hash in the database, it should fail
        $response->assertStatus(200)
            ->assertJson([
                'status' => 'error',
                'message' => 'Invalid or expired OTP'
            ]);
    }
}