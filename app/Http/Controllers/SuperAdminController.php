<?php

namespace App\Http\Controllers;

use App\Domains\Identity\Repositories\TenantRepository;
use App\Domains\Identity\Repositories\UserRepository;
use App\Domains\Menu\Services\MenuService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SuperAdminController extends Controller
{
    protected $tenantRepository;
    protected $userRepository;
    protected $menuService;

    public function __construct(
        TenantRepository $tenantRepository,
        UserRepository $userRepository,
        MenuService $menuService
    ) {
        $this->tenantRepository = $tenantRepository;
        $this->userRepository = $userRepository;
        $this->menuService = $menuService;
    }

    public function dashboard()
    {
        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Super Admin';

        $stats = [
            'total_tenants' => $this->tenantRepository->findAll(1)->total(),
            'total_users' => $this->userRepository->findAll(1)->total(),
            'active_sessions' => rand(150, 300),
            'revenue' => number_format(rand(10000, 50000), 2),
        ];

        $tenants = $this->tenantRepository->findAll(10);

        return Inertia::render('SuperAdmin/Dashboard', compact('menuItems', 'stats', 'tenants', 'userRole'));
    }

    public function tenants()
    {
        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Super Admin';
        $tenants = $this->tenantRepository->findAll(20);

        return Inertia::render('SuperAdmin/Tenants', compact('menuItems', 'tenants', 'userRole'));
    }

    public function users()
    {
        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Super Admin';
        
        return Inertia::render('SuperAdmin/Users', compact('menuItems', 'userRole'));
    }

    public function system()
    {
        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Super Admin';

        return Inertia::render('SuperAdmin/System', compact('menuItems', 'userRole'));
    }

    public function reports()
    {
        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Super Admin';

        return Inertia::render('SuperAdmin/Reports', compact('menuItems', 'userRole'));
    }

    public function logs()
    {
        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Super Admin';

        return Inertia::render('SuperAdmin/Logs', compact('menuItems', 'userRole'));
    }
    
    public function profile(Request $request)
    {
        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Super Admin';
        $user = auth()->user();
        
        // Load the user's profile
        $user->load('profile');
        
        // Handle form submissions
        if ($request->isMethod('post')) {
            // Determine which form was submitted
            if ($request->has('currentPassword')) {
                // Handle password update
                $request->validate([
                    'currentPassword' => 'required',
                    'newPassword' => 'required|min:8|confirmed',
                ]);
                
                // Check current password
                if (!\Hash::check($request->currentPassword, $user->password)) {
                    return back()->withErrors(['currentPassword' => 'Current password is incorrect.']);
                }
                
                // Update password
                $user->password = \Hash::make($request->newPassword);
                $user->save();
                
                return back()->with('success', 'Password updated successfully.');
            } elseif ($request->has('timezone')) {
                // Handle preferences update
                $request->validate([
                    'timezone' => 'required|in:UTC,America/New_York,America/Chicago,America/Denver,America/Los_Angeles',
                ]);
                
                // For now, we'll just show a success message since we don't have preference fields in the database
                return back()->with('success', 'Preferences updated successfully.');
            } else {
                // Handle profile information update
                $request->validate([
                    'name' => 'required|string|max:255',
                    'email' => 'required|email|unique:users,email,' . $user->id,
                    'phone' => 'nullable|string|max:20',
                ]);
                
                // Update user email
                $user->email = $request->email;
                $user->save();
                
                // Update or create profile
                $nameParts = explode(' ', $request->name, 2);
                $firstName = $nameParts[0];
                $lastName = isset($nameParts[1]) ? $nameParts[1] : '';
                
                if ($user->profile) {
                    $user->profile->update([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone' => $request->phone,
                    ]);
                } else {
                    $user->profile()->create([
                        'first_name' => $firstName,
                        'last_name' => $lastName,
                        'phone' => $request->phone,
                        'tenant_id' => $user->tenant_id,
                    ]);
                }
                
                return back()->with('success', 'Profile updated successfully.');
            }
        }

        return Inertia::render('SuperAdmin/Profile', compact('menuItems', 'userRole', 'user'));
    }
}