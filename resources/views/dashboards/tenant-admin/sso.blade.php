@extends('dashboard.layout')

@section('title', 'SSO Configuration')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-md-12">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h1>{{ __('SSO Configuration') }}</h1>
                <button class="btn btn-primary" data-toggle="modal" data-target="#createSsoProviderModal">
                    {{ __('Add SSO Provider') }}
                </button>
            </div>
            
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-md-6">
                            <input type="text" class="form-control" placeholder="{{ __('Search SSO providers...') }}">
                        </div>
                        <div class="col-md-6">
                            <select class="form-control">
                                <option>{{ __('All Types') }}</option>
                                <option>{{ __('OAuth 2.0') }}</option>
                                <option>{{ __('SAML 2.0') }}</option>
                                <option>{{ __('OpenID Connect') }}</option>
                            </select>
                        </div>
                    </div>
                </div>
                
                <div class="card-body">
                    <x-table :headers="['Provider', 'Type', 'Status', 'Users', 'Actions']">
                        <tr>
                            <td>Google Workspace</td>
                            <td>OAuth 2.0</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>24</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Disable') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>Microsoft Azure AD</td>
                            <td>SAML 2.0</td>
                            <td><span class="badge badge-success">Active</span></td>
                            <td>18</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-danger">{{ __('Disable') }}</button>
                            </td>
                        </tr>
                        <tr>
                            <td>GitHub Enterprise</td>
                            <td>OAuth 2.0</td>
                            <td><span class="badge badge-secondary">Inactive</span></td>
                            <td>0</td>
                            <td>
                                <button class="btn btn-sm btn-info">{{ __('View') }}</button>
                                <button class="btn btn-sm btn-warning">{{ __('Edit') }}</button>
                                <button class="btn btn-sm btn-success">{{ __('Enable') }}</button>
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

<!-- Create SSO Provider Modal -->
<div class="modal fade" id="createSsoProviderModal" tabindex="-1" role="dialog">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ __('Add SSO Provider') }}</h5>
                <button type="button" class="close" data-dismiss="modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <label for="providerName">{{ __('Provider Name') }}</label>
                        <input type="text" class="form-control" id="providerName" required>
                    </div>
                    <div class="form-group">
                        <label for="providerType">{{ __('Provider Type') }}</label>
                        <select class="form-control" id="providerType">
                            <option>OAuth 2.0</option>
                            <option>SAML 2.0</option>
                            <option>OpenID Connect</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="clientId">{{ __('Client ID') }}</label>
                        <input type="text" class="form-control" id="clientId">
                    </div>
                    <div class="form-group">
                        <label for="clientSecret">{{ __('Client Secret') }}</label>
                        <input type="password" class="form-control" id="clientSecret">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-dismiss="modal">{{ __('Cancel') }}</button>
                <button type="button" class="btn btn-primary">{{ __('Add Provider') }}</button>
            </div>
        </div>
    </div>
</div>
@endsection