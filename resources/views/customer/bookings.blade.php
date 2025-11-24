@extends('layouts.dashboard')

@section('title', 'My Bookings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">My Bookings</h4>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Service</th>
                                    <th>Provider</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>BK-2023-001</td>
                                    <td>Cardiology Checkup</td>
                                    <td>Dr. John Smith</td>
                                    <td>Dec 15, 2023 10:00 AM</td>
                                    <td><span class="badge badge-success">Confirmed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View Details</button>
                                        <button class="btn btn-sm btn-warning">Reschedule</button>
                                        <button class="btn btn-sm btn-danger">Cancel</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>BK-2023-002</td>
                                    <td>Dermatology Consultation</td>
                                    <td>Dr. Jane Wilson</td>
                                    <td>Dec 20, 2023 2:30 PM</td>
                                    <td><span class="badge badge-info">Pending</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View Details</button>
                                        <button class="btn btn-sm btn-warning">Modify</button>
                                        <button class="btn btn-sm btn-danger">Cancel</button>
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