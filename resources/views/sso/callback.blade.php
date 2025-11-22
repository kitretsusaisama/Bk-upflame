@extends('layouts.auth')

@section('title', 'SSO Callback')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">{{ __('Processing SSO Login') }}</div>

                <div class="card-body">
                    <div class="text-center">
                        <div class="spinner-border" role="status">
                            <span class="sr-only">{{ __('Loading...') }}</span>
                        </div>
                        <p class="mt-2">{{ __('Please wait while we process your SSO login...') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection