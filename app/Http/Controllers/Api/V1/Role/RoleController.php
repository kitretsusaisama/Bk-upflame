<?php

namespace App\Http\Controllers\Api\V1\Role;

use App\Http\Controllers\Controller;
use App\Domains\Access\Models\Role;
use App\Http\Resources\RoleResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function index()
    {
        $roles = Role::paginate(15);
        
        return RoleResource::collection($roles);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \App\Http\Resources\RoleResource
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            // Add other validation rules as needed
        ]);
        
        $role = Role::create($data);
        
        return new RoleResource($role);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Domains\Access\Models\Role  $role
     * @return \App\Http\Resources\RoleResource
     */
    public function show(Role $role)
    {
        return new RoleResource($role);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Domains\Access\Models\Role  $role
     * @return \App\Http\Resources\RoleResource
     */
    public function update(Request $request, Role $role)
    {
        $updateData = $request->validate([
            'name' => 'sometimes|string|max:255',
            'description' => 'nullable|string',
            // Add other validation rules as needed
        ]);
        
        $role->update($updateData);
        
        return new RoleResource($role);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Domains\Access\Models\Role  $role
     * @return \Illuminate\Http\JsonResponse
     */
    public function destroy(Role $role)
    {
        $role->delete();
        
        return response()->json([
            'message' => 'Role deleted successfully'
        ]);
    }
}