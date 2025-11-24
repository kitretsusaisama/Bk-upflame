@extends('layouts.app')

@section('title', 'Analytics')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Advanced Analytics</h4>
                </div>
                <div class="card-body">
                    <ul class="nav nav-tabs" id="analyticsTabs" role="tablist">
                        <li class="nav-item">
                            <a class="nav-link active" id="performance-tab" data-toggle="tab" href="#performance" role="tab">Performance</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="usage-tab" data-toggle="tab" href="#usage" role="tab">Usage</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" id="conversion-tab" data-toggle="tab" href="#conversion" role="tab">Conversion</a>
                        </li>
                    </ul>
                    
                    <div class="tab-content mt-4" id="analyticsTabContent">
                        <div class="tab-pane fade show active" id="performance" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>System Performance Metrics</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="performanceChart" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="usage" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>User Activity & Engagement</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="usageChart" height="100"></canvas>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="tab-pane fade" id="conversion" role="tabpanel">
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="card">
                                        <div class="card-header">
                                            <h5>Conversion Funnel Analysis</h5>
                                        </div>
                                        <div class="card-body">
                                            <canvas id="conversionChart" height="100"></canvas>
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