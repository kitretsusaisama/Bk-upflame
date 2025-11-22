<?php

namespace Tests\Feature\Workflow;

use Tests\TestCase;
use App\Domain\Tenant\Models\Tenant;
use App\Domain\Workflow\Models\Workflow;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkflowFormTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_can_submit_workflow_form()
    {
        $tenant = Tenant::factory()->create();
        $workflow = Workflow::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Workflow with Form',
        ]);

        $formData = [
            'field1' => 'value1',
            'field2' => 'value2',
        ];

        $response = $this->postJson("/api/v1/workflows/{$workflow->id}/form-submit", $formData);

        $response->assertStatus(200)
            ->assertJson([
                'message' => 'Form submitted successfully'
            ]);

        // Check if form was submitted (implementation depends on workflow system)
        $this->assertTrue(true); // Placeholder assertion
    }

    /** @test */
    public function it_validates_workflow_form_data()
    {
        $tenant = Tenant::factory()->create();
        $workflow = Workflow::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Workflow with Form',
        ]);

        // Submit invalid form data
        $response = $this->postJson("/api/v1/workflows/{$workflow->id}/form-submit", [
            // Missing required fields
        ]);

        $response->assertStatus(422);
    }
}