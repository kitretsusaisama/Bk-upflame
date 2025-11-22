<?php

namespace App\Http\Controllers\Api\V1\Role;

use App\Http\Controllers\Controller;
use App\Domain\Authorization\Models\Permission;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\JsonResponse
     */
    public function index(): JsonResponse
    {
        $permissions = Permission::paginate(15);
        
        return response()->json([
            'permissions' => $permissions
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'required|string|unique:permissions',
            'resource' => 'required|string',
            'action' => 'required|string',
            'description' => 'nullable|string',
            // Add other validation rules as needed
        ]);
        
        $permission = Permission::create($request->validated());
        
        return response()->json([
            'message' => 'Permission created successfully',
            'permission' => $permission
        ], 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domain\Authorization\Models\Permission  $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(Permission $permission): JsonResponse
    {
        return response()->json([
            'permission' => $permission
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domain\Authorization\Models\Permission  $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function update(Request $request, Permission $permission): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'slug' => 'sometimes|string|unique:permissions,slug,' . $permission->id,
            'resource' => 'sometimes|string',
            'action' => 'sometimes|string',
            'description' => 'nullable|string',
            // Add other validation rules as needed
        ]);
        
        $permission->update($request->validated());
        
        return response()->json([
            'message' => 'Permission updated successfully',
            'permission' => $permission
        ]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domain\Authorization\Models\Permission  $permission
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Permission $permission): JsonResponse
    {
        $permission->delete();
        
        return response()->json([
            'message' => 'Permission deleted successfully'
        ]);
    }
}