<?php

namespace Tests\Feature\Tenant;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Tenant\Models\TenantSettings;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TenantSettingsTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_update_tenant_settings()
    {
        $tenant = Tenant::factory()->create();

        $response = $this->putJson("/api/v1/tenants/{$tenant->id}/settings", [
            'settings' => [
                'theme' => 'dark',
                'language' => 'en',
            ]
        ]);

        $response->assertStatus(200)
            ->assertJsonStructure([
                'message',
                'settings'
            ]);

        $this->assertDatabaseHas('tenant_settings', [
            'tenant_id' => $tenant->id,
            'key' => 'theme',
            'value' => '"dark"', // JSON encoded
        ]);
    }

    /** @test */
    public function it_can_retrieve_tenant_settings()
    {
        $tenant = Tenant::factory()->create();
        
        TenantSettings::factory()->create([
            'tenant_id' => $tenant->id,
            'key' => 'theme',
            'value' => '"light"',
        ]);

        $response = $this->getJson("/api/v1/tenants/{$tenant->id}/settings");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'settings'
            ]);
    }
}