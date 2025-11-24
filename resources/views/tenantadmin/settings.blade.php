@extends('layouts.dashboard')

@section('title', 'Settings')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tenant Settings</h4>
                </div>
                <div class="card-body">
                    <form>
                        <div class="form-group">
                            <label for="tenantName">Tenant Name</label>
                            <input type="text" class="form-control" id="tenantName" value="Acme Corporation">
                        </div>
                        <div class="form-group">
                            <label for="tenantDomain">Primary Domain</label>
                            <input type="text" class="form-control" id="tenantDomain" value="acme.example.com">
                        </div>
                        <div class="form-group">
                            <label for="contactEmail">Contact Email</label>
                            <input type="email" class="form-control" id="contactEmail" value="admin@acme.example.com">
                        </div>
                        <div class="form-group">
                            <label for="timezone">Timezone</label>
                            <select class="form-control" id="timezone">
                                <option>UTC</option>
                                <option>America/New_York</option>
                                <option>America/Los_Angeles</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="language">Language</label>
                            <select class="form-control" id="language">
                                <option>English</option>
                                <option>Spanish</option>
                                <option>French</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Save Settings</button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection