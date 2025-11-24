@props([
    'label' => null,
    'type' => 'text',
    'placeholder' => null,
    'help' => null,
    'error' => null,
    'required' => false,
    'icon' => null
])

<div>
    @if($label)
        <label class="block text-sm font-medium text-gray-700 mb-1">
            {{ $label }}
            @if($required)
                <span class="text-red-500">*</span>
            @endif
        </label>
    @endif

    <div class="relative rounded-md shadow-sm">
        @if($icon)
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <i class="{{ $icon }} text-gray-400"></i>
            </div>
            <input 
                {{ $attributes->merge(['type' => $type, 'placeholder' => $placeholder, 'class' => 'focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md']) }}
                @if($required) required @endif
            >
        @else
            <input 
                {{ $attributes->merge(['type' => $type, 'placeholder' => $placeholder, 'class' => 'focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2 border-gray-300 rounded-md']) }}
                @if($required) required @endif
            >
        @endif
    </div>

    @if($help)
        <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>