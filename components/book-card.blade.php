<div class="bg-gray-50 dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300">
    <div class="h-64 bg-gray-200 dark:bg-gray-700 relative">
        @if(isset($status))
            <div class="absolute top-3 right-3 {{
                $status === 'available' ? 'bg-green-500' :
                ($status === 'unavailable' ? 'bg-[#bb0017]' : 'bg-[#263e62]')
            }} text-white text-xs font-bold px-2 py-1 rounded">
                {{ $status === 'available' ? __('messages.status_available') : ($status === 'unavailable' ? __('messages.status_unavailable') : ucfirst($status)) }}
            </div>
        @endif

        @if(isset($format))
            <div class="absolute top-3 left-3 bg-gray-800 text-white text-xs font-bold px-2 py-1 rounded">
                {{ ucfirst($format) }}
            </div>
        @endif

        <div class="flex items-center justify-center h-full">
            @if(isset($coverImage) && $coverImage)
                <img src="{{ $coverImage }}" alt="{{ $title ?? __('messages.book_cover_alt') }}" class="h-full w-full object-cover">
            @else
                <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                </svg>
            @endif
        </div>
    </div>
    <div class="p-6">
        <h3 class="font-serif text-xl font-semibold mb-2 text-gray-900 dark:text-white">{{ $title ?? __('messages.book_title_placeholder') }}</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-2">{{ $authors ?? __('messages.unknown_author') }}</p>

        @if(isset($edition))
            <p class="text-sm text-gray-500 dark:text-gray-500 mb-3">{{ __('messages.edition') }}: {{ $edition }}</p>
        @endif

        <div class="flex justify-between items-center">
            <span class="text-sm text-gray-500 dark:text-gray-500">{{ $location ?? __('messages.location_not_specified') }}</span>
            @if(isset($format) && $format === 'digital')
                <a href="{{ $viewUrl ?? route('books.view', $bookId ?? 0) }}" class="bg-[#263e62] text-white px-3 py-1 rounded text-sm hover:bg-[#1d324f] transition">
                    {{ __('messages.view_online') }}
                </a>
            @elseif(isset($status) && $status === 'available')
                <a href="{{ $actionUrl ?? '#' }}" class="text-[#bb0017] dark:text-red-400 hover:underline text-sm font-medium">{{ __('messages.view_details_link') }}</a>
            @else
                <a href="{{ $actionUrl ?? '#' }}" class="text-[#bb0017] dark:text-red-400 hover:underline text-sm font-medium">{{ $actionText ?? __('messages.reserve_link') }}</a>
            @endif
        </div>
    </div>
</div>
