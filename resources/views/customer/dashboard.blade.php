@extends('layouts.dashboard')

@section('title', 'Customer Dashboard')

@section('breadcrumb')
    <span>Customer</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Welcome Back, {{ auth()->user()->profile->first_name ?? 'Customer' }}!</h1>
    <div class="page-actions">
        <button class="btn btn-primary">
            <span>➕</span> New Booking
        </button>
    </div>
</div>

<div class="stats-grid">
    @include('components.stat-card', [
        'icon' => '📅',
        'value' => $stats['total_bookings'] ?? '0',
        'label' => 'Total Bookings',
        'iconClass' => 'stat-icon-primary'
    ])
    
    @include('components.stat-card', [
        'icon' => '⏰',
        'value' => $stats['upcoming_bookings'] ?? '0',
        'label' => 'Upcoming',
        'iconClass' => 'stat-icon-info'
    ])
    
    @include('components.stat-card', [
        'icon' => '✅',
        'value' => $stats['completed_bookings'] ?? '0',
        'label' => 'Completed',
        'iconClass' => 'stat-icon-success'
    ])
    
    @include('components.stat-card', [
        'icon' => '💰',
        'value' => '$' . ($stats['total_spent'] ?? '0'),
        'label' => 'Total Spent',
        'iconClass' => 'stat-icon-warning'
    ])
</div>

<div class="grid grid-2">
    @component('components.card', ['title' => 'Your Upcoming Bookings', 'actions' => '<a href="#" class="btn btn-sm btn-outline">View All</a>'])
        @component('components.table', [
            'headers' => ['Service', 'Provider', 'Date & Time', 'Status', 'Actions']
        ])
            @forelse($upcomingBookings ?? [] as $booking)
                <tr>
                    <td>
                        <div class="service-name">{{ $booking->service->name ?? 'N/A' }}</div>
                        <div class="text-muted">{{ $booking->booking_reference }}</div>
                    </td>
                    <td>
                        <div class="table-user">
                            <div class="user-avatar">{{ strtoupper(substr($booking->provider->first_name ?? 'P', 0, 1)) }}</div>
                            <div class="user-details">
                                <div class="user-name">{{ $booking->provider->first_name ?? 'Provider' }}</div>
                                <div class="user-email">{{ $booking->provider->email ?? 'N/A' }}</div>
                            </div>
                        </div>
                    </td>
                    <td>
                        <div>{{ $booking->scheduled_at->format('M d, Y') }}</div>
                        <div class="text-muted">{{ $booking->scheduled_at->format('h:i A') }}</div>
                    </td>
                    <td>
                        @include('components.badge', [
                            'type' => $booking->status === 'confirmed' ? 'success' : 'warning',
                            'slot' => ucfirst($booking->status)
                        ])
                    </td>
                    <td>
                        <div class="table-actions">
                            <a href="#" class="btn-icon" title="View Details">👁️</a>
                            <a href="#" class="btn-icon" title="Cancel">❌</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">
                        <div class="empty-state">
                            <p>No upcoming bookings</p>
                            <button class="btn btn-sm btn-primary">Book Now</button>
                        </div>
                    </td>
                </tr>
            @endforelse
        @endcomponent
    @endcomponent
    
    @component('components.card', ['title' => 'Available Services'])
        <div class="services-grid">
            @for($i = 1; $i <= 4; $i++)
                <div class="service-card">
                    <div class="service-icon">🔧</div>
                    <div class="service-name">Service {{ $i }}</div>
                    <div class="service-price">$99.00</div>
                    <button class="btn btn-sm btn-primary btn-block">Book Now</button>
                </div>
            @endfor
        </div>
    @endcomponent
</div>

<div class="grid grid-1">
    @component('components.card', ['title' => 'Recent Bookings'])
        <div class="booking-history">
            @for($i = 1; $i <= 3; $i++)
                <div class="booking-history-item">
                    <div class="booking-icon completed">✅</div>
                    <div class="booking-details">
                        <div class="booking-service">Service {{ $i }}</div>
                        <div class="booking-meta">
                            <span>Provider: John Doe</span>
                            <span class="meta-sep">•</span>
                            <span>{{ now()->subDays($i * 5)->format('M d, Y') }}</span>
                        </div>
                    </div>
                    <div class="booking-actions">
                        @include('components.badge', ['type' => 'success', 'slot' => 'Completed'])
                        <button class="btn btn-sm btn-outline">View Details</button>
                        <button class="btn btn-sm btn-primary">Rebook</button>
                    </div>
                </div>
            @endfor
        </div>
    @endcomponent
</div>
@endsection
