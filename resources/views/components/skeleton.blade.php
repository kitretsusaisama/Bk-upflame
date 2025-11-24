@props([
    'type' => 'text', // text, circle, rectangle, table
    'width' => 'full', // full, 1/2, 1/3, 1/4, or specific value like '200px'
    'height' => 'auto', // auto, or specific value like '20px'
    'count' => 1,
    'class' => ''
])

@php
$baseClasses = "bg-gray-200 rounded animate-pulse";
$widthClasses = [
    'full' => 'w-full',
    '1/2' => 'w-1/2',
    '1/3' => 'w-1/3',
    '1/4' => 'w-1/4',
];
$widthClass = $widthClasses[$width] ?? $width;
$heightClass = $height !== 'auto' ? $height : '';
$classes = "$baseClasses $widthClass $heightClass $class";
@endphp

@if($type === 'table')
    <div class="space-y-4">
        @for($i = 0; $i < $count; $i++)
            <div class="flex space-x-4">
                <div class="rounded-full bg-gray-200 h-10 w-10 animate-pulse"></div>
                <div class="flex-1 space-y-2">
                    <div class="h-4 bg-gray-200 rounded w-3/4 animate-pulse"></div>
                    <div class="h-4 bg-gray-200 rounded w-1/2 animate-pulse"></div>
                </div>
            </div>
        @endfor
    </div>
@elseif($type === 'circle')
    <div class="{{ $classes }} rounded-full" style="{{ $height !== 'auto' ? 'height: ' . $height . ';' : '' }}"></div>
@else
    @for($i = 0; $i < $count; $i++)
        <div class="{{ $classes }}"></div>
    @endfor
@endif