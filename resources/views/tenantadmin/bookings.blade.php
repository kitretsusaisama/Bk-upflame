@extends('layouts.dashboard')

@section('title', 'Bookings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Bookings</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addBookingModal">
                        <i class="fas fa-plus"></i> Add Booking
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Booking ID</th>
                                    <th>Customer</th>
                                    <th>Provider</th>
                                    <th>Service</th>
                                    <th>Date & Time</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#BK-001</td>
                                    <td>John Customer</td>
                                    <td>Dr. Jane Smith</td>
                                    <td>Cardiology Checkup</td>
                                    <td>Dec 15, 2023 10:00 AM</td>
                                    <td><span class="badge badge-success">Confirmed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                        <button class="btn btn-sm btn-danger">Cancel</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#BK-002</td>
                                    <td>Jane Doe</td>
                                    <td>Dr. John Wilson</td>
                                    <td>Dermatology Consultation</td>
                                    <td>Dec 16, 2023 2:30 PM</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-warning">Edit</button>
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

<!-- Add Booking Modal -->
<div class="modal fade" id="addBookingModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Booking</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="customer">Customer</label>
                        <select class="form-control" id="customer">
                            <option>John Customer</option>
                            <option>Jane Doe</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="provider">Provider</label>
                        <select class="form-control" id="provider">
                            <option>Dr. Jane Smith</option>
                            <option>Dr. John Wilson</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service">Service</label>
                        <select class="form-control" id="service">
                            <option>Cardiology Checkup</option>
                            <option>Dermatology Consultation</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="datetime">Date & Time</label>
                        <input type="datetime-local" class="form-control" id="datetime">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Booking</button>
            </div>
        </div>
    </div>
</div>
@endsection