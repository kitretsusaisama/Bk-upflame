@extends('layouts.dashboard')

@section('title', 'Provider Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">My Services</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addServiceModal">
                        <i class="fas fa-plus"></i> Add Service
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Service Name</th>
                                    <th>Description</th>
                                    <th>Price</th>
                                    <th>Duration</th>
                                    <th>Status</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Cardiology Checkup</td>
                                    <td>Comprehensive heart health assessment</td>
                                    <td>$150.00</td>
                                    <td>30 mins</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Dermatology Consultation</td>
                                    <td>Skin health evaluation and treatment</td>
                                    <td>$120.00</td>
                                    <td>45 mins</td>
                                    <td><span class="badge badge-success">Active</span></td>
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

<!-- Add Service Modal -->
<div class="modal fade" id="addServiceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Service</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="serviceName">Service Name</label>
                        <input type="text" class="form-control" id="serviceName" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                    <div class="form-group">
                        <label for="price">Price ($)</label>
                        <input type="number" class="form-control" id="price" step="0.01" required>
                    </div>
                    <div class="form-group">
                        <label for="duration">Duration (minutes)</label>
                        <input type="number" class="form-control" id="duration" required>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Service</button>
            </div>
        </div>
    </div>
</div>
@endsection