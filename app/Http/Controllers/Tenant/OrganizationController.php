<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class OrganizationController extends Controller
{
    public function show()
    {
        $tenant = Auth::user()->tenant;
        return view('tenant.organization.show', compact('tenant'));
    }

    public function update(Request $request)
    {
        $tenant = Auth::user()->tenant;
        
        // Ensure user has permission
        if (!Auth::user()->can('settings.update')) {
            abort(403);
        }

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            // Add other profile fields here (logo, timezone, etc.)
        ]);

        $tenant->update($validated);

        return back()->with('success', 'Organization profile updated.');
    }
}
