<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Models\User;
use App\Domains\Access\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;

class UserController extends Controller
{
    public function index()
    {
        $users = User::where('tenant_id', Auth::user()->tenant_id)
            ->with('roles')
            ->paginate(10);
            
        return view('tenant.users.index', compact('users'));
    }

    public function create()
    {
        $roles = Role::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('tenant.users.create', compact('roles'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')],
            'password' => 'required|string|min:8|confirmed',
            'role' => 'required|exists:roles,id',
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'tenant_id' => Auth::user()->tenant_id,
        ]);

        $user->roles()->attach($validated['role']);

        return redirect()->route('tenant.users.index')->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        // Ensure user belongs to same tenant
        if ($user->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $roles = Role::where('tenant_id', Auth::user()->tenant_id)->get();
        return view('tenant.users.edit', compact('user', 'roles'));
    }

    public function update(Request $request, User $user)
    {
        if ($user->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'role' => 'required|exists:roles,id',
        ]);

        $user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);

        $user->roles()->sync([$validated['role']]);

        return redirect()->route('tenant.users.index')->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->tenant_id !== Auth::user()->tenant_id) {
            abort(403);
        }

        $user->delete();
        return redirect()->route('tenant.users.index')->with('success', 'User deleted successfully.');
    }
}
