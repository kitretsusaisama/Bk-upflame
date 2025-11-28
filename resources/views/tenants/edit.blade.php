@extends('dashboard.layout')

@section('title', 'Edit Tenant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('Edit Tenant') }}</h1>
                <a href="{{ route('tenants.index') }}" class="btn btn-secondary">
                    {{ __('Back to Tenants') }}
                </a>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('Tenant Information') }}</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="tenantName">{{ __('Tenant Name') }}</label>
                            <input type="text" class="form-control" id="tenantName" value="Acme Corporation" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantSlug">{{ __('Slug') }}</label>
                            <input type="text" class="form-control" id="tenantSlug" value="acme-corp" required>
                            <small class="form-text text-muted">{{ __('This will be used in the tenant URL.') }}</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantDomain">{{ __('Primary Domain') }}</label>
                            <input type="text" class="form-control" id="tenantDomain" value="acme.example.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantSubscription">{{ __('Subscription Tier') }}</label>
                            <select class="form-control" id="tenantSubscription">
                                <option>Free</option>
                                <option selected>Premium</option>
                                <option>Enterprise</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantStatus">{{ __('Status') }}</label>
                            <select class="form-control" id="tenantStatus">
                                <option>Pending</option>
                                <option selected>Active</option>
                                <option>Suspended</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantExpiresAt">{{ __('Expiration Date') }}</label>
                            <input type="date" class="form-control" id="tenantExpiresAt" value="2025-01-20">
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('Update Tenant') }}</button>
                    </form>
                </div>
            </div>
            
            <div class="card mt-4">
                <div class="card-header">
                    <h5>{{ __('Danger Zone') }}</h5>
                </div>
                <div class="card-body">
                    <p>{{ __('Deleting a tenant will permanently remove all associated data. This action cannot be undone.') }}</p>
                    <button class="btn btn-danger" data-toggle="modal" data-target="#deleteTenantModal">
                        {{ __('Delete Tenant') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Delete Tenant Modal -->
<div class="modal fade" id="deleteTenantModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Delete Tenant') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <p>{{ __('Are you sure you want to delete this tenant? This will permanently remove all associated data including users, providers, bookings, and workflows.') }}</p>
                <p>{{ __('Please type the tenant name to confirm:') }}</p>
                <input type="text" class="form-control" id="confirmTenantName" placeholder="Acme Corporation">
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-danger">{{ __('Delete Tenant') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection