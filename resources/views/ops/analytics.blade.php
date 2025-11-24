@extends('layouts.dashboard')

@section('title', 'Ops Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Analytics Dashboard</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Workflow Completion Trends</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="completionTrendsChart" height="80"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Performance Metrics</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Avg. Completion Time
                                            <span class="badge badge-primary">2.4 hours</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            SLA Compliance
                                            <span class="badge badge-success">98%</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Approval Rate
                                            <span class="badge badge-info">92%</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Workflow Distribution</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="workflowDistributionChart" height="150"></canvas>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-header">
                                    <h5>User Activity</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Active Users (24h)
                                            <span class="badge badge-primary">24</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Peak Usage Time
                                            <span class="badge badge-warning">10:00 AM</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Avg. Sessions
                                            <span class="badge badge-info">3.2</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection