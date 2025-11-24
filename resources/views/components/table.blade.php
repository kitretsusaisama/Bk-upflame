@props([
    'headers' => [],
    'striped' => false,
    'hover' => true,
    'responsive' => true,
    'sticky' => false
])

<div @class([
    'overflow-x-auto' => $responsive,
    'rounded-lg border border-gray-200 shadow-sm' => true
])>
    <table class="min-w-full divide-y divide-gray-200">
        @if(count($headers))
            <thead @class([
                'bg-gray-50' => !$sticky,
                'sticky top-0 bg-white z-10 shadow-sm' => $sticky
            ])>
                <tr>
                    @foreach($headers as $header)
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            {{ $header }}
                        </th>
                    @endforeach
                </tr>
            </thead>
        @endif
        <tbody @class([
            'bg-white divide-y divide-gray-200' => true,
            'divide-y divide-gray-200' => $striped,
        ])>
            {{ $slot }}
        </tbody>
    </table>
</div>