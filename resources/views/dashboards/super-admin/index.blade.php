@extends('layouts.dashboard')

@section('title', 'Super Admin Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Super Admin Dashboard') }}</h1>
            <p>{{ __('Welcome to the super admin dashboard. Here you can manage all tenants and system settings.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <x-stat-card :title="__('Total Tenants')" :value="25" icon="building" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Active Users')" :value="1240" icon="users" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Providers')" :value="89" icon="user-md" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Bookings Today')" :value="42" icon="calendar-check" />
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Recent Tenants') }}</h5>
                </div>
                <div class="card-body">
                    <x-table :headers="['Name', 'Status', 'Created']">
                        <tr>
                            <td>Acme Corp</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>2024-01-15</td>
                        </tr>
                        <tr>
                            <td>Globex Inc</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td>2024-01-14</td>
                        </tr>
                        <tr>
                            <td>Wayne Enterprises</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>2024-01-13</td>
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
                            {{ __('Queue Workers') }}
                            <span class="badge badge-success">Running</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Email Service') }}
                            <span class="badge badge-warning">Degraded</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection