<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('messages.eru_library_title') }}</title>

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

            .hero-pattern {
                background-image: url("data:image/svg+xml,%3Csvg width='60' height='60' viewBox='0 0 60 60' xmlns='http://www.w3.org/2000/svg'%3E%3Cg fill='none' fill-rule='evenodd'%3E%3Cg fill='%23263e62' fill-opacity='0.05'%3E%3Cpath d='M36 34v-4h-2v4h-4v2h4v4h2v-4h4v-2h-4zm0-30V0h-2v4h-4v2h4v4h2V6h4V4h-4zM6 34v-4H4v4H0v2h4v4h2v-4h4v-2H6zM6 4V0H4v4H0v2h4v4h2V6h4V4H6z'/%3E%3C/g%3E%3C/g%3E%3C/svg%3E");
            }
        </style>
    </head>
    <body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">
        @include('components.navbar')

        <!-- Hero Section -->
        <section class="hero-pattern bg-blue-50 dark:bg-gray-900 relative" style="background-image: url('{{ asset('img/DJI_0975-1.jpg') }}'); background-size: cover; background-position: center;">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 py-24 text-center relative z-10">
                <h1 class="font-serif text-5xl md:text-6xl font-bold text-[#263e62] dark:text-blue-400 mb-6">{{ __('messages.discover_knowledge') }}</h1>
                <p class="text-lg md:text-xl max-w-3xl mx-auto text-gray-700 dark:text-gray-300 mb-10">{{ __('messages.access_prestigious_collection') }}</p>
                <div class="flex flex-col sm:flex-row justify-center gap-4">
                    <a href="{{ route('books') }}" class="px-8 py-3 bg-[#263e62] text-white rounded-full font-medium hover:bg-[#1d324f] transition duration-300">{{ __('messages.explore_library_catalog') }}</a>
                </div>
            </div>
            <div class="absolute inset-0 bg-white dark:bg-gray-900 opacity-60"></div>
        </section>

        <!-- Featured Books Section -->
        <section id="library" class="py-20 bg-white dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">{{ __('messages.featured_collections') }}</h2>
                    <div class="w-24 h-1 bg-[#263e62] dark:bg-blue-500 mx-auto"></div>
                    <p class="mt-6 text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">{{ __('messages.explore_curated_selection') }}</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
                    @forelse($featuredBooks as $book)
                          @include('components.book-card', [
                            'title'     => $book->title,
                            'authors'   => isset($book->authors) ? (is_array($book->authors) ? implode(', ', $book->authors) : (is_string($book->authors) ? (is_array(json_decode($book->authors)) ? implode(', ', json_decode($book->authors)) : $book->authors) : '')) : (isset($book->author) ? $book->author : ''),
                            'location'  => $book->category->name . ' Section',
                            'status'    => $book->quantity > 0 ? 'available' : 'unavailable',
                            'actionUrl' => route('books.show', $book),
                            'coverImage' => $book->image ? asset($book->image) : null,
                            'format'    => $book->format ?? null,
                            'edition'   => $book->edition ?? null,
                            'bookId'    => $book->id ?? null,
                        ])
                    @empty
                        <p class="col-span-4 text-center text-gray-500">{{ __('messages.no_featured_books') }}</p>
                    @endforelse
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('books') }}" class="inline-flex items-center text-[#263e62] dark:text-blue-400 font-medium hover:underline">
                        View All Books
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Digital Resources Section -->
        <section id="digital-resources" class="py-20 bg-gray-50 dark:bg-gray-800">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Digital Resources</h2>
                    <div class="w-24 h-1 bg-[#263e62] dark:bg-blue-500 mx-auto"></div>
                    <p class="mt-6 text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">Access our extensive collection of digital resources including research papers, audio books, and e-journals.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Research Papers -->
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 dark:bg-blue-900 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#263e62] dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                            </svg>
                        </div>
                        <h3 class="font-serif text-xl font-semibold mb-4 text-center text-gray-900 dark:text-white">Research Papers</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-center mb-6">Access over 50,000 peer-reviewed research papers from leading academics.</p>
                        <div class="text-center">
                            <a href="{{ route('documents.index', ['type' => 'research_paper']) }}" class="inline-flex items-center text-[#263e62] dark:text-blue-400 font-medium hover:underline">
                                Browse Collection
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- Audio Books -->
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-center h-20 w-20 rounded-full bg-red-100 dark:bg-red-900 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#bb0017] dark:text-red-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 017.072 0m-9.9-2.828a9 9 0 0112.728 0M12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z" />
                            </svg>
                        </div>
                        <h3 class="font-serif text-xl font-semibold mb-4 text-center text-gray-900 dark:text-white">Audio Books</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-center mb-6">Enjoy our collection of audio books, perfect for learning on the go.</p>
                        <div class="text-center">
                            <a href="{{ route('documents.index', ['type' => 'audio_book']) }}" class="inline-flex items-center text-[#bb0017] dark:text-red-400 font-medium hover:underline">
                                Listen Now
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>

                    <!-- E-Journals -->
                    <div class="bg-white dark:bg-gray-900 p-8 rounded-lg shadow-md hover:shadow-lg transition duration-300 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-center h-20 w-20 rounded-full bg-blue-100 dark:bg-blue-900 mx-auto mb-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 text-[#263e62] dark:text-blue-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 20H5a2 2 0 01-2-2V6a2 2 0 012-2h10a2 2 0 012 2v1M19 20a2 2 0 002-2V8a2 2 0 00-2-2h-5v10a2 2 0 002 2h3z" />
                            </svg>
                        </div>
                        <h3 class="font-serif text-xl font-semibold mb-4 text-center text-gray-900 dark:text-white">E-Journals</h3>
                        <p class="text-gray-600 dark:text-gray-400 text-center mb-6">Subscribe to our electronic journals from renowned publications worldwide.</p>
                        <div class="text-center">
                            <a href="{{ route('documents.index', ['type' => 'article']) }}" class="inline-flex items-center text-[#263e62] dark:text-blue-400 font-medium hover:underline">
                                View Journals
                                <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                </svg>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

        <!-- Upcoming Events Section -->
        <section id="events" class="py-20 bg-white dark:bg-gray-900">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center mb-16">
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-4">Upcoming Campus Events</h2>
                    <div class="w-24 h-1 bg-[#263e62] dark:bg-blue-500 mx-auto"></div>
                    <p class="mt-6 text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">Join us for enlightening seminars, workshops, and literary discussions happening on campus.</p>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    @forelse($upcomingEvents as $event)
                        <div class="bg-gray-50 dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300 flex flex-col md:flex-row">
                            <div class="bg-[#263e62] dark:bg-blue-800 text-white p-6 flex flex-col items-center justify-center md:w-1/3">
                                <span class="text-3xl font-bold">{{ $event->event_date->format('d') }}</span>
                                <span class="text-xl">{{ $event->event_date->format('M') }}</span>
                                <span class="mt-2">{{ $event->event_date->format('g:i A') }}</span>
                            </div>
                            <div class="p-6 md:w-2/3">
                                <h3 class="font-serif text-xl font-semibold mb-2 text-gray-900 dark:text-white">{{ $event->title }}</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-4">{{ Str::limit($event->description, 100) }}</p>
                                <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
                                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $event->location }}
                                </div>
                                <a href="{{ route('events.show', $event) }}" class="inline-block mt-4 text-[#bb0017] dark:text-red-400 hover:underline font-medium">Register Now</a>
                            </div>
                        </div>
                    @empty
                        <p class="col-span-2 text-center text-gray-500">No upcoming events at the moment.</p>
                    @endforelse
                </div>

                <div class="text-center mt-12">
                    <a href="{{ route('events') }}" class="inline-flex items-center text-[#263e62] dark:text-blue-400 font-medium hover:underline">
                        View All Events
                        <svg xmlns="http://www.w3.org/2000/svg" class="ml-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                        </svg>
                    </a>
                </div>
            </div>
        </section>

        <!-- Library Services Banner -->
        <section class="bg-[#263e62] dark:bg-blue-900 text-white py-16">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8 text-center">
                <h2 class="font-serif text-3xl md:text-4xl font-bold mb-6">{{ __('messages.experience_eru_library') }}</h2>
                <p class="text-lg md:text-xl max-w-3xl mx-auto mb-8">{{ __('messages.serene_environment_study') }}</p>
                <div class="flex flex-wrap justify-center gap-6">
                    <div class="bg-white bg-opacity-10 p-6 rounded-lg text-center w-64 backdrop-filter backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                        </svg>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.books_publications_count') }}</h3>
                        <p>{{ __('messages.books_publications_label') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-10 p-6 rounded-lg text-center w-64 backdrop-filter backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.digital_databases_count') }}</h3>
                        <p>{{ __('messages.digital_databases_label') }}</p>
                    </div>
                    <div class="bg-white bg-opacity-10 p-6 rounded-lg text-center w-64 backdrop-filter backdrop-blur-sm">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-10 w-10 mx-auto mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                        </svg>
                        <h3 class="text-xl font-bold mb-2">{{ __('messages.learning_spaces_count') }}</h3>
                        <p>{{ __('messages.learning_spaces_label') }}</p>
                    </div>
                </div>
            </div>
        </section>

        <!-- Call to Action -->
        <section class="bg-gray-50 dark:bg-gray-800 py-20">
            <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white mb-8">{{ __('messages.ready_to_explore') }}</h2>
                    <div class="flex flex-col sm:flex-row justify-center gap-4">
                        @auth
                            <a href="{{ url('/dashboard') }}" class="px-8 py-3 bg-[#263e62] text-white rounded-full font-medium hover:bg-[#1d324f] transition duration-300">{{ __('messages.access_my_account_button') }}</a>
                        @else
                            <a href="{{ route('register') }}" class="px-8 py-3 bg-[#bb0017] text-white rounded-full font-medium hover:bg-[#a00014] transition duration-300">{{ __('messages.create_account_button') }}</a>
                            <a href="{{ route('login') }}" class="px-8 py-3 bg-white dark:bg-gray-900 text-[#263e62] dark:text-blue-400 border border-[#263e62] dark:border-blue-400 rounded-full font-medium hover:bg-gray-50 dark:hover:bg-gray-700 transition duration-300">{{ __('messages.sign_in_button') }}</a>
                        @endauth
                    </div>
                </div>
            </div>
        </section>

        <!-- Footer -->
        @include('components.footer')

        @if (Route::has('login'))
            <div class="h-14.5 hidden lg:block"></div>
        @endif

    </body>
</html>
