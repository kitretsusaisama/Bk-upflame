@extends('dashboard.layout')

@section('title', 'System Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('System Analytics') }}</h1>
            <p>{{ __('View system-wide analytics and metrics.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <ul class="nav nav-tabs card-header-tabs">
                        <li class="nav-item">
                            <a class="nav-link active" href="#">{{ __('Usage') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ __('Performance') }}</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="#">{{ __('Revenue') }}</a>
                        </li>
                    </ul>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('Tenant Growth') }}</h5>
                            <canvas id="tenantGrowthChart" height="200"></canvas>
                        </div>
                        <div class="col-md-6">
                            <h5>{{ __('User Activity') }}</h5>
                            <canvas id="userActivityChart" height="200"></canvas>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <h5>{{ __('Subscription Distribution') }}</h5>
                            <canvas id="subscriptionChart" height="100"></canvas>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row mt-4">
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Recent Activity') }}</h5>
                </div>
                <div class="card-body">
                    <ul class="list-group">
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('New tenant registered: Acme Corp') }}</span>
                                <small class="text-muted">2 hours ago</small>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Subscription upgraded: Globex Inc') }}</span>
                                <small class="text-muted">5 hours ago</small>
                            </div>
                        </li>
                        <li class="list-group-item">
                            <div class="d-flex justify-content-between">
                                <span>{{ __('Provider added: Dr. Jane Smith') }}</span>
                                <small class="text-muted">1 day ago</small>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('System Metrics') }}</h5>
                </div>
                <div class="card-body">
                    <div class="list-group">
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('API Requests (24h)') }}
                            <span class="badge badge-primary">12,458</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Average Response Time') }}
                            <span class="badge badge-success">142ms</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Error Rate') }}
                            <span class="badge badge-danger">0.2%</span>
                        </div>
                        <div class="list-group-item d-flex justify-content-between align-items-center">
                            {{ __('Active Users (24h)') }}
                            <span class="badge badge-info">1,240</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection