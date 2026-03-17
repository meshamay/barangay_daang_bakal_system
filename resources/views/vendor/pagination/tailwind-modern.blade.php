<div class="w-full flex justify-center">
<nav role="navigation" aria-label="Pagination Navigation" class="flex items-center gap-2 bg-white rounded-xl shadow-md px-6 py-3 mt-6">
        {{-- Previous Page Link --}}
        @if ($paginator->onFirstPage())
            <span class="inline-flex items-center px-4 py-2 text-base font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed select-none">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Prev
            </span>
        @else
            <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="inline-flex items-center px-4 py-2 text-base font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 transition">
                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" /></svg>
                Prev
            </a>
        @endif

        {{-- Pagination Elements --}}
        @foreach ($elements as $element)
            {{-- "Three Dots" Separator --}}
            @if (is_string($element))
                <span class="inline-flex items-center px-4 py-2 text-base font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-lg select-none">{{ $element }}</span>
            @endif

            {{-- Array Of Links --}}
            @if (is_array($element))
                @foreach ($element as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="inline-flex items-center px-4 py-2 text-base font-bold text-white bg-blue-600 border border-blue-600 rounded-lg shadow select-none">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="inline-flex items-center px-4 py-2 text-base font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 transition">{{ $page }}</a>
                    @endif
                @endforeach
            @endif
        @endforeach

        {{-- Next Page Link --}}
        @if ($paginator->hasMorePages())
            <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="inline-flex items-center px-4 py-2 text-base font-semibold text-gray-700 bg-white border border-gray-200 rounded-lg hover:bg-blue-50 transition">
                Next
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </a>
        @else
            <span class="inline-flex items-center px-4 py-2 text-base font-semibold text-gray-400 bg-gray-100 border border-gray-200 rounded-lg cursor-not-allowed select-none">
                Next
                <svg class="w-4 h-4 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" /></svg>
            </span>
        @endif
    </nav>
</div>