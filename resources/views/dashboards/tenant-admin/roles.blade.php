@extends('dashboard.layout')

@section('title', 'Manage Roles')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('Manage Roles') }}</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createRoleModal">
                    {{ __('Create New Role') }}
                </button>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ __('Search roles...') }}">
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <x-table :headers="['Name', 'Description', 'Users', 'Permissions', 'Actions']">
                        <tr>
                            <td>Admin</td>
                            <td>Full access to tenant resources</td>
                            <td>3</td>
                            <td>24</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Manager</td>
                            <td>Can manage users and content</td>
                            <td>5</td>
                            <td>12</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>User</td>
                            <td>Regular user with basic permissions</td>
                            <td>34</td>
                            <td>6</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Delete') }}</button>
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
                            <li class="page-item"><a class="page-link" href="#">{{ __('Next') }}</a></li>
                        </ul>
                    </nav>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Create Role Modal -->
<div class="modal fade" id="createRoleModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New Role') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="roleName">{{ __('Role Name') }}</label>
                        <input type="text" class="form-control" id="roleName" required>
                    </div>
                    <div class="form-group">
                        <label for="roleDescription">{{ __('Description') }}</label>
                        <textarea class="form-control" id="roleDescription" rows="3"></textarea>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary">{{ __('Create Role') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection