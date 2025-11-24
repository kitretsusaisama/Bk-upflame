@props([
    'variant' => 'primary', // primary, secondary, success, warning, error, outline
    'size' => 'md', // sm, md, lg
    'icon' => null,
    'iconPosition' => 'left', // left, right
    'disabled' => false,
    'href' => null,
    'type' => 'button'
])

@php
$baseClasses = "inline-flex items-center border font-medium focus:outline-none focus:ring-2 focus:ring-offset-2 transition-all duration-200";
$variantClasses = [
    'primary' => 'bg-blue-600 text-white border-transparent hover:bg-blue-700 focus:ring-blue-500',
    'secondary' => 'bg-gray-600 text-white border-transparent hover:bg-gray-700 focus:ring-gray-500',
    'success' => 'bg-green-600 text-white border-transparent hover:bg-green-700 focus:ring-green-500',
    'warning' => 'bg-yellow-600 text-white border-transparent hover:bg-yellow-700 focus:ring-yellow-500',
    'error' => 'bg-red-600 text-white border-transparent hover:bg-red-700 focus:ring-red-500',
    'outline' => 'bg-white text-gray-700 border-gray-300 hover:bg-gray-50 focus:ring-blue-500'
];
$sizeClasses = [
    'sm' => 'px-2.5 py-1.5 text-xs rounded',
    'md' => 'px-4 py-2 text-sm rounded-md',
    'lg' => 'px-6 py-3 text-base rounded-md'
];
$disabledClasses = $disabled ? 'opacity-50 cursor-not-allowed' : '';
$classes = "$baseClasses {$variantClasses[$variant]} {$sizeClasses[$size]} $disabledClasses";
@endphp

@if($href)
    <a href="{{ $href }}" {{ $attributes->merge(['class' => $classes]) }}>
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </a>
@else
    <button 
        type="{{ $type }}" 
        {{ $attributes->merge(['class' => $classes, 'disabled' => $disabled]) }}
    >
        @if($icon && $iconPosition === 'left')
            <i class="{{ $icon }} mr-2"></i>
        @endif
        {{ $slot }}
        @if($icon && $iconPosition === 'right')
            <i class="{{ $icon }} ml-2"></i>
        @endif
    </button>
@endif