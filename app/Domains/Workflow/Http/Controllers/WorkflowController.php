<?php

namespace App\Domains\Workflow\Http\Controllers;

use App\Domains\Workflow\Services\WorkflowService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WorkflowController extends Controller
{
    protected $workflowService;

    public function __construct(WorkflowService $workflowService)
    {
        $this->workflowService = $workflowService;
    }

    public function index(Request $request)
    {
        $tenantId = app('tenant')->id;
        $workflows = $this->workflowService->getWorkflowsByTenant($tenantId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $workflows
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'definition_id' => 'required|string',
            'entity_type' => 'required|string|max:100',
            'entity_id' => 'required|string'
        ]);

        $validated['tenant_id'] = app('tenant')->id;
        
        $workflow = $this->workflowService->createWorkflow($validated);

        return response()->json([
            'status' => 'success',
            'data' => $workflow
        ], 201);
    }

    public function show($id)
    {
        $workflow = $this->workflowService->getWorkflowById($id);

        if (!$workflow) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'WORKFLOW_NOT_FOUND',
                    'message' => 'Workflow not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $workflow
        ]);
    }

    public function submitStep(Request $request, $id, $stepKey)
    {
        $validated = $request->validate([
            'data' => 'required|array'
        ]);

        try {
            $result = $this->workflowService->submitStep($id, $stepKey, $validated['data']);

            return response()->json([
                'status' => 'success',
                'data' => $result
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SUBMISSION_FAILED',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }

    public function approve(Request $request, $id)
    {
        $validated = $request->validate([
            'comments' => 'nullable|string'
        ]);

        try {
            $workflow = $this->workflowService->approveWorkflow($id);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'Workflow approved successfully',
                    'workflow' => $workflow
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'APPROVAL_FAILED',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }

    public function reject(Request $request, $id)
    {
        $validated = $request->validate([
            'reason' => 'required|string'
        ]);

        try {
            $workflow = $this->workflowService->rejectWorkflow($id, $validated['reason']);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'Workflow rejected',
                    'workflow' => $workflow
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'REJECTION_FAILED',
                    'message' => $e->getMessage()
                ]
            ], 400);
        }
    }
}
