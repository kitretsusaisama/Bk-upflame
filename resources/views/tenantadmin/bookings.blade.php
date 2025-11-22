@extends('layouts.app')

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
                                    <th>Date</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>#BK001</td>
                                    <td>John Doe</td>
                                    <td>Jane Smith</td>
                                    <td>Plumbing</td>
                                    <td>2025-12-01</td>
                                    <td><span class="badge badge-warning">Pending</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>#BK002</td>
                                    <td>Jane Doe</td>
                                    <td>John Smith</td>
                                    <td>Electrical</td>
                                    <td>2025-12-02</td>
                                    <td><span class="badge badge-success">Confirmed</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
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
                            <option>John Doe</option>
                            <option>Jane Doe</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="provider">Provider</label>
                        <select class="form-control" id="provider">
                            <option>Jane Smith</option>
                            <option>John Smith</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="service">Service</label>
                        <select class="form-control" id="service">
                            <option>Plumbing</option>
                            <option>Electrical</option>
                            <option>Carpentry</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Date</label>
                        <input type="date" class="form-control" id="date" required>
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