@props([
    'variant' => 'primary', // primary, secondary, success, warning, error, info
    'size' => 'md', // sm, md, lg
    'rounded' => 'full' // full, md, none
])

@php
$baseClasses = "inline-flex items-center font-medium";
$variantClasses = [
    'primary' => 'bg-blue-100 text-blue-800',
    'secondary' => 'bg-gray-100 text-gray-800',
    'success' => 'bg-green-100 text-green-800',
    'warning' => 'bg-yellow-100 text-yellow-800',
    'error' => 'bg-red-100 text-red-800',
    'info' => 'bg-blue-100 text-blue-800'
];
$sizeClasses = [
    'sm' => 'text-xs px-2 py-0.5',
    'md' => 'text-sm px-2.5 py-0.5',
    'lg' => 'text-base px-3 py-1'
];
$roundedClasses = [
    'full' => 'rounded-full',
    'md' => 'rounded-md',
    'none' => 'rounded-none'
];
$classes = "$baseClasses {$variantClasses[$variant]} {$sizeClasses[$size]} {$roundedClasses[$rounded]}";
@endphp

<span {{ $attributes->merge(['class' => $classes]) }}>
    {{ $slot }}
</span>