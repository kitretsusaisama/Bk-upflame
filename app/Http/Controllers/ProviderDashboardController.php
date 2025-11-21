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
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $provider = auth()->user()->provider;

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
            'total_bookings' => $provider ? $this->bookingRepository->findByProvider($provider->id, 1)->total() : 0,
            'upcoming_bookings' => $provider ? $this->bookingRepository->findByStatus('confirmed', null, 1)->total() : 0,
            'completed_bookings' => $provider ? $this->bookingRepository->findByStatus('completed', null, 1)->total() : 0,
            'rating' => '4.8',
        ];

        $upcomingBookings = $provider ? $this->bookingRepository->findByProvider($provider->id, 10) : collect();

        return view('provider.dashboard', compact('menuItems', 'stats', 'upcomingBookings'));
    }
}
