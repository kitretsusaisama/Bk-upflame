<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Domain\Identity\Services\AuthenticationService;
use App\Domain\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class AuthenticationServiceTest extends TestCase
{
    use RefreshDatabase;

    protected AuthenticationService $authService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->authService = new AuthenticationService();
    }

    /** @test */
    public function it_can_authenticate_a_user_with_valid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $result = $this->authService->login([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->id, $result['user']->id);
    }

    /** @test */
    public function it_fails_to_authenticate_with_invalid_credentials()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $result = $this->authService->login([
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credentials', $result['message']);
    }

    /** @test */
    public function it_fails_to_authenticate_non_existent_user()
    {
        $result = $this->authService->login([
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credentials', $result['message']);
    }
}