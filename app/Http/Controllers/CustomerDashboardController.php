<?php

namespace App\Http\Controllers;

use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Menu\Services\MenuService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CustomerDashboardController extends Controller
{
    protected $bookingRepository;
    protected $menuService;

    public function __construct(BookingRepository $bookingRepository, MenuService $menuService)
    {
        $this->bookingRepository = $bookingRepository;
        $this->menuService = $menuService;
    }

    public function dashboard()
    {
        $userId = auth()->id();

        // Safely get tenant ID from the tenant binding or authenticated user
        $tenantId = null;
        try {
            $tenant = app('tenant');
            $tenantId = $tenant ? $tenant->id : (auth()->user()->tenant_id ?? null);
        } catch (\Exception $e) {
            // Tenant binding doesn't exist, try to get from user
            $tenantId = auth()->user()->tenant_id ?? null;
        }

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Customer';

        $stats = [
            'total_bookings' => $this->bookingRepository->totalForUser($userId),
            'upcoming_bookings' => $this->bookingRepository->countByStatusForUser($userId, 'confirmed'),
            'completed_bookings' => $this->bookingRepository->countByStatusForUser($userId, 'completed'),
            'total_spent' => number_format($this->bookingRepository->totalAmountForUser($userId), 2),
        ];

        $upcomingBookings = $this->bookingRepository->upcomingForUser($userId);

        return Inertia::render('Customer/Dashboard', compact('menuItems', 'stats', 'upcomingBookings', 'userRole'));
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

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Customer';
        
        return Inertia::render('Customer/Bookings', compact('menuItems', 'userRole'));
    }

    public function services()
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

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Customer';
        
        return Inertia::render('Customer/Services', compact('menuItems', 'userRole'));
    }

    public function profile()
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

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Customer';
        
        return Inertia::render('Customer/Profile', compact('menuItems', 'userRole'));
    }

    public function payments()
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

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Customer';
        
        return Inertia::render('Customer/Payments', compact('menuItems', 'userRole'));
    }

    public function support()
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

        $menuItems = $this->menuService->getSidebarForCurrentUser();
        $userRole = 'Customer';
        
        return Inertia::render('Customer/Support', compact('menuItems', 'userRole'));
    }
}