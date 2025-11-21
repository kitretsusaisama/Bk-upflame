@php
    $types = [
        'success' => 'badge-success',
        'error' => 'badge-error',
        'warning' => 'badge-warning',
        'info' => 'badge-info',
        'primary' => 'badge-primary',
        'secondary' => 'badge-secondary',
    ];
    
    $badgeClass = $types[$type ?? 'primary'] ?? 'badge-primary';
@endphp

<span class="badge {{ $badgeClass }} {{ $class ?? '' }}">
    {{ $slot }}
</span>
