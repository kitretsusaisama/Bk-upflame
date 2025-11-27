<ul role="list" class="flex flex-1 flex-col gap-y-7">
    @foreach($menuItems ?? [] as $group)
        <li>
            @if(!empty($group['group']) && $group['group'] !== 'Other')
                <div class="text-xs font-semibold leading-6 text-gray-400 uppercase tracking-wider mb-2">{{ $group['group'] }}</div>
            @endif

            <ul role="list" class="-mx-2 space-y-1">
                @foreach($group['items'] as $item)
                    <li>
                        <a href="{{ $item['url'] ?? '#' }}" 
                           class="{{ $item['active'] ? 'bg-gray-800 text-white' : 'text-gray-400 hover:text-white hover:bg-gray-800' }} group flex gap-x-3 rounded-md p-2 text-sm leading-6 font-semibold transition-colors">
                            @if(isset($item['icon']))
                                <span class="iconify h-6 w-6 shrink-0" data-icon="{{ $item['icon'] }}"></span>
                            @endif
                            {{ $item['label'] }}
                        </a>
                    </li>
                @endforeach
            </ul>
        </li>
    @endforeach

    <li class="mt-auto">
        <a href="#" onclick="logoutModalOpen = true; return false;" class="group -mx-2 flex gap-x-3 rounded-md p-2 text-sm font-semibold leading-6 text-gray-400 hover:bg-gray-800 hover:text-white transition-colors">
            <span class="iconify h-6 w-6 shrink-0" data-icon="heroicons:arrow-right-on-rectangle"></span>
            Log out
        </a>
    </li>
</ul>
