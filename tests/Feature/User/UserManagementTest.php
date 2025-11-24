<?php

namespace Tests\Feature\User;

use Tests\TestCase;
use App\Domain\Identity\Models\User;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class UserManagementTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_create_a_new_user()
    {
        $tenant = Tenant::factory()->create();

        $response = $this->postJson('/api/v1/users', [
            'tenant_id' => $tenant->id,
            'email' => 'newuser@example.com',
            'password' => 'password123',
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'email',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@example.com',
        ]);
    }

    /** @test */
    public function it_can_list_users()
    {
        $tenant = Tenant::factory()->create();
        User::factory()->count(3)->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->getJson('/api/v1/users');

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    '*' => ['id', 'email']
                ]
            ]);
    }

    /** @test */
    public function it_can_update_a_user()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
            'email' => 'old@example.com',
        ]);

        $response = $this->putJson("/api/v1/users/{$user->id}", [
            'email' => 'updated@example.com',
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'data' => [
                    'id',
                    'email',
                ]
            ]);

        $this->assertDatabaseHas('users', [
            'id' => $user->id,
            'email' => 'updated@example.com',
        ]);
    }

    /** @test */
    public function it_can_delete_a_user()
    {
        $tenant = Tenant::factory()->create();
        $user = User::factory()->create([
            'tenant_id' => $tenant->id,
        ]);

        $response = $this->deleteJson("/api/v1/users/{$user->id}");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'User deleted successfully'
            ]);

        $this->assertDatabaseMissing('users', [
            'id' => $user->id,
        ]);
    }
}