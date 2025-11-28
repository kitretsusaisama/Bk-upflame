@extends('dashboard.layout')

@section('title', 'System Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <h1>{{ __('System Settings') }}</h1>
            <p>{{ __('Manage global system configuration and settings.') }}</p>
        </div>
    </div>
    
    <div class="row">
        <div class="col-md-3">
            <div class="list-group">
                <a href="#" class="list-group-item list-group-item-action active">
                    {{ __('General Settings') }}
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    {{ __('Email Configuration') }}
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    {{ __('Payment Settings') }}
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    {{ __('Security') }}
                </a>
                <a href="#" class="list-group-item list-group-item-action">
                    {{ __('Maintenance') }}
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
                            <label for="siteName">{{ __('Site Name') }}</label>
                            <input type="text" class="form-control" id="siteName" value="Multi-Tenant Platform">
                        </div>
                        
                        <div class="form-group">
                            <label for="siteEmail">{{ __('Site Email') }}</label>
                            <input type="email" class="form-control" id="siteEmail" value="admin@example.com">
                        </div>
                        
                        <div class="form-group">
                            <label for="timezone">{{ __('Default Timezone') }}</label>
                            <select class="form-control" id="timezone">
                                <option>UTC</option>
                                <option selected>America/New_York</option>
                                <option>America/Los_Angeles</option>
                                <option>Europe/London</option>
                                <option>Asia/Tokyo</option>
                            </select>
                        </div>
                        
                        <div class="form-group">
                            <label for="maintenanceMode">{{ __('Maintenance Mode') }}</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="maintenanceMode">
                                <label class="custom-control-label" for="maintenanceMode">{{ __('Enable maintenance mode') }}</label>
                            </div>
                        </div>
                        
                        <div class="form-group">
                            <label for="debugMode">{{ __('Debug Mode') }}</label>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input" id="debugMode">
                                <label class="custom-control-label" for="debugMode">{{ __('Enable debug mode') }}</label>
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