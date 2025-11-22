@extends('layouts.app')

@section('title', 'Available Services')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Available Services</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-faucet fa-3x text-primary mb-3"></i>
                                    <h5>Plumbing</h5>
                                    <p>Professional plumbing services for residential and commercial properties.</p>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookServiceModal">Book Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-bolt fa-3x text-warning mb-3"></i>
                                    <h5>Electrical</h5>
                                    <p>Expert electrical work including installations, repairs, and maintenance.</p>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookServiceModal">Book Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-hammer fa-3x text-secondary mb-3"></i>
                                    <h5>Carpentry</h5>
                                    <p>Custom carpentry solutions for furniture, repairs, and construction.</p>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookServiceModal">Book Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-paint-roller fa-3x text-info mb-3"></i>
                                    <h5>Painting</h5>
                                    <p>Professional painting services for interior and exterior spaces.</p>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookServiceModal">Book Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-soap fa-3x text-success mb-3"></i>
                                    <h5>Cleaning</h5>
                                    <p>Thorough cleaning services for homes and offices.</p>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookServiceModal">Book Now</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-tree fa-3x text-success mb-3"></i>
                                    <h5>Landscaping</h5>
                                    <p>Garden and landscaping services to enhance your outdoor spaces.</p>
                                    <button class="btn btn-primary" data-toggle="modal" data-target="#bookServiceModal">Book Now</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Book Service Modal -->
<div class="modal fade" id="bookServiceModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Book a Service</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="serviceType">Service Type</label>
                        <select class="form-control" id="serviceType">
                            <option>Plumbing</option>
                            <option>Electrical</option>
                            <option>Carpentry</option>
                            <option>Painting</option>
                            <option>Cleaning</option>
                            <option>Landscaping</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="date">Preferred Date</label>
                        <input type="date" class="form-control" id="date" required>
                    </div>
                    <div class="form-group">
                        <label for="time">Preferred Time</label>
                        <input type="time" class="form-control" id="time" required>
                    </div>
                    <div class="form-group">
                        <label for="address">Service Address</label>
                        <textarea class="form-control" id="address" rows="3" placeholder="Enter your full address"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Request Service</button>
            </div>
        </div>
    </div>
</div>
@endsection