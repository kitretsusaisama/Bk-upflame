@props([
    'label' => null,
    'options' => [],
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
            <select 
                {{ $attributes->merge(['class' => 'focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 pr-3 py-2 border-gray-300 rounded-md']) }}
                @if($required) required @endif
            >
                @if($placeholder)
                    <option value="">{{ $placeholder }}</option>
                @endif
                @foreach($options as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        @else
            <select 
                {{ $attributes->merge(['class' => 'focus:ring-blue-500 focus:border-blue-500 block w-full px-3 py-2 border-gray-300 rounded-md']) }}
                @if($required) required @endif
            >
                @if($placeholder)
                    <option value="">{{ $placeholder }}</option>
                @endif
                @foreach($options as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
        @endif
    </div>

    @if($help)
        <p class="mt-1 text-sm text-gray-500">{{ $help }}</p>
    @endif

    @if($error)
        <p class="mt-1 text-sm text-red-600">{{ $error }}</p>
    @endif
</div>