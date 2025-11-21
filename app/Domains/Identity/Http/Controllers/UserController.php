<?php

namespace App\Domains\Identity\Http\Controllers;

use App\Domains\Identity\Repositories\UserRepository;
use App\Domains\Access\Services\RoleService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserController extends Controller
{
    protected $userRepository;
    protected $roleService;

    public function __construct(
        UserRepository $userRepository,
        RoleService $roleService
    ) {
        $this->userRepository = $userRepository;
        $this->roleService = $roleService;
    }

    public function index(Request $request)
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenant = app('tenant');
        $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'TENANT_NOT_FOUND',
                    'message' => 'Tenant not found'
                ]
            ], 403);
        }
        
        $users = $this->userRepository->findByTenant($tenantId, $request->get('limit', 20));

        return response()->json([
            'status' => 'success',
            'data' => $users
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'password' => 'required|string|min:8',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20'
        ]);

        // Safely get tenant ID from the tenant binding or authenticated user
        $tenant = app('tenant');
        $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'TENANT_NOT_FOUND',
                    'message' => 'Tenant not found'
                ]
            ], 403);
        }
        
        $userData = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantId,
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'status' => 'pending'
        ];
        
        $user = $this->userRepository->create($userData);
        
        if (isset($validated['first_name']) || isset($validated['last_name']) || isset($validated['phone'])) {
            $user->profile()->create([
                'id' => Str::uuid()->toString(),
                'user_id' => $user->id,
                'tenant_id' => $tenantId,
                'first_name' => $validated['first_name'] ?? null,
                'last_name' => $validated['last_name'] ?? null,
                'phone' => $validated['phone'] ?? null
            ]);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user->load('profile')
        ], 201);
    }

    public function show($id)
    {
        $user = $this->userRepository->findById($id);

        if (!$user) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user->load(['profile', 'roles'])
        ]);
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'email' => 'sometimes|email',
            'status' => 'sometimes|in:active,inactive,banned,hold,pending,suspended',
            'first_name' => 'nullable|string|max:100',
            'last_name' => 'nullable|string|max:100',
            'phone' => 'nullable|string|max:20'
        ]);

        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found'
                ]
            ], 404);
        }
        
        $userUpdateData = [];
        if (isset($validated['email'])) $userUpdateData['email'] = $validated['email'];
        if (isset($validated['status'])) $userUpdateData['status'] = $validated['status'];
        
        if (!empty($userUpdateData)) {
            $user = $this->userRepository->update($id, $userUpdateData);
        }
        
        $profileUpdateData = [];
        if (isset($validated['first_name'])) $profileUpdateData['first_name'] = $validated['first_name'];
        if (isset($validated['last_name'])) $profileUpdateData['last_name'] = $validated['last_name'];
        if (isset($validated['phone'])) $profileUpdateData['phone'] = $validated['phone'];
        
        if (!empty($profileUpdateData) && $user->profile) {
            $user->profile->update($profileUpdateData);
        }

        return response()->json([
            'status' => 'success',
            'data' => $user->load(['profile', 'roles'])
        ]);
    }

    public function destroy($id)
    {
        $result = $this->userRepository->delete($id);

        if (!$result) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found'
                ]
            ], 404);
        }

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'User deleted successfully'
            ]
        ]);
    }

    public function assignRole(Request $request, $id)
    {
        $validated = $request->validate([
            'role_id' => 'required|string'
        ]);

        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found'
                ]
            ], 404);
        }
        
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenant = app('tenant');
        $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'TENANT_NOT_FOUND',
                    'message' => 'Tenant not found'
                ]
            ], 403);
        }
        
        $user->roles()->attach($validated['role_id'], [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $tenantId,
            'assigned_by' => $request->user()->id
        ]);

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Role assigned successfully'
            ]
        ]);
    }

    public function removeRole($id, $roleId)
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'USER_NOT_FOUND',
                    'message' => 'User not found'
                ]
            ], 404);
        }
        
        $user->roles()->detach($roleId);

        return response()->json([
            'status' => 'success',
            'data' => [
                'message' => 'Role removed successfully'
            ]
        ]);
    }
}