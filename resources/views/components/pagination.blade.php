@props(['paginator'])

<div class="flex items-center justify-between border-t border-gray-200 bg-white px-4 py-3 sm:px-6">
    <div class="flex flex-1 justify-between sm:hidden">
        @if($paginator->previousPageUrl())
            <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Previous
            </a>
        @else
            <span class="relative inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                Previous
            </span>
        @endif

        @if($paginator->nextPageUrl())
            <a href="{{ $paginator->nextPageUrl() }}" class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-white px-4 py-2 text-sm font-medium text-gray-700 hover:bg-gray-50">
                Next
            </a>
        @else
            <span class="relative ml-3 inline-flex items-center rounded-md border border-gray-300 bg-gray-100 px-4 py-2 text-sm font-medium text-gray-500 cursor-not-allowed">
                Next
            </span>
        @endif
    </div>
    <div class="hidden sm:flex sm:flex-1 sm:items-center sm:justify-between">
        <div>
            <p class="text-sm text-gray-700">
                Showing
                <span class="font-medium">{{ $paginator->firstItem() }}</span>
                to
                <span class="font-medium">{{ $paginator->lastItem() }}</span>
                of
                <span class="font-medium">{{ $paginator->total() }}</span>
                results
            </p>
        </div>
        <div>
            <nav class="isolate inline-flex -space-x-px rounded-md shadow-sm" aria-label="Pagination">
                @if($paginator->previousPageUrl())
                    <a href="{{ $paginator->previousPageUrl() }}" class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Previous</span>
                        <i class="ti ti-chevron-left"></i>
                    </a>
                @else
                    <span class="relative inline-flex items-center rounded-l-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 bg-gray-100 cursor-not-allowed">
                        <span class="sr-only">Previous</span>
                        <i class="ti ti-chevron-left"></i>
                    </span>
                @endif

                @foreach($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if($page == $paginator->currentPage())
                        <span class="relative z-10 inline-flex items-center px-4 py-2 text-sm font-semibold text-blue-600 bg-blue-100 ring-1 ring-inset ring-blue-600 focus:z-20 focus-visible:outline focus-visible:outline-2 focus-visible:outline-offset-2 focus-visible:outline-blue-600">
                            {{ $page }}
                        </span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-semibold text-gray-900 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                            {{ $page }}
                        </a>
                    @endif
                @endforeach

                @if($paginator->nextPageUrl())
                    <a href="{{ $paginator->nextPageUrl() }}" class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-400 ring-1 ring-inset ring-gray-300 hover:bg-gray-50 focus:z-20 focus:outline-offset-0">
                        <span class="sr-only">Next</span>
                        <i class="ti ti-chevron-right"></i>
                    </a>
                @else
                    <span class="relative inline-flex items-center rounded-r-md px-2 py-2 text-gray-300 ring-1 ring-inset ring-gray-300 bg-gray-100 cursor-not-allowed">
                        <span class="sr-only">Next</span>
                        <i class="ti ti-chevron-right"></i>
                    </span>
                @endif
            </nav>
        </div>
    </div>
</div>