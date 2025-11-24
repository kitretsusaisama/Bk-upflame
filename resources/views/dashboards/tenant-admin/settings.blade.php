@extends('layouts.dashboard')

@section('title', 'Tenant Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('Tenant Settings') }}</h1>
            <p>{{ __('Manage your tenant configuration and preferences.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action active">
                    {{ __('General Settings') }}
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    {{ __('Authentication') }}
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    {{ __('SSO Configuration') }}
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    {{ __('Email Settings') }}
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    {{ __('Notification Preferences') }}
                </a>
            </div>
        </div>
        
        <div class="col-md-9">
            <div class="card">
                <div class="card-header">
                    <h5>{{ __('General Settings') }}</h5>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="tenantName">{{ __('Tenant Name') }}</label>
                            <input type="text" class="form-control" id="tenantName" value="Acme Corporation">
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantDomain">{{ __('Primary Domain') }}</label>
                            <input type="text" class="form-control" id="tenantDomain" value="acme.example.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantTimezone">{{ __('Timezone') }}</label>
                            <select class="form-control" id="tenantTimezone">
                                <option>America/New_York</option>
                                <option selected>America/Los_Angeles</option>
                                <option>Europe/London</option>
                                <option>Asia/Tokyo</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantLocale">{{ __('Default Language') }}</label>
                            <select class="form-control" id="tenantLocale">
                                <option selected>English</option>
                                <option>Spanish</option>
                                <option>French</option>
                                <option>German</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="tenantLogo">{{ __('Logo') }}</label>
                            <div class="custom-file">
                                <input type="file" class="custom-file-input" id="tenantLogo">
                                <label class="custom-file-label" for="tenantLogo">{{ __('Choose file') }}</label>
                            </div>
                        </div>
                        
                        <button type="submit" class="btn btn-primary">{{ __('Save Settings') }}</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection