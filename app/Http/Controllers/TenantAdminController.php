<?php

namespace App\Http\Controllers;

use App\Domains\Identity\Repositories\UserRepository;
use App\Domains\Provider\Repositories\ProviderRepository;
use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Workflow\Repositories\WorkflowRepository;
use App\Domains\Menu\Services\MenuService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class TenantAdminController extends Controller
{
    protected $userRepository;
    protected $providerRepository;
    protected $bookingRepository;
    protected $workflowRepository;
    protected $menuService;

    public function __construct(
        UserRepository $userRepository,
        ProviderRepository $providerRepository,
        BookingRepository $bookingRepository,
        WorkflowRepository $workflowRepository,
        MenuService $menuService
    ) {
        $this->userRepository = $userRepository;
        $this->providerRepository = $providerRepository;
        $this->bookingRepository = $bookingRepository;
        $this->workflowRepository = $workflowRepository;
        $this->menuService = $menuService;
    }

    public function dashboard()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Tenant Admin';

        $stats = [
            'total_users' => $this->userRepository->findByTenant($tenantId, 1)->total(),
            'total_providers' => $this->providerRepository->findByTenant($tenantId, 1)->total(),
            'total_bookings' => $this->bookingRepository->findByTenant($tenantId, 1)->total(),
            'pending_workflows' => $this->workflowRepository->findByStatus('pending', $tenantId, 1)->total(),
        ];

        $users = $this->userRepository->findByTenant($tenantId, 10);
        $pendingWorkflows = $this->workflowRepository->findByStatus('pending', $tenantId, 5);

        return Inertia::render('TenantAdmin/Dashboard', compact('menuItems', 'stats', 'users', 'pendingWorkflows', 'userRole'));
    }

    public function users()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Tenant Admin';
        $users = $this->userRepository->findByTenant($tenantId, 20);

        return Inertia::render('TenantAdmin/Users', compact('menuItems', 'users', 'userRole'));
    }

    public function providers()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Tenant Admin';

        return Inertia::render('TenantAdmin/Providers', compact('menuItems', 'userRole'));
    }

    public function bookings()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Tenant Admin';

        return Inertia::render('TenantAdmin/Bookings', compact('menuItems', 'userRole'));
    }

    public function roles()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Tenant Admin';

        return Inertia::render('TenantAdmin/Roles', compact('menuItems', 'userRole'));
    }

    public function settings()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Tenant Admin';

        return Inertia::render('TenantAdmin/Settings', compact('menuItems', 'userRole'));
    }

    public function menu()
    {
        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }
        
        // If we still don't have a tenant ID, throw an exception
        if (!$tenantId) {
            abort(403, 'Tenant not found');
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Tenant Admin';
        $allMenuItems = $this->menuService->getAllForTenant($tenantId);

        return Inertia::render('TenantAdmin/Menu', compact('menuItems', 'allMenuItems', 'userRole'));
    }
}