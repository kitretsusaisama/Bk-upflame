@extends('layouts.dashboard')

@section('title', 'Operations Dashboard')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Operations Dashboard') }}</h1>
            <p>{{ __('Monitor and manage system operations.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <x-stat-card :title="__('Active Workflows')" :value="24" icon="project-diagram" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Pending Tasks')" :value="8" icon="tasks" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('System Alerts')" :value="2" icon="exclamation-triangle" />
        </div>
        <div class="col-md-3">
            <x-stat-card :title="__('Completed Today')" :value="36" icon="check-circle" />
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Active Workflows') }}</h5>
                </div>
                <div class="card-body">
                    <x-table :headers="['Workflow', 'Status', 'Progress', 'Actions']">
                        <tr>
                            <td>User Onboarding</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>75%</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Provider Verification</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td>25%</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Booking Process</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>90%</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                            </td>
                        </tr>
                    </x-table>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('System Alerts') }}</h5>
                </div>
                <div class="card-body">
                    <div class="alert alert-warning">
                        <h6>{{ __('High Memory Usage') }}</h6>
                        <p class="mb-0">{{ __('Server memory usage is at 85%. Consider scaling up resources.') }}</p>
                        <small class="text-muted">2 hours ago</small>
                    </div>
                    <div class="alert alert-info">
                        <h6>{{ __('New Workflow Template') }}</h6>
                        <p class="mb-0">{{ __('A new workflow template is available for update.') }}</p>
                        <small class="text-muted">1 day ago</small>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection