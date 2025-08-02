<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <!-- ...existing head (fonts, styles, etc.)... -->
    <title>{{ $book->title }} – {{ __('messages.eru_library_short_title') }}</title>
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased min-h-screen flex flex-col">
    @include('components.navbar')

    @php
        if ($book->created_at->gt(now()->subDays(30))) {
            $statusText = __('messages.status_new_arrival');
            $statusClass = 'text-blue-600 dark:text-blue-400';
        } elseif ($book->quantity > 0) {
            $statusText = __('messages.status_available_now');
            $statusClass = 'text-green-600 dark:text-green-400';
        } else {
            $statusText = __('messages.status_out_of_stock');
            $statusClass = 'text-red-600 dark:text-red-400';
        }
    @endphp

    <div class="py-12 flex-grow flex items-center justify-center">
        <div class="max-w-4xl mx-auto bg-white dark:bg-gray-800 shadow-lg rounded-lg overflow-hidden">
            <div class="md:flex">
                @if($book->cover_url)
                    <img src="{{ $book->cover_url }}"
                         alt="{{ $book->title }}"
                         class="w-full md:w-1/3 h-auto object-cover">
                @endif

                <div class="p-6 flex-1">
                    <h1 class="text-3xl font-serif font-bold mb-2">{{ $book->title }}</h1>
                    <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $book->author }}</p>

                    <div class="grid grid-cols-2 gap-4 mb-4 text-sm">
                        <div><span class="font-semibold">{{ __('messages.book_category_label') }}:</span> {{ $book->category->name }}</div>
                        <div><span class="font-semibold">{{ __('messages.book_isbn_label') }}:</span> {{ $book->isbn }}</div>
                        <div><span class="font-semibold">{{ __('messages.book_format_label') }}:</span> {{ ucfirst($book->format) }}</div>
                        <div><span class="font-semibold">{{ __('messages.book_added_label') }}:</span> {{ $book->created_at->format('M d, Y') }}</div>
                    </div>

                    <p class="mb-6"><span class="font-semibold">{{ __('messages.book_status_label') }}:</span>
                        <span class="{{ $statusClass }}">{{ $statusText }}</span>
                    </p>

                    @if (session('success'))
                        <div class="mb-4 p-4 bg-green-100 dark:bg-green-900 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-300 rounded">
                            {{ session('success') }}
                        </div>
                    @endif
                    @if (session('warning'))
                        <div class="mb-4 p-4 bg-yellow-100 dark:bg-yellow-900 border border-yellow-400 dark:border-yellow-600 text-yellow-700 dark:text-yellow-300 rounded">
                            {{ session('warning') }}
                        </div>
                    @endif
                    @if (session('error'))
                        <div class="mb-4 p-4 bg-red-100 dark:bg-red-900 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-300 rounded">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="flex gap-4">
                        <a href="{{ route('books') }}"
                           class="px-4 py-2 bg-gray-300 dark:bg-gray-700 text-gray-800 dark:text-gray-200 rounded-lg hover:bg-gray-400 transition">
                            ← {{ __('messages.back_to_catalog_button') }}
                        </a>
                        @if($book->quantity <= 0)
                            @php
                                $alreadyReserved = \App\Models\Reservation::where('book_id', $book->id)
                                    ->where('user_id', auth()->id())
                                    ->where('status', 'pending')
                                    ->exists();
                            @endphp

                            @if($alreadyReserved)
                                <span class="px-4 py-2 bg-red-100 dark:bg-red-900 text-red-700 dark:text-red-300 rounded-lg cursor-not-allowed">
                                    {{ __('messages.already_reserved_button') }}
                                </span>
                            @else
                                <form method="POST" action="{{ route('reservations.storeFromBook', $book) }}">
                                    @csrf
                                    <button type="submit"
                                        class="px-4 py-2 bg-[#263e62] text-white rounded-lg hover:bg-[#1d324f] transition">
                                        {{ __('messages.reserve_now_button') }}
                                    </button>
                                </form>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            @if($book->description)
                <div class="p-6 border-t border-gray-200 dark:border-gray-700 bg-gray-50 dark:bg-gray-900">
                    <h2 class="text-xl font-semibold mb-2">{{ __('messages.book_description_label') }}</h2>
                    <p class="prose dark:prose-dark">{{ $book->description }}</p>
                </div>
            @endif
        </div>
    </div>

    <div class="py-8"></div>
    @include('components.footer')
</body>
</html>
