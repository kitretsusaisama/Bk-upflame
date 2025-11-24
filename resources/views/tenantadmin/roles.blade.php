@extends('layouts.dashboard')

@section('title', 'Roles & Permissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Roles & Permissions</h4>
                    <button type="button" class="btn btn-primary float-right" data-toggle="modal" data-target="#addRoleModal">
                        <i class="fas fa-plus"></i> Add Role
                    </button>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>Role Name</th>
                                    <th>Description</th>
                                    <th>Permissions</th>
                                    <th>Users</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>Tenant Admin</td>
                                    <td>Full access to tenant management</td>
                                    <td>24 permissions</td>
                                    <td>2 users</td>
                                    <td>
                                        <button class="btn btn-sm btn-info">View</button>
                                        <button class="btn btn-sm btn-warning">Edit</button>
                                        <button class="btn btn-sm btn-danger">Delete</button>
                                    </td>
                                </tr>
                                <tr>
                                    <td>Provider</td>
                                    <td>Access to provider dashboard</td>
                                    <td>8 permissions</td>
                                    <td>5 users</td>
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

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Add New Role</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="roleName">Role Name</label>
                        <input type="text" class="form-control" id="roleName" required>
                    </div>
                    <div class="form-group">
                        <label for="description">Description</label>
                        <textarea class="form-control" id="description" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Role</button>
            </div>
        </div>
    </div>
</div>
@endsection