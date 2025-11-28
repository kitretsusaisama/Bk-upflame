@extends('dashboard.layout')

@section('title', 'Provider Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Provider Dashboard') }}</h1>
            <p>{{ __('Welcome to your provider dashboard.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <x-stat-card :title="__('Today\'s Bookings')" :value="3" icon="calendar-check" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Upcoming Bookings')" :value="12" icon="calendar-alt" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Average Rating')" :value="4.8" icon="star" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Total Earnings')" :value="$2,450" icon="dollar-sign" />
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Today\'s Schedule') }}</h5>
                </div>
                <div class="card-body">
                    <x-table :headers="['Time', 'Patient', 'Service', 'Status']">
                        <tr>
                            <td>09:00 - 09:30</td>
                            <td>John Doe</td>
                            <td>Consultation</td>
                            <td><span class="badge badge-success">Confirmed</span></td>
                        </tr>
                        <tr>
                            <td>10:00 - 10:30</td>
                            <td>Jane Roe</td>
                            <td>Follow-up</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                        </tr>
                        <tr>
                            <td>11:00 - 11:30</td>
                            <td>Bob Smith</td>
                            <td>Consultation</td>
                            <td><span class="badge badge-success">Confirmed</span></td>
                        </tr>
                    </x-table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Recent Reviews') }}</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <strong>John Doe</strong>
                                <span class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                </span>
                            </div>
                            <p class="mb-0">{{ __('Great service and very knowledgeable!') }}</p>
                            <small class="text-muted">2 hours ago</small>
                        </div>
                        <div class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <strong>Jane Roe</strong>
                                <span class="text-warning">
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star"></i>
                                    <i class="fas fa-star-half-alt"></i>
                                </span>
                            </div>
                            <p class="mb-0">{{ __('Professional and friendly staff.') }}</p>
                            <small class="text-muted">1 day ago</small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection