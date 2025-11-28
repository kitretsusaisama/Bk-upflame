@extends('dashboard.layout')

@section('title', 'Customer Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Customer Dashboard') }}</h1>
            <p>{{ __('Welcome to your customer dashboard.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <x-stat-card :title="__('Upcoming Bookings')" :value="2" icon="calendar-check" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Past Bookings')" :value="15" icon="calendar-alt" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Favorite Providers')" :value="3" icon="heart" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Notifications')" :value="4" icon="bell" />
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Upcoming Bookings') }}</h5>
                </div>
                <div class="card-body">
                    <x-table :headers="['Provider', 'Date & Time', 'Service', 'Status']">
                        <tr>
                            <td>Dr. Smith</td>
                            <td>2024-01-25 10:00</td>
                            <td>Consultation</td>
                            <td><span class="badge badge-success">Confirmed</span></td>
                        </tr>
                        <tr>
                            <td>Dr. Johnson</td>
                            <td>2024-01-30 14:30</td>
                            <td>Follow-up</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                        </tr>
                    </x-table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Recent Activity') }}</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Booking confirmed with Dr. Smith') }}</span>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Reminder: Appointment with Dr. Johnson tomorrow') }}</span>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('New provider added: Dr. Williams') }}</span>
                                <small class="text-muted">2 days ago</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection