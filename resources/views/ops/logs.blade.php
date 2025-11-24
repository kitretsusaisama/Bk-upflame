@extends('layouts.app')

@section('title', 'System Logs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">System Logs</h4>
                    <div class="float-right">
                        <button class="btn btn-secondary btn-sm mr-2">Export Logs</button>
                        <button class="btn btn-danger btn-sm">Clear Logs</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-3">
                            <select class="form-control">
                                <option>All Levels</option>
                                <option>Error</option>
                                <option>Warning</option>
                                <option>Info</option>
                                <option>Debug</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" placeholder="Start Date">
                        </div>
                        <div class="col-md-3">
                            <input type="date" class="form-control" placeholder="End Date">
                        </div>
                        <div class="col-md-3">
                            <button class="btn btn-primary btn-block">Filter</button>
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>Level</th>
                                    <th>Message</th>
                                    <th>Source</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2025-11-22 14:30:25</td>
                                    <td><span class="badge badge-danger">ERROR</span></td>
                                    <td>Database connection failed for tenant admin providers view</td>
                                    <td>ViewServiceProvider</td>
                                </tr>
                                <tr>
                                    <td>2025-11-22 14:25:18</td>
                                    <td><span class="badge badge-warning">WARNING</span></td>
                                    <td>Slow query detected in bookings table</td>
                                    <td>QueryMonitor</td>
                                </tr>
                                <tr>
                                    <td>2025-11-22 14:20:42</td>
                                    <td><span class="badge badge-info">INFO</span></td>
                                    <td>User John Doe logged in successfully</td>
                                    <td>AuthController</td>
                                </tr>
                                <tr>
                                    <td>2025-11-22 14:15:33</td>
                                    <td><span class="badge badge-success">DEBUG</span></td>
                                    <td>Cache hit for user permissions</td>
                                    <td>PermissionService</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                    
                    <nav aria-label="Log pagination">
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled">
                                <a class="page-link" href="#" tabindex="-1">Previous</a>
                            </li>
                            <li class="page-item active">
                                <a class="page-link" href="#">1</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">2</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">3</a>
                            </li>
                            <li class="page-item">
                                <a class="page-link" href="#">Next</a>
                            </li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection