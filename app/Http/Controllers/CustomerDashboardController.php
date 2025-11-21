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
            'total_bookings' => $this->bookingRepository->totalForUser($userId),
            'upcoming_bookings' => $this->bookingRepository->countByStatusForUser($userId, 'confirmed'),
            'completed_bookings' => $this->bookingRepository->countByStatusForUser($userId, 'completed'),
            'total_spent' => number_format($this->bookingRepository->totalAmountForUser($userId), 2),
        ];

        $upcomingBookings = $this->bookingRepository->upcomingForUser($userId);

        return view('customer.dashboard', compact('menuItems', 'stats', 'upcomingBookings'));
    }
}