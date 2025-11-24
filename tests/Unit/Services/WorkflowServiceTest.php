<?php

namespace Tests\Unit\Services;

use Tests\TestCase;
use App\Domain\Workflow\Services\WorkflowService;
use App\Domain\Workflow\Models\Workflow;
use App\Domain\Tenant\Models\Tenant;
use Illuminate\Foundation\Testing\RefreshDatabase;

class WorkflowServiceTest extends TestCase
{
    use RefreshDatabase;

    protected WorkflowService $workflowService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->workflowService = new WorkflowService();
    }

    /** @test */
    public function it_can_create_a_new_workflow()
    {
        $tenant = Tenant::factory()->create();
        
        $workflowData = [
            'tenant_id' => $tenant->id,
            'name' => 'User Onboarding',
            'description' => 'Workflow for onboarding new users',
            'status' => 'active',
            'config' => ['steps' => ['step1', 'step2']]
        ];

        $workflow = $this->workflowService->createWorkflow($workflowData);

        $this->assertInstanceOf(Workflow::class, $workflow);
        $this->assertEquals('User Onboarding', $workflow->name);
        $this->assertEquals('active', $workflow->status);
        $this->assertEquals($tenant->id, $workflow->tenant_id);
        $this->assertDatabaseHas('workflows', ['name' => 'User Onboarding']);
    }

    /** @test */
    public function it_can_find_a_workflow_by_id()
    {
        $tenant = Tenant::factory()->create();
        $workflow = Workflow::factory()->create([
            'tenant_id' => $tenant->id,
            'name' => 'Content Approval',
            'status' => 'draft',
        ]);

        $foundWorkflow = $this->workflowService->findById($workflow->id);

        $this->assertNotNull($foundWorkflow);
        $this->assertEquals($workflow->id, $foundWorkflow->id);
        $this->assertEquals('Content Approval', $foundWorkflow->name);
        $this->assertEquals('draft', $foundWorkflow->status);
    }

    /** @test */
    public function it_returns_null_when_workflow_not_found_by_id()
    {
        $workflow = $this->workflowService->findById(999999);

        $this->assertNull($workflow);
    }
}