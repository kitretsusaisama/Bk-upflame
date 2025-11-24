@extends('layouts.dashboard')

@section('title', 'Ops Approvals')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Pending Approvals</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Request ID</th>
                                    <th>Type</th>
                                    <th>Requested By</th>
                                    <th>Description</th>
                                    <th>Submitted</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>REQ-2023-001</td>
                                    <td>Provider Onboarding</td>
                                    <td>Dr. John Smith</td>
                                    <td>Medical license verification</td>
                                    <td>3 hours ago</td>
                                    <td>
                                        <button class="btn btn-sm btn-success">Approve</button>
                                        <button class="btn btn-sm btn-danger">Reject</button>
                                        <button class="btn btn-sm btn-info">View Details</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>REQ-2023-002</td>
                                    <td>Booking Modification</td>
                                    <td>Jane Doe</td>
                                    <td>Reschedule cardiology appointment</td>
                                    <td>1 day ago</td>
                                    <td>
                                        <button class="btn btn-sm btn-success">Approve</button>
                                        <button class="btn btn-sm btn-danger">Reject</button>
                                        <button class="btn btn-sm btn-info">View Details</button>
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