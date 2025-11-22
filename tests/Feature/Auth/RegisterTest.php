<?php

namespace Tests\Feature\Auth;

use Tests\TestCase;
use App\Domain\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;

class RegisterTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_register_a_new_user()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'email' => 'newuser@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'user'
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com'
        ]);
    }

    /** @test */
    public function it_cannot_register_with_invalid_data()
    {
        $response = $this->postJson('/api/v1/auth/register', [
            'email' => 'invalid-email',
            'password' => 'short',
        ]);

        $response->assertStatus(422);
    }
}