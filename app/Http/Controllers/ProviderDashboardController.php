<?php

namespace App\Http\Controllers;

use App\Domains\Booking\Repositories\BookingRepository;
use App\Domains\Menu\Services\MenuService;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProviderDashboardController extends Controller
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
        $provider = auth()->user()->provider;

        if (!$provider) {
            abort(403, 'Provider profile not configured');
        }

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
        $userRole = 'Provider';

        $stats = [
            'total_bookings' => $this->bookingRepository->totalForProvider($provider->id),
            'upcoming_bookings' => $this->bookingRepository->countByStatusForProvider($provider->id, 'confirmed'),
            'completed_bookings' => $this->bookingRepository->countByStatusForProvider($provider->id, 'completed'),
            'rating' => number_format($provider->profile_json['rating'] ?? 4.8, 1),
        ];

        $upcomingBookings = $this->bookingRepository->upcomingForProvider($provider->id);

        return Inertia::render('Provider/Dashboard', compact('menuItems', 'stats', 'upcomingBookings', 'userRole'));
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
        $userRole = 'Provider';
        return Inertia::render('Provider/Bookings', compact('menuItems', 'userRole'));
    }

    public function schedule()
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
        $userRole = 'Provider';
        return Inertia::render('Provider/Schedule', compact('menuItems', 'userRole'));
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
        $userRole = 'Provider';
        return Inertia::render('Provider/Services', compact('menuItems', 'userRole'));
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
        $userRole = 'Provider';
        return Inertia::render('Provider/Profile', compact('menuItems', 'userRole'));
    }

    public function reviews()
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
        $userRole = 'Provider';
        return Inertia::render('Provider/Reviews', compact('menuItems', 'userRole'));
    }
}