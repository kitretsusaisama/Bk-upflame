<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Domains\Identity\Models\User;
use App\Domains\Identity\Models\OtpRequest;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RequestOtpTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_request_an_otp_for_existing_user()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com'
        ]);

        // Request OTP
        $response = $this->postJson('/api/v1/auth/request-otp', [
            'email' => 'test@example.com'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'OTP sent successfully',
                'user_exists' => true
            ]);

        // Verify OTP request was created
        $this->assertDatabaseHas('otp_requests', [
            'recipient' => 'test@example.com',
            'used' => false
        ]);
    }

    /** @test */
    public function it_can_request_an_otp_for_non_existing_user()
    {
        // Request OTP for non-existing user
        $response = $this->postJson('/api/v1/auth/request-otp', [
            'email' => 'nonexistent@example.com'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success',
                'message' => 'OTP sent successfully',
                'user_exists' => false
            ]);

        // Verify OTP request was created
        $this->assertDatabaseHas('otp_requests', [
            'recipient' => 'nonexistent@example.com',
            'used' => false
        ]);
    }

    /** @test */
    public function it_requires_email_to_request_otp()
    {
        $response = $this->postJson('/api/v1/auth/request-otp', []);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }
}