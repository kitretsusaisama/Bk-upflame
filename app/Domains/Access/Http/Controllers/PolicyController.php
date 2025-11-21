<?php

namespace App\Domains\Access\Http\Controllers;

use App\Domains\Access\Services\PolicyService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PolicyController extends Controller
{
    protected $policyService;

    public function __construct(PolicyService $policyService)
    {
        $this->policyService = $policyService;
    }

    public function index(Request $request)
    {
        $tenantId = app('tenant')->id;
        $policies = $this->policyService->getPoliciesByTenant($tenantId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $policies
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'target' => 'required|array',
            'target.resource' => 'required|string',
            'target.action' => 'required|string',
            'rules' => 'required|array',
            'priority' => 'nullable|integer'
        ]);

        $validated['tenant_id'] = app('tenant')->id;
        
        $policy = $this->policyService->createPolicy($validated);

        return response()->json([
            'status' => 'success',
            'data' => $policy
        ], 201);
    }

    public function show($id)
    {
        $policy = $this->policyService->getPolicyById($id);

        if (!$policy) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'POLICY_NOT_FOUND',
                    'message' => 'Policy not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $policy
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            'target' => 'sometimes|array',
            'rules' => 'sometimes|array',
            'is_enabled' => 'sometimes|boolean',
            'priority' => 'nullable|integer'
        ]);

        $policy = $this->policyService->updatePolicy($id, $validated);

        if (!$policy) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'POLICY_NOT_FOUND',
                    'message' => 'Policy not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $policy
        ]);
    }

    public function destroy($id)
    {
        $result = $this->policyService->deletePolicy($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'POLICY_NOT_FOUND',
                    'message' => 'Policy not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Policy deleted successfully'
            ]
        ]);
    }

    public function assign(Request $request, $id)
    {
        $validated = $request->validate([
            'assignee_type' => 'required|in:user,role,group',
            'assignee_id' => 'required|string'
        ]);

        $tenantId = app('tenant')->id;
        
        $assignment = $this->policyService->assignPolicy(
            $id,
            $validated['assignee_type'],
            $validated['assignee_id'],
            $tenantId
        );

        return response()->json([
            'status' => 'success',
            'data' => $assignment
        ], 201);
    }
}
