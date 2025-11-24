<?php

namespace Tests\Feature\Sso;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\SSO\Models\SsoProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SsoLoginTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_initiate_sso_login()
    {
        $tenant = Tenant::factory()->create();
        $provider = SsoProvider::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'google',
            'type' => 'oauth',
        ]);

        $response = $this->getJson("/api/v1/sso/login/{$provider->name}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'redirect_url'
            ]);
    }

    /** @test */
    public function it_returns_error_for_nonexistent_sso_provider()
    {
        $response = $this->getJson('/api/v1/sso/login/nonexistent');

        $response->assertStatus(422);
    }
}