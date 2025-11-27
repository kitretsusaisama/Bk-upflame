<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Domains\Identity\Models\Tenant;
use App\Domains\Identity\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class TenantController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tenants = Tenant::withCount('users')->latest()->paginate(10);
        return view('admin.tenants.index', compact('tenants'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.tenants.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => 'required|string|max:255|unique:tenants,domain',
            'status' => 'required|in:active,inactive,suspended',
            'tier' => 'required|in:free,standard,premium,enterprise',
        ]);

        $validated['slug'] = Str::slug($validated['name']);

        Tenant::create($validated);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant created successfully.');
    }

    /**
     * Display the specified resource.
     */
    public function show(Tenant $tenant)
    {
        $tenant->load(['users' => function($query) {
            $query->limit(5);
        }]);
        
        return view('admin.tenants.show', compact('tenant'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Tenant $tenant)
    {
        return view('admin.tenants.edit', compact('tenant'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Tenant $tenant)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'domain' => ['required', 'string', 'max:255', Rule::unique('tenants')->ignore($tenant->id)],
            'status' => 'required|in:active,inactive,suspended',
            'tier' => 'required|in:free,standard,premium,enterprise',
        ]);

        if ($request->has('name') && $request->name !== $tenant->name) {
             $validated['slug'] = Str::slug($validated['name']);
        }

        $tenant->update($validated);

        return redirect()->route('admin.tenants.index')->with('success', 'Tenant updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('admin.tenants.index')->with('success', 'Tenant deleted successfully.');
    }

    /**
     * Suspend the tenant.
     */
    public function suspend(Tenant $tenant)
    {
        $tenant->update(['status' => 'suspended']);
        return back()->with('success', 'Tenant suspended.');
    }

    /**
     * Activate the tenant.
     */
    public function activate(Tenant $tenant)
    {
        $tenant->update(['status' => 'active']);
        return back()->with('success', 'Tenant activated.');
    }
}
