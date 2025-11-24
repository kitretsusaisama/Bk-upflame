@props([
    'id',
    'title',
    'size' => 'md', // sm, md, lg, xl
    'show' => false
])

<div x-data="{ open: $show }" 
     x-show="open" 
     class="fixed inset-0 z-50 overflow-y-auto"
     style="display: none;"
     x-cloak>
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div x-show="open" 
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100"
             x-transition:leave-end="opacity-0"
             class="fixed inset-0 transition-opacity" 
             aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div x-show="open"
             x-transition:enter="ease-out duration-300"
             x-transition:enter-start="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             x-transition:enter-end="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave="ease-in duration-200"
             x-transition:leave-start="opacity-100 translate-y-0 sm:scale-100"
             x-transition:leave-end="opacity-0 translate-y-4 sm:translate-y-0 sm:scale-95"
             class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle {{ $size === 'lg' ? 'sm:max-w-3xl' : ($size === 'xl' ? 'sm:max-w-5xl' : ($size === 'sm' ? 'sm:max-w-sm' : 'sm:max-w-lg')) }} sm:w-full"
             role="dialog" 
             aria-modal="true" 
             aria-labelledby="{{ $id }}-title">
            
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center pb-3 border-b">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="{{ $id }}-title">
                                {{ $title }}
                            </h3>
                            <button @click="open = false" 
                                    type="button" 
                                    class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <i class="ti ti-x text-2xl"></i>
                            </button>
                        </div>
                        <div class="mt-4">
                            {{ $slot }}
                        </div>
                    </div>
                </div>
            </div>
            
            @if(isset($footer))
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                {{ $footer }}
            </div>
            @endif
        </div>
    </div>
</div>