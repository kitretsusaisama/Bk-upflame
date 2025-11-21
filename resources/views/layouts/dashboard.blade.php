<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Dashboard') - {{ config('app.name') }}</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <link rel="stylesheet" href="{{ asset('css/dashboard.css') }}">
    
    @stack('styles')
</head>
<body>
    <div class="dashboard-wrapper">
        @include('components.sidebar', ['menuItems' => $menuItems ?? []])
        
        <div class="dashboard-main">
            @include('components.topbar', ['user' => $user ?? null])
            
            <main class="dashboard-content">
                @if(session('success'))
                    @include('components.alert', ['type' => 'success', 'message' => session('success')])
                @endif
                
                @if(session('error'))
                    @include('components.alert', ['type' => 'error', 'message' => session('error')])
                @endif
                
                @if($errors->any())
                    @include('components.alert', ['type' => 'error', 'message' => $errors->first()])
                @endif
                
                @yield('content')
            </main>
            
            <footer class="dashboard-footer">
                <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
            </footer>
        </div>
    </div>
    
    <script src="{{ asset('js/dashboard.js') }}"></script>
    @stack('scripts')
</body>
</html>
