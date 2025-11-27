@extends('dashboard.layout')

@section('page-title', 'Dashboard')

@section('content')
<div style="margin-bottom: 24px;">
    <h2 style="font-size: 20px; margin-bottom: 8px;">Welcome back, {{ auth()->user()->email }}</h2>
    <p style="color: #666;">Here's what's happening with your account today.</p>
</div>

<!-- Stats Grid -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 20px; margin-bottom: 32px;">
    @foreach($stats as $key => $value)
        <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <div style="color: #7f8c8d; font-size: 13px; text-transform: uppercase; margin-bottom: 8px;">
                {{ str_replace('_', ' ', $key) }}
            </div>
            <div style="font-size: 32px; font-weight: 600; color: #2c3e50;">
                {{ is_numeric($value) ? number_format($value) : $value }}
            </div>
        </div>
    @endforeach
</div>

<!-- Widgets -->
@if(isset($widgets) && count($widgets) > 0)
    <div style="margin-top: 32px;">
        <h3 style="font-size: 18px; margin-bottom: 16px;">Your Widgets</h3>
        
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(300px, 1fr)); gap: 20px;">
            @foreach($widgets as $widget)
                <div style="background: white; padding: 24px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                    <h4 style="font-size: 16px; margin-bottom: 12px;">{{ $widget['label'] }}</h4>
                    <p style="color: #7f8c8d; font-size: 14px;">
                        Widget: {{ $widget['component'] }}
                    </p>
                    <!-- Widget content would be loaded dynamically here -->
                </div>
            @endforeach
        </div>
    </div>
@endif
@endsection
