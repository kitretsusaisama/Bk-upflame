@extends('layouts.dashboard')

@section('title', 'Tenant Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Tenant Admin Dashboard') }}</h1>
            <p>{{ __('Welcome to your tenant administration dashboard.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <x-stat-card :title="__('Total Users')" :value="42" icon="users" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Providers')" :value="8" icon="user-md" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Active Bookings')" :value="15" icon="calendar-check" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Workflows')" :value="3" icon="project-diagram" />
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Recent Bookings') }}</h5>
                </div>
                <div class="card-body">
                    <x-table :headers="['User', 'Provider', 'Date', 'Status']">
                        <tr>
                            <td>John Doe</td>
                            <td>Dr. Smith</td>
                            <td>2024-01-20 10:00</td>
                            <td><span class="badge badge-success">Confirmed</span></td>
                        </tr>
                        <tr>
                            <td>Jane Roe</td>
                            <td>Dr. Johnson</td>
                            <td>2024-01-20 14:30</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                        </tr>
                        <tr>
                            <td>Bob Smith</td>
                            <td>Dr. Williams</td>
                            <td>2024-01-21 09:15</td>
                            <td><span class="badge badge-success">Confirmed</span></td>
                        </tr>
                    </x-table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('System Health') }}</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Database') }}
                            <span class="badge badge-success">Online</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Cache') }}
                            <span class="badge badge-success">Online</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Email Service') }}
                            <span class="badge badge-success">Online</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection