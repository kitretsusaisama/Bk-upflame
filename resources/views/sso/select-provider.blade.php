@extends('layouts.auth')

@section('title', 'Select SSO Provider')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Select SSO Provider') }}</div>

                <div class="card-body">
                    <p>{{ __('Please select an SSO provider to continue:') }}</p>
                    
                    <div class="providers-list">
                        <!-- SSO providers will be listed here -->
                        <div class="provider-option">
                            <a href="{{ route('sso.login', 'google') }}" class="btn btn-primary btn-block">
                                {{ __('Login with Google') }}
                            </a>
                        </div>
                        
                        <div class="provider-option">
                            <a href="{{ route('sso.login', 'azure') }}" class="btn btn-info btn-block">
                                {{ __('Login with Azure AD') }}
                            </a>
                        </div>
                        
                        <div class="provider-option">
                            <a href="{{ route('sso.login', 'github') }}" class="btn btn-dark btn-block">
                                {{ __('Login with GitHub') }}
                            </a>
                        </div>
                    </div>
                    
                    <div class="mt-3 text-center">
                        <a href="{{ route('login') }}">{{ __('Or use email and password') }}</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection