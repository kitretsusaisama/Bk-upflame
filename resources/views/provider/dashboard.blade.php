@extends('layouts.dashboard')

@section('title', 'Provider Dashboard')

@section('breadcrumb')
    <span>Provider</span> <span class="breadcrumb-sep">/</span> <span>Dashboard</span>
@endsection

@section('content')
<div class="page-header">
    <h1 class="page-title">Provider Dashboard</h1>
    <div class="page-actions">
        <button class="btn btn-primary">
            <span>📅</span> Manage Availability
        </button>
    </div>
</div>

<div class="stats-grid">
    @include('components.stat-card', [
        'icon' => '📅',
        'value' => $stats['total_bookings'] ?? '0',
        'label' => 'Total Bookings',
        'change' => '+18%',
        'changeType' => 'positive',
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
        'change' => '+12%',
        'changeType' => 'positive',
        'iconClass' => 'stat-icon-success'
    ])
    
    @include('components.stat-card', [
        'icon' => '⭐',
        'value' => $stats['rating'] ?? '0.0',
        'label' => 'Average Rating',
        'iconClass' => 'stat-icon-warning'
    ])
</div>

<div class="grid grid-2">
    @component('components.card', ['title' => 'Upcoming Bookings', 'actions' => '<a href="#" class="btn btn-sm btn-outline">View All</a>'])
        @component('components.table', [
            'headers' => ['Customer', 'Service', 'Date & Time', 'Status', 'Actions']
        ])
            @forelse($upcomingBookings ?? [] as $booking)
                <tr>
                    <td>
                        <div class="table-user">
                            <div class="user-avatar">{{ strtoupper(substr($booking->user->email, 0, 1)) }}</div>
                            <div class="user-details">
                                <div class="user-name">{{ $booking->user->profile->first_name ?? 'N/A' }}</div>
                                <div class="user-email">{{ $booking->user->email }}</div>
                            </div>
                        </div>
                    </td>
                    <td>{{ $booking->service->name ?? 'N/A' }}</td>
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
                            <a href="#" class="btn-icon" title="Contact">📧</a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="5" class="text-center">No upcoming bookings</td>
                </tr>
            @endforelse
        @endcomponent
    @endcomponent
    
    @component('components.card', ['title' => 'This Week Schedule'])
        <div class="schedule-calendar">
            @foreach(['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'] as $day)
                <div class="schedule-day">
                    <div class="schedule-day-name">{{ $day }}</div>
                    <div class="schedule-slots">
                        <div class="schedule-slot occupied">9:00 AM</div>
                        <div class="schedule-slot available">11:00 AM</div>
                        <div class="schedule-slot available">2:00 PM</div>
                    </div>
                </div>
            @endforeach
        </div>
    @endcomponent
</div>

<div class="grid grid-1">
    @component('components.card', ['title' => 'Recent Reviews'])
        <div class="reviews-list">
            @for($i = 1; $i <= 3; $i++)
                <div class="review-item">
                    <div class="review-header">
                        <div class="review-user">
                            <div class="user-avatar">C</div>
                            <div class="user-info">
                                <div class="user-name">Customer {{ $i }}</div>
                                <div class="review-rating">
                                    <span class="stars">⭐⭐⭐⭐⭐</span>
                                </div>
                            </div>
                        </div>
                        <div class="review-date">{{ now()->subDays($i)->diffForHumans() }}</div>
                    </div>
                    <div class="review-text">
                        Excellent service! Very professional and punctual. Highly recommended.
                    </div>
                </div>
            @endfor
        </div>
    @endcomponent
</div>
@endsection
