<?php

namespace App\Http\Controllers;

use App\Domains\Booking\Repositories\BookingRepository;
use Illuminate\Http\Request;

class ProviderDashboardController extends Controller
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
    }

    public function dashboard()
    {
        $provider = auth()->user()->provider;

        if (!$provider) {
            abort(403, 'Provider profile not configured');
        }

        $menuItems = [
            ['label' => 'Dashboard', 'route' => 'provider.dashboard', 'icon' => '📊'],
            ['label' => 'Bookings', 'route' => 'provider.bookings', 'icon' => '📅'],
            ['label' => 'Schedule', 'route' => 'provider.schedule', 'icon' => '🗓️'],
            ['label' => 'Services', 'route' => 'provider.services', 'icon' => '🔧'],
            ['separator' => true, 'label' => 'Account'],
            ['label' => 'Profile', 'route' => 'provider.profile', 'icon' => '👤'],
            ['label' => 'Reviews', 'route' => 'provider.reviews', 'icon' => '⭐'],
        ];

        $stats = [
            'total_bookings' => $this->bookingRepository->totalForProvider($provider->id),
            'upcoming_bookings' => $this->bookingRepository->countByStatusForProvider($provider->id, 'confirmed'),
            'completed_bookings' => $this->bookingRepository->countByStatusForProvider($provider->id, 'completed'),
            'rating' => number_format($provider->profile_json['rating'] ?? 4.8, 1),
        ];

        $upcomingBookings = $this->bookingRepository->upcomingForProvider($provider->id);

        return view('provider.dashboard', compact('menuItems', 'stats', 'upcomingBookings'));
    }
}