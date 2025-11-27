<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Models\User;
use App\Domains\Access\Models\Role;
use App\Domains\Identity\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    /**
     * Display a listing of users (cross-tenant)
     */
    public function index(Request $request)
    {
        if (!auth()->user()->hasPermission('platform.user.view')) {
            abort(403, 'Unauthorized action');
        }

        $query = User::with(['tenant', 'roles']);

        // Filter by tenant
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Filter by role
        if ($request->filled('role_id')) {
            $query->whereHas('roles', function ($q) use ($request) {
                $q->where('roles.id', $request->role_id);
            });
        }

        // Filter by status
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        // Search by email
        if ($request->filled('search')) {
            $query->where('email', 'like', '%' . $request->search . '%');
        }

        $users = $query->orderBy('created_at', 'desc')->paginate(15)->withQueryString();
        
        // For filters
        $tenants = Tenant::where('status', 'active')->get();
        $roles = Role::all();

        return view('admin.users.index', compact('users', 'tenants', 'roles'));
    }

    /**
     * Show the form for creating a new user
     */
    public function create()
    {
        if (!auth()->user()->hasPermission('platform.user.create')) {
            abort(403, 'Unauthorized action');
        }

        $tenants = Tenant::where('status', 'active')->get();
        $roles = Role::all();

        return view('admin.users.create', compact('tenants', 'roles'));
    }

    /**
     * Store a newly created user
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('platform.user.create')) {
            abort(403, 'Unauthorized action');
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $user = User::create([
                'tenant_id' => $validated['tenant_id'],
                'email' => $validated['email'],
                'password' => Hash::make($validated['password']),
                'status' => $validated['status'],
                'email_verified' => false,
            ]);

            // Attach roles with tenant context
            if (!empty($validated['roles'])) {
                $roleData = [];
                foreach ($validated['roles'] as $roleId) {
                    $roleData[$roleId] = [
                        'tenant_id' => $validated['tenant_id'],
                        'assigned_by' => auth()->id(),
                    ];
                }
                $user->roles()->attach($roleData);
            }

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', "User created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create user: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified user
     */
    public function show(User $user)
    {
        if (!auth()->user()->hasPermission('platform.user.view')) {
            abort(403, 'Unauthorized action');
        }

        $user->load(['tenant', 'roles', 'sessions' => function ($query) {
            $query->where(function ($q) {
                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
            })->orderBy('last_activity', 'desc');
        }]);

        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified user
     */
    public function edit(User $user)
    {
        if (!auth()->user()->hasPermission('platform.user.update')) {
            abort(403, 'Unauthorized action');
        }

        $tenants = Tenant::where('status', 'active')->get();
        $roles = Role::all();
        $userRoles = $user->roles->pluck('id')->toArray();

        return view('admin.users.edit', compact('user', 'tenants', 'roles', 'userRoles'));
    }

    /**
     * Update the specified user
     */
    public function update(Request $request, User $user)
    {
        if (!auth()->user()->hasPermission('platform.user.update')) {
            abort(403, 'Unauthorized action');
        }

        $validated = $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'status' => ['required', Rule::in(['active', 'inactive', 'suspended'])],
            'roles' => 'array',
            'roles.*' => 'exists:roles,id',
        ]);

        DB::beginTransaction();
        try {
            $updateData = [
                'tenant_id' => $validated['tenant_id'],
                'email' => $validated['email'],
                'status' => $validated['status'],
            ];

            if ($request->filled('password')) {
                $updateData['password'] = Hash::make($validated['password']);
            }

            $user->update($updateData);

            // Sync roles with tenant context
            $roleData = [];
            foreach ($validated['roles'] ?? [] as $roleId) {
                $roleData[$roleId] = [
                    'tenant_id' => $validated['tenant_id'],
                    'assigned_by' => auth()->id(),
                ];
            }
            $user->roles()->sync($roleData);

            DB::commit();

            return redirect()->route('admin.users.index')
                ->with('success', "User updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update user: ' . $e->getMessage());
        }
    }

    /**
     * Delete/soft delete the specified user
     */
    public function destroy(User $user)
    {
        if (!auth()->user()->hasPermission('platform.user.delete')) {
            abort(403, 'Unauthorized action');
        }

        // Prevent self-deletion
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot delete your own account');
        }

        $userEmail = $user->email;
        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', "User '{$userEmail}' deleted successfully.");
    }

    /**
     * Activate user
     */
    public function activate(User $user)
    {
        if (!auth()->user()->hasPermission('platform.user.activate')) {
            abort(403, 'Unauthorized action');
        }

        $user->update(['status' => 'active']);

        return back()->with('success', 'User activated successfully');
    }

    /**
     * Deactivate user
     */
    public function deactivate(User $user)
    {
        if (!auth()->user()->hasPermission('platform.user.activate')) {
            abort(403, 'Unauthorized action');
        }

        // Prevent self-deactivation
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Cannot deactivate your own account');
        }

        $user->update(['status' => 'inactive']);

        return back()->with('success', 'User deactivated successfully');
    }
}
