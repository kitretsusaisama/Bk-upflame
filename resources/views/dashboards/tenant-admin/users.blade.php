@extends('dashboard.layout')

@section('title', 'Manage Users')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('Manage Users') }}</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createUserModal">
                    {{ __('Create New User') }}
                </button>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ __('Search users...') }}">
                        </div>
                        <div class="col-md-6">
                            <select class="form-control">
                                <option>{{ __('All Roles') }}</option>
                                <option>{{ __('Admin') }}</option>
                                <option>{{ __('Manager') }}</option>
                                <option>{{ __('User') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <x-table :headers="['Name', 'Email', 'Role', 'Status', 'Actions']">
                        <tr>
                            <td>John Doe</td>
                            <td>john.doe@example.com</td>
                            <td>Admin</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Deactivate') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Jane Roe</td>
                            <td>jane.roe@example.com</td>
                            <td>Manager</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Deactivate') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Bob Smith</td>
                            <td>bob.smith@example.com</td>
                            <td>User</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-success">{{ __('Activate') }}</button>
                            </td>
                        </tr>
                    </x-table>
                </div>
                
                <div class="card-footer">
                    <nav>
                        <ul class="pagination justify-content-center">
                            <li class="page-item disabled"><span class="page-link">{{ __('Previous') }}</span></li>
                            <li class="page-item active"><span class="page-link">1</span></li>
                            <li class="page-item"><a class="page-link" href="#">2</a></li>
                            <li class="page-item"><a class="page-link" href="#">3</a></li>
                            <li class="page-item"><a class="page-link" href="#">{{ __('Next') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create User Modal -->
<div class="modal fade" id="createUserModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New User') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="userName">{{ __('Full Name') }}</label>
                        <input type="text" class="form-control" id="userName" required>
                    </div>
                    <div class="form-group">
                        <label for="userEmail">{{ __('Email Address') }}</label>
                        <input type="email" class="form-control" id="userEmail" required>
                    </div>
                    <div class="form-group">
                        <label for="userRole">{{ __('Role') }}</label>
                        <select class="form-control" id="userRole">
                            <option>Admin</option>
                            <option>Manager</option>
                            <option>User</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary">{{ __('Create User') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection