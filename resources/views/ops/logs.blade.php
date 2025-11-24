@extends('layouts.dashboard')

@section('title', 'Ops Audit Logs')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Audit Logs</h4>
                    <div class="card-actions">
                        <button class="btn btn-sm btn-outline-primary">Export Logs</button>
                        <button class="btn btn-sm btn-outline-secondary">Clear Filters</button>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row mb-4">
                        <div class="col-md-3">
                            <label for="dateFilter">Date Range</label>
                            <input type="date" class="form-control" id="dateFilter">
                        </div>
                        <div class="col-md-3">
                            <label for="userFilter">User</label>
                            <select class="form-control" id="userFilter">
                                <option>All Users</option>
                                <option>John Admin</option>
                                <option>Jane Ops</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="actionFilter">Action Type</label>
                            <select class="form-control" id="actionFilter">
                                <option>All Actions</option>
                                <option>Create</option>
                                <option>Update</option>
                                <option>Delete</option>
                                <option>Approve</option>
                                <option>Reject</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label for="searchFilter">Search</label>
                            <input type="text" class="form-control" id="searchFilter" placeholder="Search logs...">
                        </div>
                    </div>
                    
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Timestamp</th>
                                    <th>User</th>
                                    <th>Action</th>
                                    <th>Resource</th>
                                    <th>Details</th>
                                    <th>IP Address</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>2023-12-15 14:30:22</td>
                                    <td>John Admin</td>
                                    <td><span class="badge badge-success">Approve</span></td>
                                    <td>Workflow WF-2023-001</td>
                                    <td>Provider onboarding approved</td>
                                    <td>192.168.1.100</td>
                                </tr>
                                <tr>
                                    <td>2023-12-15 14:25:18</td>
                                    <td>Jane Ops</td>
                                    <td><span class="badge badge-info">Update</span></td>
                                    <td>Booking BK-2023-045</td>
                                    <td>Rescheduled appointment</td>
                                    <td>192.168.1.101</td>
                                </tr>
                                <tr>
                                    <td>2023-12-15 14:15:44</td>
                                    <td>System</td>
                                    <td><span class="badge badge-warning">Create</span></td>
                                    <td>Workflow WF-2023-042</td>
                                    <td>New provider onboarding started</td>
                                    <td>192.168.1.1</td>
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