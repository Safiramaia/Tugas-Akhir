@if ($paginator->hasPages())
    <nav role="navigation" aria-label="Pagination" class="flex items-center justify-between mt-2">
        <!-- Tombol navigasi Pagination -->
        <div>
            <span class="relative z-0 inline-flex shadow-sm">
                {{-- Tombol Previous --}}
                @if ($paginator->onFirstPage())
                    <span class="opacity-50 cursor-default px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-l-md">&lsaquo;</span>
                @else
                    <a href="{{ $paginator->previousPageUrl() }}" rel="prev" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-l-md hover:bg-gray-200 focus:outline-none focus:ring focus:ring-gray-300">&lsaquo;</a>
                @endif

                {{-- Halaman --}}
                @foreach ($paginator->getUrlRange(1, $paginator->lastPage()) as $page => $url)
                    @if ($page == $paginator->currentPage())
                        <span class="z-10 inline-flex items-center px-4 py-2 text-sm font-medium text-white bg-gray-600 border border-gray-600">{{ $page }}</span>
                    @else
                        <a href="{{ $url }}" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 hover:bg-gray-200 focus:outline-none focus:ring focus:ring-gray-300">{{ $page }}</a>
                    @endif
                @endforeach

                {{-- Tombol Next --}}
                @if ($paginator->hasMorePages())
                    <a href="{{ $paginator->nextPageUrl() }}" rel="next" class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-600 bg-white border border-gray-300 rounded-r-md hover:bg-gray-200 focus:outline-none focus:ring focus:ring-gray-300">&rsaquo;</a>
                @else
                    <span class="opacity-50 cursor-default px-4 py-2 text-sm font-medium text-gray-400 bg-white border border-gray-300 rounded-r-md">&rsaquo;</span>
                @endif
            </span>
        </div>
    </nav>
@endif
