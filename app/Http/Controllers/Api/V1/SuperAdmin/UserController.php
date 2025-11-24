<?php

namespace App\Http\Controllers\Api\V1\SuperAdmin;

use App\Services\UserManagementService;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;

class UserController extends Controller
{
    protected $userManagementService;

    public function __construct(UserManagementService $userManagementService)
    {
        $this->userManagementService = $userManagementService;
    }

    public function index(Request $request)
    {
        try {
            $filters = [
                'search' => $request->get('search', ''),
                'role' => $request->get('role', ''),
                'status' => $request->get('status', '')
            ];
            
            $limit = $request->get('limit', 20);
            
            $users = $this->userManagementService->getUsers($filters, $limit);
            
            return response()->json([
                'status' => 'success',
                'data' => $users
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => 'An error occurred while fetching users'
                ]
            ], 500);
        }
    }

    public function store(Request $request)
    {
        try {
            $validated = $request->validate([
                'email' => 'required|email|unique:users,email',
                'password' => 'required|string|min:8',
                'first_name' => 'nullable|string|max:100',
                'last_name' => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:20',
                'tenant_id' => 'nullable|string|exists:tenants,id',
                'role' => 'nullable|string'
            ]);

            $user = $this->userManagementService->createUser($validated);

            return response()->json([
                'status' => 'success',
                'data' => $user
            ], 201);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'CREATE_FAILED',
                    'message' => 'Failed to create user: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function show($id)
    {
        try {
            // Use the repository directly since we don't need the service method
            $user = app(\App\Domains\Identity\Repositories\UserRepository::class)
                ->getModel()
                ->with(['profile', 'roles', 'tenant'])
                ->find($id);

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
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => 'An error occurred while fetching user'
                ]
            ], 500);
        }
    }

    public function update(Request $request, $id)
    {
        try {
            $validated = $request->validate([
                'email' => 'sometimes|email|unique:users,email,' . $id,
                'status' => 'sometimes|in:active,inactive,banned,hold,pending,suspended',
                'first_name' => 'nullable|string|max:100',
                'last_name' => 'nullable|string|max:100',
                'phone' => 'nullable|string|max:20',
                'tenant_id' => 'nullable|string|exists:tenants,id',
                'role' => 'nullable|string'
            ]);

            $user = $this->userManagementService->updateUser($id, $validated);

            return response()->json([
                'status' => 'success',
                'data' => $user
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'UPDATE_FAILED',
                    'message' => 'Failed to update user: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function destroy($id)
    {
        try {
            $result = $this->userManagementService->deleteUser($id);
            
            if (!$result) {
                return response()->json([
                    'status' => 'error',
                    'error' => [
                        'code' => 'DELETE_FAILED',
                        'message' => 'Failed to delete user'
                    ]
                ], 500);
            }

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'User deleted successfully'
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'DELETE_FAILED',
                    'message' => 'Failed to delete user: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function activate($id)
    {
        try {
            $user = $this->userManagementService->activateUser($id);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'User activated successfully',
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'ACTIVATE_FAILED',
                    'message' => 'Failed to activate user: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function deactivate($id)
    {
        try {
            $user = $this->userManagementService->deactivateUser($id);

            return response()->json([
                'status' => 'success',
                'data' => [
                    'message' => 'User deactivated successfully',
                    'user' => $user
                ]
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'DEACTIVATE_FAILED',
                    'message' => 'Failed to deactivate user: ' . $e->getMessage()
                ]
            ], 500);
        }
    }

    public function getRoles()
    {
        try {
            $roles = $this->userManagementService->getRoles();
            
            return response()->json([
                'status' => 'success',
                'data' => $roles
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => 'An error occurred while fetching roles'
                ]
            ], 500);
        }
    }

    public function getTenants()
    {
        try {
            $tenants = $this->userManagementService->getTenants();
            
            return response()->json([
                'status' => 'success',
                'data' => $tenants
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'error' => [
                    'code' => 'SERVER_ERROR',
                    'message' => 'An error occurred while fetching tenants'
                ]
            ], 500);
        }
    }
}