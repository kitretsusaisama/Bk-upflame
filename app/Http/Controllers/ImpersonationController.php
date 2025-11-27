<?php

namespace App\Http\Controllers;

use App\Domains\Identity\Models\User;
use App\Services\ImpersonationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ImpersonationController extends Controller
{
    protected ImpersonationService $impersonationService;

    public function __construct(ImpersonationService $impersonationService)
    {
        $this->impersonationService = $impersonationService;
    }

    public function start(Request $request, User $user)
    {
        // Permission check: Can the current user impersonate?
        // This should ideally be handled by a Policy or Gate, but for now:
        $currentUser = Auth::user();
        
        if (!$currentUser->can('impersonate.user') && !$currentUser->isSuperAdmin()) {
            abort(403, 'Unauthorized to impersonate.');
        }

        // Additional check: Tenant Admin can only impersonate users in their tenant
        if (!$currentUser->isSuperAdmin() && $currentUser->tenant_id !== $user->tenant_id) {
            abort(403, 'Cannot impersonate users from other tenants.');
        }

        try {
            $this->impersonationService->start($currentUser, $user);
            return redirect()->route('dashboard')->with('success', "You are now impersonating {$user->name}");
        } catch (\Exception $e) {
            return back()->with('error', $e->getMessage());
        }
    }

    public function stop()
    {
        $this->impersonationService->stop();
        return redirect()->route('dashboard')->with('success', 'Welcome back!');
    }
}
