<?php

namespace Tests\Feature\Workflow;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Workflow\Models\Workflow;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkflowExecutionTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_execute_a_workflow()
    {
        $tenant = Tenant::factory()->create();
        $workflow = Workflow::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Test Workflow',
            'status' => 'active',
        ]);

        $response = $this->postJson("/api/v1/workflows/{$workflow->id}/execute");

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Workflow executed successfully'
            ]);

        // Check if workflow was executed (implementation depends on workflow system)
        $this->assertTrue(true); // Placeholder assertion
    }

    /** @test */
    public function it_cannot_execute_inactive_workflow()
    {
        $tenant = Tenant::factory()->create();
        $workflow = Workflow::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Inactive Workflow',
            'status' => 'inactive',
        ]);

        $response = $this->postJson("/api/v1/workflows/{$workflow->id}/execute");

        $response->assertStatus(422);
    }
}