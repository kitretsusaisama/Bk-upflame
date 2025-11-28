@extends('dashboard.layout')

@section('title', 'Create Tenant')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('Create Tenant') }}</h1>
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
                            <input type="text" class="form-control" id="tenantName" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantSlug">{{ __('Slug') }}</label>
                            <input type="text" class="form-control" id="tenantSlug" placeholder="e.g., acme-corp" required>
                            <small class="form-text text-muted">{{ __('This will be used in the tenant URL.') }}</small>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantDomain">{{ __('Primary Domain') }}</label>
                            <input type="text" class="form-control" id="tenantDomain" placeholder="e.g., acme.example.com" required>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantAdminEmail">{{ __('Administrator Email') }}</label>
                            <input type="email" class="form-control" id="tenantAdminEmail" required>
                            <small class="form-text text-muted">{{ __('This will be the email for the tenant administrator account.') }}</small>
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
                        
                        <div class="form-group">
                            <label for="tenantStatus">{{ __('Initial Status') }}</label>
                            <select class="form-control" id="tenantStatus">
                                <option value="pending">{{ __('Pending') }}</option>
                                <option value="active">{{ __('Active') }}</option>
                                <option value="suspended">{{ __('Suspended') }}</option>
                            </select>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('Create Tenant') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection