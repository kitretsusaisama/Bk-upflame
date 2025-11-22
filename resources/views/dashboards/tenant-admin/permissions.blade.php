@extends('layouts.dashboard')

@section('title', 'Role Permissions')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('Manage Role Permissions') }}</h1>
                <a href="{{ route('tenant-admin.roles') }}" class="btn btn-secondary">
                    {{ __('Back to Roles') }}
                </a>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h3>{{ __('Admin Role Permissions') }}</h3>
                    <p>{{ __('Manage permissions assigned to the Admin role.') }}</p>
                </div>
                
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <h5>{{ __('User Management') }}</h5>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="viewUsers" checked>
                                <label class="custom-control-label" for="viewUsers">{{ __('View Users') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="createUsers" checked>
                                <label class="custom-control-label" for="createUsers">{{ __('Create Users') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="editUsers" checked>
                                <label class="custom-control-label" for="editUsers">{{ __('Edit Users') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="deleteUsers" checked>
                                <label class="custom-control-label" for="deleteUsers">{{ __('Delete Users') }}</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>{{ __('Role Management') }}</h5>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="viewRoles" checked>
                                <label class="custom-control-label" for="viewRoles">{{ __('View Roles') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="createRoles" checked>
                                <label class="custom-control-label" for="createRoles">{{ __('Create Roles') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="editRoles" checked>
                                <label class="custom-control-label" for="editRoles">{{ __('Edit Roles') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="deleteRoles" checked>
                                <label class="custom-control-label" for="deleteRoles">{{ __('Delete Roles') }}</label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row mt-4">
                        <div class="col-md-6">
                            <h5>{{ __('Provider Management') }}</h5>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="viewProviders" checked>
                                <label class="custom-control-label" for="viewProviders">{{ __('View Providers') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="createProviders" checked>
                                <label class="custom-control-label" for="createProviders">{{ __('Create Providers') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="editProviders" checked>
                                <label class="custom-control-label" for="editProviders">{{ __('Edit Providers') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="deleteProviders" checked>
                                <label class="custom-control-label" for="deleteProviders">{{ __('Delete Providers') }}</label>
                            </div>
                        </div>
                        
                        <div class="col-md-6">
                            <h5>{{ __('Booking Management') }}</h5>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="viewBookings" checked>
                                <label class="custom-control-label" for="viewBookings">{{ __('View Bookings') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="createBookings" checked>
                                <label class="custom-control-label" for="createBookings">{{ __('Create Bookings') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="editBookings" checked>
                                <label class="custom-control-label" for="editBookings">{{ __('Edit Bookings') }}</label>
                            </div>
                            <div class="custom-control custom-checkbox">
                                <input type="checkbox" class="custom-control-input" id="deleteBookings" checked>
                                <label class="custom-control-label" for="deleteBookings">{{ __('Delete Bookings') }}</label>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="card-footer">
                    <button type="button" class="btn btn-primary">{{ __('Save Permissions') }}</button>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection