@extends('layouts.dashboard')

@section('title', 'Provider Bookings')

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
                                    <th>Customer</th>
                                    <th>Service</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#BK-101</td>
                                    <td>John Customer</td>
                                    <td>Cardiology Checkup</td>
                                    <td>Dec 15, 2023 10:00 AM</td>
                                    <td><span class="badge badge-success">Confirmed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-warning">Reschedule</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#BK-102</td>
                                    <td>Jane Doe</td>
                                    <td>Dermatology Consultation</td>
                                    <td>Dec 16, 2023 2:30 PM</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-success">Confirm</button>
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