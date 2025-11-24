<?php

namespace App\Http\Controllers\Api\V1\Workflow;

use App\Http\Controllers\Controller;
use App\Domains\Workflow\Models\WorkflowInstance;
use App\Domains\Workflow\Models\Workflow;
use App\Domains\Workflow\Models\WorkflowStepInstance;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class WorkflowInstanceController extends Controller
{
    /**
     * Start a new workflow instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $workflowId
     * @return \Illuminate\Http\JsonResponse
     */
    public function start(Request $request, int $workflowId): JsonResponse
    {
        $request->validate([
            'entity_type' => 'required|string',
            'entity_id' => 'required|string',
            // Add validation rules for workflow instance data
        ]);
        
        // Check if workflow exists and belongs to the tenant
        $workflow = Workflow::where('id', $workflowId)
            ->where('tenant_id', $request->user()->tenant_id)
            ->first();
        
        if (!$workflow) {
            return response()->json(['message' => 'Workflow not found'], 404);
        }
        
        // Create a new WorkflowInstance record
        $instance = WorkflowInstance::create([
            'tenant_id' => $request->user()->tenant_id,
            'workflow_id' => $workflowId,
            'user_id' => $request->user()->id,
            'entity_type' => $request->input('entity_type'),
            'entity_id' => $request->input('entity_id'),
            'status' => 'active',
            'started_at' => now(),
        ]);
        
        return response()->json([
            'message' => 'Workflow instance started successfully',
            'instance_id' => $instance->id
        ], 201);
    }

    /**
     * Execute a step in the workflow instance.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $instanceId
     * @return \Illuminate\Http\JsonResponse
     */
    public function execute(Request $request, int $instanceId): JsonResponse
    {
        $data = $request->validate([
            'step_id' => 'required|integer',
            // Add other validation rules as needed
        ]);
        
        // Check if instance exists and belongs to the tenant
        $instance = WorkflowInstance::where('id', $instanceId)
            ->where('tenant_id', $request->user()->tenant_id)
            ->first();
        
        if (!$instance) {
            return response()->json(['message' => 'Workflow instance not found'], 404);
        }
        
        // Create or update the WorkflowStepInstance record
        $stepInstance = WorkflowStepInstance::updateOrCreate(
            [
                'instance_id' => $instanceId,
                'step_id' => $data['step_id'],
            ],
            [
                'status' => 'completed',
                'completed_at' => now(),
            ]
        );
        
        return response()->json([
            'message' => 'Workflow step executed successfully',
            'step_instance_id' => $stepInstance->id
        ]);
    }
}