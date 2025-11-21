<?php

namespace App\Domains\Access\Http\Controllers;

use App\Domains\Access\Services\PermissionService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class PermissionController extends Controller
{
    protected $permissionService;

    public function __construct(PermissionService $permissionService)
    {
        $this->permissionService = $permissionService;
    }

    public function index(Request $request)
    {
        $permissions = $this->permissionService->getAllPermissions($request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $permissions
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'resource' => 'required|string|max:100',
            'action' => 'required|string|max:50',
            'description' => 'nullable|string'
        ]);

        $permission = $this->permissionService->createPermission($validated);

        return response()->json([
            'status' => 'success',
            'data' => $permission
        ], 201);
    }

    public function show($id)
    {
        $permission = $this->permissionService->getPermissionById($id);

        if (!$permission) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'PERMISSION_NOT_FOUND',
                    'message' => 'Permission not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $permission
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:100',
            'resource' => 'sometimes|string|max:100',
            'action' => 'sometimes|string|max:50',
            'description' => 'nullable|string'
        ]);

        $permission = $this->permissionService->updatePermission($id, $validated);

        if (!$permission) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'PERMISSION_NOT_FOUND',
                    'message' => 'Permission not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $permission
        ]);
    }

    public function destroy($id)
    {
        $result = $this->permissionService->deletePermission($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'PERMISSION_NOT_FOUND',
                    'message' => 'Permission not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Permission deleted successfully'
            ]
        ]);
    }
}
