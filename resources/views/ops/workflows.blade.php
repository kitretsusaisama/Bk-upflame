@extends('layouts.dashboard')

@section('title', 'Ops Workflows')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Workflow Management</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Workflow ID</th>
                                    <th>Type</th>
                                    <th>Entity</th>
                                    <th>Status</th>
                                    <th>Created</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>WF-2023-001</td>
                                    <td>Provider Onboarding</td>
                                    <td>Dr. John Smith</td>
                                    <td><span class="badge badge-warning">Pending Approval</span></td>
                                    <td>2 hours ago</td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-success">Approve</button>
                                        <button class="btn btn-sm btn-danger">Reject</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>WF-2023-002</td>
                                    <td>Booking Request</td>
                                    <td>Cardiology Appointment</td>
                                    <td><span class="badge badge-info">In Progress</span></td>
                                    <td>1 day ago</td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection