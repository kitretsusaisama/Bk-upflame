<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Domains\Access\Models\Role;
use App\Domains\Access\Models\Permission;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class RoleController extends Controller
{
    public function index()
    {
        $roles = Role::where('tenant_id', Auth::user()->tenant_id)->paginate(10);
        return view('tenant.roles.index', compact('roles'));
    }

    public function create()
    {
        // Only show permissions that are allowed for tenants
        // For now, we show all, but in a real app we might filter platform-only perms
        $permissions = Permission::all()->groupBy('resource');
        return view('tenant.roles.create', compact('permissions'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->where('tenant_id', Auth::user()->tenant_id)],
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role = Role::create([
            'name' => $validated['name'],
            'tenant_id' => Auth::user()->tenant_id,
            'guard_name' => 'web',
        ]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('tenant.roles.index')->with('success', 'Role created successfully.');
    }

    public function edit(Role $role)
    {
        if ($role->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $permissions = Permission::all()->groupBy('resource');
        return view('tenant.roles.edit', compact('role', 'permissions'));
    }

    public function update(Request $request, Role $role)
    {
        if ($role->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('roles')->where('tenant_id', Auth::user()->tenant_id)->ignore($role->id)],
            'permissions' => 'array',
            'permissions.*' => 'exists:permissions,id',
        ]);

        $role->update(['name' => $validated['name']]);

        if (isset($validated['permissions'])) {
            $role->syncPermissions($validated['permissions']);
        }

        return redirect()->route('tenant.roles.index')->with('success', 'Role updated successfully.');
    }

    public function destroy(Role $role)
    {
        if ($role->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        // Prevent deleting system roles if needed, or check if assigned to users
        if ($role->users()->exists()) {
            return back()->with('error', 'Cannot delete role assigned to users.');
        }

        $role->delete();
        return redirect()->route('tenant.roles.index')->with('success', 'Role deleted successfully.');
    }
}
