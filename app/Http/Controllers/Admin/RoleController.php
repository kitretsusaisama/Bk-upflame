<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Access\Models\Role;
use App\Domains\Access\Models\Permission;
use App\Domains\Identity\Models\Tenant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    /**
     * Display a listing of roles (cross-tenant)
     */
    public function index(Request $request)
    {
        // Ensure user has platform.role.view permission
        if (!auth()->user()->hasPermission('platform.role.view')) {
            abort(403, 'Unauthorized action');
        }

        $query = Role::with(['tenant', 'permissions'])
            ->withCount('users');

        // Filter by tenant
        if ($request->filled('tenant_id')) {
            $query->where('tenant_id', $request->tenant_id);
        }

        // Search by name
        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        // Order by priority (lower = higher priority)
        $query->orderBy('priority')->orderBy('name');

        $roles = $query->paginate(15)->withQueryString();
        $tenants = Tenant::where('status', 'active')->get();

        return view('admin.roles.index', compact('roles', 'tenants'));
    }

    /**
     * Show the form for creating a new role
     */
    public function create()
    {
        if (!auth()->user()->hasPermission('platform.role.create')) {
            abort(403, 'Unauthorized action');
        }

        $tenants = Tenant::where('status', 'active')->get();
        $permissions = Permission::all()->groupBy('resource');

        return view('admin.roles.create', compact('tenants', 'permissions'));
    }

    /**
     * Store a newly created role
     */
    public function store(Request $request)
    {
        if (!auth()->user()->hasPermission('platform.role.create')) {
            abort(403, 'Unauthorized action');
        }

        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($request) {
                    return $query->where('tenant_id', $request->tenant_id);
                }),
            ],
            'description' => 'nullable|string|max:500',
            'priority' => 'required|integer|min:1|max:100',
            'is_system' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            $role = Role::create([
                'tenant_id' => $validated['tenant_id'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'priority' => $validated['priority'],
                'is_system' => $validated['is_system'] ?? false,
            ]);

            // Attach permissions
            if (!empty($validated['permissions'])) {
                $role->permissions()->sync($validated['permissions']);
            }

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', "Role '{$role->name}' created successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to create role: ' . $e->getMessage());
        }
    }

    /**
     * Display the specified role
     */
    public function show(Role $role)
    {
        if (!auth()->user()->hasPermission('platform.role.view')) {
            abort(403, 'Unauthorized action');
        }

        $role->load(['tenant', 'permissions', 'users.tenant']);
        $permissions = $role->permissions->groupBy('resource');

        return view('admin.roles.show', compact('role', 'permissions'));
    }

    /**
     * Show the form for editing the specified role
     */
    public function edit(Role $role)
    {
        if (!auth()->user()->hasPermission('platform.role.update')) {
            abort(403, 'Unauthorized action');
        }

        // Prevent editing system roles (optional)
        if ($role->is_system && $role->name === 'Super Admin') {
            return back()->with('warning', 'Cannot edit Super Admin role');
        }

        $tenants = Tenant::where('status', 'active')->get();
        $permissions = Permission::all()->groupBy('resource');
        $rolePermissions = $role->permissions->pluck('id')->toArray();

        return view('admin.roles.edit', compact('role', 'tenants', 'permissions', 'rolePermissions'));
    }

    /**
     * Update the specified role
     */
    public function update(Request $request, Role $role)
    {
        if (!auth()->user()->hasPermission('platform.role.update')) {
            abort(403, 'Unauthorized action');
        }

        // Prevent editing Super Admin
        if ($role->is_system && $role->name === 'Super Admin') {
            return back()->with('error', 'Cannot modify Super Admin role');
        }

        $validated = $request->validate([
            'tenant_id' => 'nullable|exists:tenants,id',
            'name' => [
                'required',
                'string',
                'max:255',
                Rule::unique('roles')->where(function ($query) use ($request) {
                    return $query->where('tenant_id', $request->tenant_id);
                })->ignore($role->id),
            ],
            'description' => 'nullable|string|max:500',
            'priority' => 'required|integer|min:1|max:100',
            'is_system' => 'boolean',
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        DB::beginTransaction();
        try {
            $role->update([
                'tenant_id' => $validated['tenant_id'],
                'name' => $validated['name'],
                'description' => $validated['description'] ?? null,
                'priority' => $validated['priority'],
                'is_system' => $validated['is_system'] ?? false,
            ]);

            // Sync permissions
            $role->permissions()->sync($validated['permissions'] ?? []);

            DB::commit();

            return redirect()->route('admin.roles.index')
                ->with('success', "Role '{$role->name}' updated successfully.");
        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withInput()
                ->with('error', 'Failed to update role: ' . $e->getMessage());
        }
    }

    /**
     * Delete the specified role
     */
    public function destroy(Role $role)
    {
        if (!auth()->user()->hasPermission('platform.role.delete')) {
            abort(403, 'Unauthorized action');
        }

        // Prevent deleting system roles
        if ($role->is_system) {
            return back()->with('error', 'Cannot delete system roles');
        }

        // Prevent deleting if assigned to users
        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete role assigned to users');
        }

        $roleName = $role->name;
        $role->delete();

        return redirect()->route('admin.roles.index')
            ->with('success', "Role '{$roleName}' deleted successfully.");
    }
}
