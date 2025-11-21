@php
    $types = [
        'success' => ['class' => 'alert-success', 'icon' => '✓'],
        'error' => ['class' => 'alert-error', 'icon' => '✕'],
        'warning' => ['class' => 'alert-warning', 'icon' => '⚠'],
        'info' => ['class' => 'alert-info', 'icon' => 'ℹ'],
    ];
    
    $alertType = $types[$type ?? 'info'];
@endphp

<div class="alert {{ $alertType['class'] }} {{ $class ?? '' }}" role="alert">
    <span class="alert-icon">{{ $alertType['icon'] }}</span>
    <div class="alert-content">
        {{ $message ?? $slot }}
    </div>
    <button class="alert-close" onclick="this.parentElement.remove()">×</button>
</div>
