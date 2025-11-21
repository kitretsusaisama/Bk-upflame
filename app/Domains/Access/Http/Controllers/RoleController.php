<?php

namespace App\Domains\Access\Http\Controllers;

use App\Domains\Access\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class RoleController extends Controller
{
    protected $roleService;

    public function __construct(RoleService $roleService)
    {
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        $tenantId = app('tenant')->id;
        $roles = $this->roleService->getRolesByTenant($tenantId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $roles
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:100',
            'description' => 'nullable|string',
            'role_family' => 'required|in:Provider,Internal,Customer',
            'permissions' => 'nullable|array'
        ]);

        $validated['tenant_id'] = app('tenant')->id;
        
        $role = $this->roleService->createRole($validated);

        return response()->json([
            'status' => 'success',
            'data' => $role
        ], 201);
    }

    public function show($id)
    {
        $role = $this->roleService->getRoleById($id);

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'ROLE_NOT_FOUND',
                    'message' => 'Role not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'description' => 'nullable|string',
            'role_family' => 'sometimes|in:Provider,Internal,Customer',
            'permissions' => 'nullable|array'
        ]);

        $role = $this->roleService->updateRole($id, $validated);

        if (!$role) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'ROLE_NOT_FOUND',
                    'message' => 'Role not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $role
        ]);
    }

    public function destroy($id)
    {
        $result = $this->roleService->deleteRole($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'ROLE_NOT_FOUND',
                    'message' => 'Role not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Role deleted successfully'
            ]
        ]);
    }

    public function attachPermission(Request $request, $id)
    {
        $validated = $request->validate([
            'permission_id' => 'required|string'
        ]);

        $result = $this->roleService->attachPermission($id, $validated['permission_id']);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'OPERATION_FAILED',
                    'message' => 'Failed to attach permission'
                ]
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Permission attached successfully'
            ]
        ]);
    }

    public function detachPermission($id, $permissionId)
    {
        $result = $this->roleService->detachPermission($id, $permissionId);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'OPERATION_FAILED',
                    'message' => 'Failed to detach permission'
                ]
            ], 400);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Permission detached successfully'
            ]
        ]);
    }
}
