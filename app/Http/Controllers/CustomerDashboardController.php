<?php

namespace App\Http\Controllers;

use App\Domains\Booking\Repositories\BookingRepository;
use Illuminate\Http\Request;

class CustomerDashboardController extends Controller
{
    protected $bookingRepository;

    public function __construct(BookingRepository $bookingRepository)
    {
        $this->bookingRepository = $bookingRepository;
        $this->middleware('auth');
    }

    public function dashboard()
    {
        $userId = auth()->id();

        $menuItems = [
            ['label' => 'Dashboard', 'route' => 'customer.dashboard', 'icon' => '🏠'],
            ['label' => 'My Bookings', 'route' => 'customer.bookings', 'icon' => '📅'],
            ['label' => 'Browse Services', 'route' => 'customer.services', 'icon' => '🔍'],
            ['separator' => true, 'label' => 'Account'],
            ['label' => 'Profile', 'route' => 'customer.profile', 'icon' => '👤'],
            ['label' => 'Payment Methods', 'route' => 'customer.payments', 'icon' => '💳'],
            ['label' => 'Support', 'route' => 'customer.support', 'icon' => '💬'],
        ];

        $stats = [
            'total_bookings' => $this->bookingRepository->findByUser($userId, 1)->total(),
            'upcoming_bookings' => $this->bookingRepository->findByStatus('confirmed', null, 1)->total(),
            'completed_bookings' => $this->bookingRepository->findByStatus('completed', null, 1)->total(),
            'total_spent' => number_format(rand(500, 2000), 2),
        ];

        $upcomingBookings = $this->bookingRepository->findByUser($userId, 10);

        return view('customer.dashboard', compact('menuItems', 'stats', 'upcomingBookings'));
    }
}
