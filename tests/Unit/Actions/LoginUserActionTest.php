<?php

namespace Tests\Unit\Actions;

use Tests\TestCase;
use App\Domain\Identity\Actions\LoginUserAction;
use App\Domain\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;

class LoginUserActionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_login_a_user_with_valid_credentials()
    {
        $action = new LoginUserAction();
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $result = $action->execute([
            'email' => 'test@example.com',
            'password' => 'password123',
        ]);

        $this->assertTrue($result['success']);
        $this->assertArrayHasKey('user', $result);
        $this->assertArrayHasKey('token', $result);
        $this->assertEquals($user->id, $result['user']->id);
    }

    /** @test */
    public function it_fails_to_login_with_invalid_password()
    {
        $action = new LoginUserAction();
        
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password123'),
        ]);

        $result = $action->execute([
            'email' => 'test@example.com',
            'password' => 'wrongpassword',
        ]);

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credentials', $result['message']);
    }

    /** @test */
    public function it_fails_to_login_non_existent_user()
    {
        $action = new LoginUserAction();

        $result = $action->execute([
            'email' => 'nonexistent@example.com',
            'password' => 'password123',
        ]);

        $this->assertFalse($result['success']);
        $this->assertEquals('Invalid credentials', $result['message']);
    }
}