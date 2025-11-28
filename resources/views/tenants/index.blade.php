@extends('dashboard.layout')

@section('title', 'Tenants')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('Tenants') }}</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createTenantModal">
                    {{ __('Create Tenant') }}
                </button>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ __('Search tenants...') }}">
                        </div>
                        <div class="col-md-6">
                            <select class="form-control">
                                <option>{{ __('All Statuses') }}</option>
                                <option>{{ __('Active') }}</option>
                                <option>{{ __('Pending') }}</option>
                                <option>{{ __('Suspended') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <x-table :headers="['Name', 'Domain', 'Status', 'Subscription', 'Actions']">
                        <tr>
                            <td>Acme Corporation</td>
                            <td>acme.example.com</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>Premium</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Suspend') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Globex Incorporated</td>
                            <td>globex.example.com</td>
                            <td><span class="badge badge-warning">Pending</span></td>
                            <td>Basic</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-success">{{ __('Activate') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Wayne Enterprises</td>
                            <td>wayne.example.com</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>Enterprise</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Suspend') }}</button>
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

<!-- Create Tenant Modal -->
<div class="modal fade" id="createTenantModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Create New Tenant') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="tenantName">{{ __('Tenant Name') }}</label>
                        <input type="text" class="form-control" id="tenantName" required>
                    </div>
                    <div class="form-group">
                        <label for="tenantDomain">{{ __('Domain') }}</label>
                        <input type="text" class="form-control" id="tenantDomain" placeholder="e.g., acme.example.com" required>
                    </div>
                    <div class="form-group">
                        <label for="tenantSubscription">{{ __('Subscription Tier') }}</label>
                        <select class="form-control" id="tenantSubscription">
                            <option>Free</option>
                            <option>Basic</option>
                            <option>Premium</option>
                            <option>Enterprise</option>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary">{{ __('Create Tenant') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection