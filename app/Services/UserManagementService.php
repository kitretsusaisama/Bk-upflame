<?php

namespace App\Services;

use App\Domains\Identity\Repositories\UserRepository;
use App\Domains\Identity\Repositories\TenantRepository;
use App\Domains\Access\Repositories\RoleRepository;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserManagementService
{
    protected $userRepository;
    protected $tenantRepository;
    protected $roleRepository;

    public function __construct(
        UserRepository $userRepository,
        TenantRepository $tenantRepository,
        RoleRepository $roleRepository
    ) {
        $this->userRepository = $userRepository;
        $this->tenantRepository = $tenantRepository;
        $this->roleRepository = $roleRepository;
    }

    /**
     * Get paginated users with filters
     *
     * @param array $filters
     * @param int $limit
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */
    public function getUsers(array $filters = [], int $limit = 20)
    {
        $query = $this->userRepository->getModel()
            ->with(['profile', 'roles', 'tenant']);
            
        // Apply search filter
        if (!empty($filters['search'])) {
            $search = $filters['search'];
            $query->where(function($q) use ($search) {
                $q->where('email', 'like', '%' . $search . '%')
                  ->orWhereHas('profile', function($profileQuery) use ($search) {
                      $profileQuery->where('first_name', 'like', '%' . $search . '%')
                                   ->orWhere('last_name', 'like', '%' . $search . '%');
                  });
            });
        }
        
        // Apply role filter
        if (!empty($filters['role'])) {
            $query->whereHas('roles', function($roleQuery) use ($filters) {
                $roleQuery->where('name', $filters['role']);
            });
        }
        
        // Apply status filter
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }
        
        return $query->paginate($limit);
    }

    /**
     * Create a new user
     *
     * @param array $data
     * @return \App\Domains\Identity\Models\User
     */
    public function createUser(array $data)
    {
        $userData = [
            'id' => Str::uuid()->toString(),
            'tenant_id' => $data['tenant_id'] ?? null,
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
            'status' => 'active'
        ];
        
        $user = $this->userRepository->create($userData);
        
        // Create user profile
        if (isset($data['first_name']) || isset($data['last_name']) || isset($data['phone'])) {
            $user->profile()->create([
                'id' => Str::uuid()->toString(),
                'user_id' => $user->id,
                'tenant_id' => $data['tenant_id'] ?? null,
                'first_name' => $data['first_name'] ?? null,
                'last_name' => $data['last_name'] ?? null,
                'phone' => $data['phone'] ?? null
            ]);
        }
        
        // Assign role if provided
        if (isset($data['role']) && !empty($data['role'])) {
            $role = $this->roleRepository->getModel()
                ->where('name', $data['role'])
                ->first();
                
            if ($role) {
                $user->roles()->attach($role->id, [
                    'id' => Str::uuid()->toString(),
                    'tenant_id' => $data['tenant_id'] ?? null,
                    'assigned_by' => auth()->id()
                ]);
            }
        }

        return $user->load(['profile', 'roles', 'tenant']);
    }

    /**
     * Update an existing user
     *
     * @param string $id
     * @param array $data
     * @return \App\Domains\Identity\Models\User
     */
    public function updateUser(string $id, array $data)
    {
        $user = $this->userRepository->getModel()
            ->with(['profile', 'roles', 'tenant'])
            ->find($id);
            
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        $userUpdateData = [];
        if (isset($data['email'])) $userUpdateData['email'] = $data['email'];
        if (isset($data['status'])) $userUpdateData['status'] = $data['status'];
        if (isset($data['tenant_id'])) $userUpdateData['tenant_id'] = $data['tenant_id'];
        
        if (!empty($userUpdateData)) {
            $user->update($userUpdateData);
        }
        
        $profileUpdateData = [];
        if (isset($data['first_name'])) $profileUpdateData['first_name'] = $data['first_name'];
        if (isset($data['last_name'])) $profileUpdateData['last_name'] = $data['last_name'];
        if (isset($data['phone'])) $profileUpdateData['phone'] = $data['phone'];
        
        if (!empty($profileUpdateData)) {
            if ($user->profile) {
                $user->profile->update($profileUpdateData);
            } else {
                // Create profile if it doesn't exist
                $user->profile()->create([
                    'id' => Str::uuid()->toString(),
                    'user_id' => $user->id,
                    'tenant_id' => $data['tenant_id'] ?? $user->tenant_id,
                    'first_name' => $data['first_name'] ?? null,
                    'last_name' => $data['last_name'] ?? null,
                    'phone' => $data['phone'] ?? null
                ]);
            }
        }
        
        // Handle role assignment
        if (isset($data['role'])) {
            // Remove all existing roles
            $user->roles()->detach();
            
            // Assign new role if provided
            if (!empty($data['role'])) {
                $role = $this->roleRepository->getModel()
                    ->where('name', $data['role'])
                    ->first();
                    
                if ($role) {
                    $user->roles()->attach($role->id, [
                        'id' => Str::uuid()->toString(),
                        'tenant_id' => $data['tenant_id'] ?? $user->tenant_id,
                        'assigned_by' => auth()->id()
                    ]);
                }
            }
        }

        return $user->refresh()->load(['profile', 'roles', 'tenant']);
    }

    /**
     * Delete a user
     *
     * @param string $id
     * @return bool
     */
    public function deleteUser(string $id)
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        return $user->delete();
    }

    /**
     * Activate a user
     *
     * @param string $id
     * @return \App\Domains\Identity\Models\User
     */
    public function activateUser(string $id)
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        $user->update(['status' => 'active']);
        
        return $user->load(['profile', 'roles', 'tenant']);
    }

    /**
     * Deactivate a user
     *
     * @param string $id
     * @return \App\Domains\Identity\Models\User
     */
    public function deactivateUser(string $id)
    {
        $user = $this->userRepository->findById($id);
        
        if (!$user) {
            throw new \Exception('User not found');
        }
        
        $user->update(['status' => 'inactive']);
        
        return $user->load(['profile', 'roles', 'tenant']);
    }

    /**
     * Get all available roles
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getRoles()
    {
        return $this->roleRepository->getModel()->get();
    }

    /**
     * Get all available tenants
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function getTenants()
    {
        return $this->tenantRepository->getModel()->get();
    }
}