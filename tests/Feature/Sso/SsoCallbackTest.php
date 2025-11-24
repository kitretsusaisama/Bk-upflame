<?php

namespace Tests\Feature\Sso;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\SSO\Models\SsoProvider;
use Illuminate\Foundation\Testing\RefreshDatabase;

class SsoCallbackTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_handle_sso_callback()
    {
        $tenant = Tenant::factory()->create();
        $provider = SsoProvider::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'google',
            'type' => 'oauth',
        ]);

        $response = $this->getJson("/api/v1/sso/callback/{$provider->name}");

        // This would typically redirect or return a response based on the SSO implementation
        // For now, we'll just check that it doesn't throw an error
        $response->assertStatus(200)
            ->assertJsonStructure([
                'message'
            ]);
    }

    /** @test */
    public function it_returns_error_for_invalid_sso_callback()
    {
        $response = $this->getJson('/api/v1/sso/callback/invalid-provider');

        $response->assertStatus(500);
    }
}