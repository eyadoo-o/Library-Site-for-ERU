<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('messages.books_title') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|playfair-display:400,600,700" rel="stylesheet" />

        <!-- Styles / Scripts -->
        @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
            @vite(['resources/css/app.css', 'resources/js/app.js'])
        @endif

        <style>
            .font-serif {
                font-family: 'Playfair Display', serif;
            }
        </style>
    </head>
    <body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">
        @include('components.navbar')

        <!-- Page Header -->
        <div class="bg-[#263e62] dark:bg-blue-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-white text-center">
                    <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4">{{ __('messages.library_catalog_header') }}</h1>
                    <p class="text-xl text-blue-100 max-w-3xl mx-auto">{{ __('messages.browse_extensive_collection') }}</p>
                </div>
            </div>
        </div>

        <!-- Search and Filter Section -->
        <div class="bg-gray-100 dark:bg-gray-800 py-6 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <form method="GET" action="{{ route('books') }}" class="flex flex-col md:flex-row gap-4 w-full">
                    <!-- Search Bar -->
                    <div class="flex-1">
                        <div class="relative">
                            <input
                                type="text"
                                name="search"
                                value="{{ request('search') }}"
                                placeholder="{{ __('messages.search_placeholder') }}"
                                class="w-full pl-10 pr-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#263e62] dark:bg-gray-700 dark:text-gray-300"
                            >
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500 absolute left-3 top-3.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                            </svg>
                        </div>
                    </div>

                    <!-- Filter Dropdowns -->
                    <div class="flex gap-2 flex-wrap md:{{ app()->getLocale() == 'ar' ? 'flex-row-reverse' : 'flex-row' }}">
                        <select name="category" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 dark:text-gray-300 {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            <option value="">{{ __('messages.categories_filter') }}</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" @if(request('category') == $id) selected @endif>{{ $name }}</option>
                            @endforeach
                        </select>

                        <select name="availability" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 dark:text-gray-300 {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            <option value="">{{ __('messages.availability_filter') }}</option>
                            <option value="available" @if(request('availability')=='available') selected @endif>{{ __('messages.available_now_option') }}</option>
                            <option value="reserved" @if(request('availability')=='reserved') selected @endif>{{ __('messages.reserved_option') }}</option>
                        </select>

                        <select name="format" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 dark:text-gray-300 {{ app()->getLocale() == 'ar' ? 'text-right' : '' }}">
                            <option value="">{{ __('messages.format_filter') }}</option>
                            <option value="physical" @if(request('format')=='physical') selected @endif>{{ __('messages.physical_format') }}</option>
                            <option value="digital" @if(request('format')=='digital') selected @endif>{{ __('messages.digital_format') }}</option>
                        </select>

                        <button type="submit" class="px-4 py-2 bg-[#263e62] text-white rounded-lg text-sm hover:bg-[#1d324f] transition duration-200">
                            {{ __('messages.apply_filters_button') }}
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Books Grid -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="mb-10">
                    <h2 class="text-2xl font-serif font-bold border-b pb-4 border-gray-200 dark:border-gray-700">{{ __('messages.latest_additions_header') }}</h2>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-8">
                    @foreach($books as $book)
                        @php
                            // Check if authors is already an array before trying to decode
                            $authorsText = '';
                            if (is_array($book->authors)) {
                                $authorsText = implode(', ', $book->authors);
                            } elseif (is_string($book->authors)) {
                                $authorsArray = json_decode($book->authors);
                                $authorsText = is_array($authorsArray) ? implode(', ', $authorsArray) : $book->authors;
                            }

                            // Digital books are always available
                            $status = $book->format === 'digital' ? 'available' : ($book->quantity > 0 ? 'available' : 'unavailable');
                        @endphp
                        @include('components.book-card', [
                            'title' => $book->title,
                            'authors' => $authorsText,
                            'location' => $book->category->name . ' Section',
                            'status' => $status,
                            'actionUrl' => route('books.show', $book),
                            'viewUrl' => $book->format === 'digital' ? route('books.view', $book) : null,
                            'coverImage' => $book->image ? asset($book->image) : null,
                            'format' => $book->format,
                            'edition' => $book->edition,
                            'bookId' => $book->id,
                        ])
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-12 flex justify-center">
                    @if ($books->hasPages())
                        {{ $books->links('vendor.pagination.custom') }}
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('components.footer')
    </body>
</html>
