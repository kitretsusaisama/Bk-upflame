@extends('layouts.app')

@section('title', 'Support')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Customer Support</h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-question-circle fa-3x text-primary mb-3"></i>
                                    <h5>FAQs</h5>
                                    <p>Browse our frequently asked questions for quick answers.</p>
                                    <button class="btn btn-primary">View FAQs</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-comments fa-3x text-success mb-3"></i>
                                    <h5>Live Chat</h5>
                                    <p>Chat with our support team in real-time.</p>
                                    <button class="btn btn-success">Start Chat</button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body text-center">
                                    <i class="fas fa-envelope fa-3x text-info mb-3"></i>
                                    <h5>Email Support</h5>
                                    <p>Send us an email and we'll get back to you within 24 hours.</p>
                                    <button class="btn btn-info">Send Email</button>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Submit a Support Ticket</h5>
                                </div>
                                <div class="card-body">
                                    <form>
                                        <div class="form-group">
                                            <label for="subject">Subject</label>
                                            <input type="text" class="form-control" id="subject" placeholder="Brief description of your issue">
                                        </div>
                                        <div class="form-group">
                                            <label for="category">Category</label>
                                            <select class="form-control" id="category">
                                                <option>Billing</option>
                                                <option>Booking</option>
                                                <option>Provider</option>
                                                <option>Technical</option>
                                                <option>Account</option>
                                                <option>Other</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="priority">Priority</label>
                                            <select class="form-control" id="priority">
                                                <option>Low</option>
                                                <option>Medium</option>
                                                <option>High</option>
                                                <option>Urgent</option>
                                            </select>
                                        </div>
                                        <div class="form-group">
                                            <label for="description">Description</label>
                                            <textarea class="form-control" id="description" rows="5" placeholder="Please provide detailed information about your issue"></textarea>
                                        </div>
                                        <div class="form-group">
                                            <label for="attachment">Attachment</label>
                                            <input type="file" class="form-control-file" id="attachment">
                                        </div>
                                        <button type="submit" class="btn btn-primary">Submit Ticket</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5>Recent Tickets</h5>
                                </div>
                                <div class="card-body">
                                    <div class="table-responsive">
                                        <table class="table table-striped">
                                            <thead>
                                                <tr>
                                                    <th>Ticket ID</th>
                                                    <th>Subject</th>
                                                    <th>Category</th>
                                                    <th>Date</th>
                                                    <th>Status</th>
                                                    <th>Actions</th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                <tr>
                                                    <td>#TKT001</td>
                                                    <td>Issue with booking confirmation</td>
                                                    <td>Booking</td>
                                                    <td>2025-11-20</td>
                                                    <td><span class="badge badge-success">Resolved</span></td>
                                                    <td>
                                                        <button class="btn btn-sm btn-info">View</button>
                                                    </td>
                                                </tr>
                                                <tr>
                                                    <td>#TKT002</td>
                                                    <td>Payment not processed</td>
                                                    <td>Billing</td>
                                                    <td>2025-11-21</td>
                                                    <td><span class="badge badge-warning">In Progress</span></td>
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
            </div>
        </div>
    </div>
</div>
@endsection