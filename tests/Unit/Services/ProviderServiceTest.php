<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Domain\Provider\Services\ProviderService;
use App\Domain\Provider\Models\Provider;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class ProviderServiceTest extends TestCase
{
    use RefreshDatabase;

    protected ProviderService $providerService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->providerService = new ProviderService();
    }

    /** @test */
    public function it_can_create_a_new_provider()
    {
        $tenant = Tenant::factory()->create();
        
        $providerData = [
            'tenant_id' => $tenant->id,
            'name' => 'Dr. John Smith',
            'email' => 'john@example.com',
            'phone' => '+1234567890',
            'specialty' => 'Cardiology',
            'status' => 'active'
        ];

        $provider = $this->providerService->createProvider($providerData);

        $this->assertInstanceOf(Provider::class, $provider);
        $this->assertEquals('Dr. John Smith', $provider->name);
        $this->assertEquals('john@example.com', $provider->email);
        $this->assertEquals($tenant->id, $provider->tenant_id);
        $this->assertDatabaseHas('providers', ['email' => 'john@example.com']);
    }

    /** @test */
    public function it_can_find_a_provider_by_id()
    {
        $tenant = Tenant::factory()->create();
        $provider = Provider::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Dr. Jane Doe',
            'email' => 'jane@example.com',
        ]);

        $foundProvider = $this->providerService->findById($provider->id);

        $this->assertNotNull($foundProvider);
        $this->assertEquals($provider->id, $foundProvider->id);
        $this->assertEquals('Dr. Jane Doe', $foundProvider->name);
    }

    /** @test */
    public function it_returns_null_when_provider_not_found_by_id()
    {
        $provider = $this->providerService->findById(999999);

        $this->assertNull($provider);
    }
}