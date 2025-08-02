<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ $event->title }} - {{ __('messages.event_details') }}</title>

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
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">
    @include('components.navbar')

    <!-- Event Header -->
    <div class="bg-[#263e62] dark:bg-blue-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-white text-center">
                <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4">{{ $event->title }}</h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">
                    {{ Str::limit($event->short_description ?? $event->description, 150) }}
                </p>
            </div>
        </div>
    </div>

    <!-- Event Content -->
    <div class="py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                <!-- Event Details -->
                <div class="md:flex">
                    <!-- Event Image (if available) -->
                    @if(isset($event->image) && $event->image)
                        <div class="md:w-1/3">
                            <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-full object-cover object-center">
                        </div>
                    @endif

                    <!-- Event Information -->
                    <div class="md:w-2/3 p-6">
                        <h2 class="text-2xl font-serif font-bold mb-4">{{ __('messages.event_details') }}</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">
                                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <strong>{{ __('messages.date') }}:</strong> {{ $event->event_date->format('F d, Y g:i A') }}
                                </p>

                                @if($event->location)
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        <strong>{{ __('messages.location') }}:</strong> {{ $event->location }}
                                    </p>
                                @endif
                            </div>

                            <div>
                                <p class="text-gray-600 dark:text-gray-400 mb-2">
                                    <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <strong>{{ __('messages.status') }}:</strong>
                                    <span class="{{ $event->status == 'published' ? 'text-green-600' : ($event->status == 'cancelled' ? 'text-red-600' : 'text-yellow-600') }}">
                                        {{ __('messages.' . $event->status) }}
                                    </span>
                                </p>

                                @if($event->max_attendees > 0)
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                        </svg>
                                        <strong>{{ __('messages.max_attendees') }}:</strong> {{ $event->max_attendees }}
                                    </p>
                                @endif

                                @if(isset($event->organizer) && $event->organizer)
                                    <p class="text-gray-600 dark:text-gray-400 mb-2">
                                        <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                        </svg>
                                        <strong>Organizer:</strong> {{ $event->organizer }}
                                    </p>
                                @endif
                            </div>
                        </div>

                        <!-- Event Description -->
                        <div class="mt-6">
                            <h3 class="text-lg font-semibold mb-2">About This Event</h3>
                            <div class="text-gray-700 dark:text-gray-300 leading-relaxed prose max-w-none">
                                {{ $event->description }}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Registration Section -->
            @auth
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6">
                    <h3 class="text-xl font-serif font-bold mb-4">{{ __('messages.register_for_event') }}</h3>

                    @if(session('success'))
                        <p class="text-green-600 dark:text-green-400 mb-4">
                            <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                            {{ session('success') }}
                        </p>
                    @endif

                    @if($event->status == 'published' && $event->event_date > now())
                        @if(!isset($userRegistered) || !$userRegistered)
                            <form action="{{ route('events.register', ['event' => $event->id]) }}" method="POST">
                                @csrf
                                <button type="submit" class="px-6 py-3 bg-[#bb0017] text-white rounded-lg text-sm font-medium hover:bg-[#a00014] transition duration-200">
                                    {{ __('messages.register_now') }}
                                </button>
                            </form>
                        @else
                            <p class="text-green-600 dark:text-green-400">
                                <svg class="inline w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                                {{ __('messages.already_registered') }}
                            </p>
                        @endif
                    @else
                        <p class="text-gray-600 dark:text-gray-400">{{ __('messages.registration_closed') }}</p>
                    @endif
                </div>
            @else
                <div class="mt-8 bg-white dark:bg-gray-800 rounded-lg shadow-lg p-6 text-center">
                    <p class="text-gray-600 dark:text-gray-400">
                        <a href="{{ route('login') }}" class="text-[#bb0017] hover:underline">{{ __('messages.login_to_register') }}</a>
                    </p>
                </div>
            @endauth

            <!-- Related Events (if available) -->
            @if(isset($relatedEvents) && count($relatedEvents) > 0)
                <div class="mt-12">
                    <h2 class="text-2xl font-serif font-bold mb-6">Related Events</h2>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        @foreach($relatedEvents as $relatedEvent)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden">
                                <a href="{{ route('events.show', $relatedEvent->id) }}">
                                    <div class="p-4">
                                        <p class="text-sm text-gray-500">{{ $relatedEvent->event_date->format('F d, Y') }}</p>
                                        <h3 class="font-semibold text-lg mt-1">{{ $relatedEvent->title }}</h3>
                                    </div>
                                </a>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    </div>

    @include('components.footer')
</body>
</html>
