@props([
    'title' => null,
    'subtitle' => null,
    'actions' => null,
    'footer' => null,
    'shadow' => true,
    'bordered' => true
])

<div {{ $attributes->merge(['class' => 'bg-white rounded-lg ' . ($shadow ? 'shadow-sm' : '') . ' ' . ($bordered ? 'border border-gray-200' : '')]) }}>
    @if($title || $actions)
        <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex justify-between items-center">
                <div>
                    @if($title)
                        <h3 class="text-lg font-medium text-gray-900">{{ $title }}</h3>
                    @endif
                    @if($subtitle)
                        <p class="mt-1 text-sm text-gray-500">{{ $subtitle }}</p>
                    @endif
                </div>
                @if($actions)
                    <div>
                        {{ $actions }}
                    </div>
                @endif
            </div>
        </div>
    @endif

    <div class="p-6">
        {{ $slot }}
    </div>

    @if($footer)
        <div class="px-6 py-4 bg-gray-50 border-t border-gray-200 rounded-b-lg">
            {{ $footer }}
        </div>
    @endif
</div>