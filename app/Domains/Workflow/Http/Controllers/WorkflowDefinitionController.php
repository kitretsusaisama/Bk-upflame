<?php

namespace App\Domains\Workflow\Http\Controllers;

use App\Domains\Workflow\Services\WorkflowDefinitionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class WorkflowDefinitionController extends Controller
{
    protected $workflowDefinitionService;

    public function __construct(WorkflowDefinitionService $workflowDefinitionService)
    {
        $this->workflowDefinitionService = $workflowDefinitionService;
    }

    public function index(Request $request)
    {
        $tenantId = app('tenant')->id;
        $definitions = $this->workflowDefinitionService->getDefinitionsByTenant($tenantId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $definitions
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'role_family' => 'required|in:Provider,Internal,Customer',
            'steps' => 'required|array'
        ]);

        $validated['tenant_id'] = app('tenant')->id;
        
        $definition = $this->workflowDefinitionService->createDefinition($validated);

        return response()->json([
            'status' => 'success',
            'data' => $definition
        ], 201);
    }

    public function show($id)
    {
        $definition = $this->workflowDefinitionService->getDefinitionById($id);

        if (!$definition) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'DEFINITION_NOT_FOUND',
                    'message' => 'Workflow definition not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $definition
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'role_family' => 'sometimes|in:Provider,Internal,Customer',
            'steps' => 'sometimes|array',
            'is_active' => 'sometimes|boolean'
        ]);

        $definition = $this->workflowDefinitionService->updateDefinition($id, $validated);

        if (!$definition) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'DEFINITION_NOT_FOUND',
                    'message' => 'Workflow definition not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $definition
        ]);
    }

    public function destroy($id)
    {
        $result = $this->workflowDefinitionService->deleteDefinition($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'DEFINITION_NOT_FOUND',
                    'message' => 'Workflow definition not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Workflow definition deleted successfully'
            ]
        ]);
    }
}
