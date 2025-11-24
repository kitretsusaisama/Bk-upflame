@extends('layouts.dashboard')

@section('title', 'Ops Reports')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Operational Reports</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-card-icon">📋</div>
                                <div class="stat-card-content">
                                    <h3>142</h3>
                                    <p>Active Workflows</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-card-icon">✅</div>
                                <div class="stat-card-content">
                                    <h3>28</h3>
                                    <p>Pending Approvals</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-card-icon">⏱️</div>
                                <div class="stat-card-content">
                                    <h3>2.4h</h3>
                                    <p>Avg. Completion Time</p>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="stat-card">
                                <div class="stat-card-icon">📈</div>
                                <div class="stat-card-content">
                                    <h3>98%</h3>
                                    <p>SLA Compliance</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Workflow Performance</h5>
                                </div>
                                <div class="card-body">
                                    <canvas id="workflowChart" height="100"></canvas>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Top Workflow Types</h5>
                                </div>
                                <div class="card-body">
                                    <ul class="list-group">
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Provider Onboarding
                                            <span class="badge badge-primary badge-pill">42</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Booking Requests
                                            <span class="badge badge-primary badge-pill">38</span>
                                        </li>
                                        <li class="list-group-item d-flex justify-content-between align-items-center">
                                            Service Approvals
                                            <span class="badge badge-primary badge-pill">26</span>
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Activity</h5>
                                </div>
                                <div class="card-body">
                                    <div class="activity-feed">
                                        <div class="feed-item">
                                            <div class="feed-date">2 hours ago</div>
                                            <div class="feed-content">
                                                <strong>Workflow WF-2023-001</strong> completed by John Admin
                                            </div>
                                        </div>
                                        <div class="feed-item">
                                            <div class="feed-date">4 hours ago</div>
                                            <div class="feed-content">
                                                <strong>Approval REQ-2023-002</strong> approved by Jane Ops
                                            </div>
                                        </div>
                                        <div class="feed-item">
                                            <div class="feed-date">1 day ago</div>
                                            <div class="feed-content">
                                                <strong>Workflow WF-2023-003</strong> started for new provider
                                            </div>
                                        </div>
                                    </div>
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