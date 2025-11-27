<?php

namespace Tests\Feature;

use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Laravel\Sanctum\Sanctum;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;

    protected Tenant $tenant;

    protected function setUp(): void
    {
        parent::setUp();

        $this->tenant = Tenant::create([
            'name' => 'Test Tenant',
            'domain' => 'test.local',
            'status' => 'active',
        ]);
    }

    /** @test */
    public function a_user_can_view_the_login_page()
    {
        $this->get('/login')
            ->assertStatus(200)
            ->assertViewIs('auth.login');
    }

    /** @test */
    public function a_user_can_view_the_register_page()
    {
        $this->get('/register')
            ->assertStatus(200)
            ->assertViewIs('auth.register');
    }

    /** @test */
    public function a_user_can_view_the_forgot_password_page()
    {
        $this->get('/forgot-password')
            ->assertStatus(200)
            ->assertViewIs('auth.forgot-password');
    }

    /** @test */
    public function a_user_can_register()
    {
        $response = $this->post('/register', [
            'first_name' => 'John',
            'last_name' => 'Doe',
            'email' => 'john@example.com',
            'phone' => '1234567890',
            'password' => 'password',
            'password_confirmation' => 'password',
        ]);

        $response->assertRedirect('/customer/dashboard');
        $this->assertDatabaseHas('users', ['email' => 'john@example.com']);
        $this->assertDatabaseHas('user_profiles', [
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);
    }

    /** @test */
    public function a_user_can_login_with_valid_credentials()
    {
        $user = $this->createUser();

        $response = $this->post('/login', [
            'email' => 'john@example.com',
            'password' => 'password',
        ]);

        $response->assertRedirect('/customer/dashboard');
        $this->assertAuthenticatedAs($user);
    }

    /** @test */
    public function a_user_cannot_login_with_invalid_credentials()
    {
        $this->post('/login', [
            'email' => 'nonexistent@example.com',
            'password' => 'wrong-password',
        ])->assertSessionHasErrors();

        $this->assertGuest();
    }

    /** @test */
    public function a_user_can_logout()
    {
        $user = $this->createUser();

        $this->actingAs($user)->post('/logout')
            ->assertRedirect('/login');

        $this->assertGuest();
    }

    /** @test */
    public function provider_role_users_are_redirected_to_their_dashboard()
    {
        $user = $this->createUserWithRole('Provider', 'Provider');

        $this->post('/login', [
            'email' => $user->email,
            'password' => 'password',
        ])->assertRedirect(route('provider.dashboard'));
    }

    /** @test */
    public function authenticated_users_can_request_an_sso_token()
    {
        $user = $this->createUserWithRole('Tenant Admin', 'Internal');

        $this->actingAs($user)
            ->getJson(route('sso.token'))
            ->assertOk()
            ->assertJsonStructure(['status', 'data' => ['token', 'redirect_to']]);
    }

    /** @test */
    public function api_clients_can_exchange_sanctum_tokens_for_web_sessions()
    {
        $user = $this->createUserWithRole('Customer', 'Customer');
        Sanctum::actingAs($user);

        $this->postJson('/api/v1/sso/exchange')
            ->assertOk()
            ->assertJsonStructure(['status', 'data' => ['token', 'redirect_to']]);
    }

    private function createUser(): User
    {
        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'email' => 'john@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
        ]);

        $user->profile()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => 'John',
            'last_name' => 'Doe',
        ]);

        return $user;
    }

    private function createUserWithRole(string $roleName, string $roleFamily): User
    {
        $role = Role::create([
            'tenant_id' => $this->tenant->id,
            'name' => $roleName,
            'description' => "{$roleName} role",
            'role_family' => $roleFamily,
            'is_system' => true,
        ]);

        $user = User::create([
            'tenant_id' => $this->tenant->id,
            'email' => Str::slug($roleName) . '@example.com',
            'password' => bcrypt('password'),
            'status' => 'active',
            'primary_role_id' => $role->id,
        ]);

        $user->profile()->create([
            'tenant_id' => $this->tenant->id,
            'first_name' => $roleName,
            'last_name' => 'User',
        ]);

        $user->roles()->attach($role->id, [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $this->tenant->id,
            'assigned_by' => $user->id,
        ]);

        return $user;
    }
}