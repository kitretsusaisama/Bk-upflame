<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginWithPasswordTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_login_with_valid_credentials()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'status' => 'active'
        ]);

        // Attempt login
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => 'success'
            ])
            ->assertJsonStructure([
                'data' => [
                    'access_token',
                    'token_type',
                    'expires_in',
                    'user'
                ]
            ]);
    }

    /** @test */
    public function it_fails_to_login_with_invalid_credentials()
    {
        // Create a user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123')
        ]);

        // Attempt login with wrong password
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'wrongpassword'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'Invalid email or password'
                ]
            ]);
    }

    /** @test */
    public function it_fails_to_login_with_nonexistent_user()
    {
        // Attempt login with non-existent user
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(401)
            ->assertJson([
                'status' => 'error',
                'error' => [
                    'code' => 'INVALID_CREDENTIALS',
                    'message' => 'Invalid email or password'
                ]
            ]);
    }

    /** @test */
    public function it_fails_to_login_inactive_user()
    {
        // Create an inactive user
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
            'status' => 'inactive'
        ]);

        // Attempt login
        $response = $this->postJson('/api/v1/auth/login', [
            'email' => 'test@example.com',
            'password' => 'password123'
        ]);

        $response->assertStatus(400)
            ->assertJson([
                'status' => 'error',
                'error' => [
                    'code' => 'AUTHENTICATION_ERROR',
                    'message' => 'Account is not active.'
                ]
            ]);
    }
}