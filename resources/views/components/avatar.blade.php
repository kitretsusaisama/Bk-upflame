@props([
    'src' => null,
    'name' => null,
    'size' => 'md', // sm, md, lg, xl
    'rounded' => 'full', // full, md, none
    'variant' => 'primary' // primary, secondary, success, warning, error, info
])

@php
$sizeClasses = [
    'sm' => 'h-8 w-8',
    'md' => 'h-10 w-10',
    'lg' => 'h-12 w-12',
    'xl' => 'h-16 w-16'
];

$roundedClasses = [
    'full' => 'rounded-full',
    'md' => 'rounded-md',
    'none' => 'rounded-none'
];

$variantClasses = [
    'primary' => 'bg-blue-500 text-white',
    'secondary' => 'bg-gray-500 text-white',
    'success' => 'bg-green-500 text-white',
    'warning' => 'bg-yellow-500 text-white',
    'error' => 'bg-red-500 text-white',
    'info' => 'bg-blue-500 text-white'
];

$sizeClass = $sizeClasses[$size] ?? $sizeClasses['md'];
$roundedClass = $roundedClasses[$rounded] ?? $roundedClasses['full'];
$variantClass = $variantClasses[$variant] ?? $variantClasses['primary'];
$classes = "$sizeClass $roundedClass $variantClass flex items-center justify-center font-medium";
@endphp

@if($src)
    <img src="{{ $src }}" {{ $attributes->merge(['class' => "$sizeClass $roundedClass"]) }} alt="{{ $name ?? 'Avatar' }}">
@else
    <div {{ $attributes->merge(['class' => $classes]) }}>
        @if($name)
            {{ strtoupper(substr($name, 0, 1)) }}
        @else
            <i class="ti ti-user"></i>
        @endif
    </div>
@endif