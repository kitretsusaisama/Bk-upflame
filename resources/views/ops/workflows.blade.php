@extends('layouts.app')

@section('title', 'Workflows')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Workflows</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addWorkflowModal">
                        <i class="fas fa-plus"></i> Add Workflow
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Workflow Name</th>
                                    <th>Description</th>
                                    <th>Status</th>
                                    <th>Steps</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Customer Onboarding</td>
                                    <td>Process for new customer registration</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>5</td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Provider Verification</td>
                                    <td>Verification process for new providers</td>
                                    <td><span class="badge badge-success">Active</span></td>
                                    <td>4</td>
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

<!-- Add Workflow Modal -->
<div class="modal fade" id="addWorkflowModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Workflow</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="workflowName">Workflow Name</label>
                        <input type="text" class="form-control" id="workflowName" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Workflow</button>
            </div>
        </div>
    </div>
</div>
@endsection