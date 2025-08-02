<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('messages.events') }} - {{ __('messages.eru_library_title') }}</title>

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
            /* Accordion animation for add event form */
            .accordion-content {
                max-height: 0;
                overflow: hidden;
                transition: max-height 0.5s ease-out;
            }
            .accordion-content.open {
                max-height: 1000px;
            }
        </style>
    </head>
    <body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">
        @include('components.navbar')

        <!-- Page Header -->
        <div class="bg-[#bb0017] dark:bg-red-900 py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-white text-center">
                    <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4">{{ __('messages.campus_events') }}</h1>
                    <p class="text-xl text-red-100 max-w-3xl mx-auto">{{ __('messages.join_us_for_events') }}</p>
                </div>
            </div>
        </div>

        <!-- Filter Section -->
        <div class="bg-gray-100 dark:bg-gray-800 py-6 border-b border-gray-200 dark:border-gray-700">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <form action="{{ route('events') }}" method="GET" id="filterForm">
                    <div class="flex flex-col md:flex-row justify-between gap-4">
                        <!-- Date Range Filter -->
                        <div class="flex flex-wrap gap-4 items-center">
                            <span class="text-gray-700 dark:text-gray-300 font-medium">{{ __('messages.filter_by_date') }}:</span>
                            <div class="flex flex-wrap gap-2">
                                <button type="button" onclick="setDateRange('this_week')"
                                    class="px-4 py-2 {{ request('date_range') == 'this_week' ? 'bg-[#263e62] text-white' : 'bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300' }} rounded-full text-sm hover:bg-[#1d324f] hover:text-white transition duration-200">
                                    This Week
                                </button>
                                <button type="button" onclick="setDateRange('this_month')"
                                    class="px-4 py-2 {{ request('date_range') == 'this_month' ? 'bg-[#263e62] text-white' : 'bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300' }} rounded-full text-sm hover:bg-[#1d324f] hover:text-white transition duration-200">
                                    This Month
                                </button>
                                <button type="button" onclick="setDateRange('next_month')"
                                    class="px-4 py-2 {{ request('date_range') == 'next_month' ? 'bg-[#263e62] text-white' : 'bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 text-gray-700 dark:text-gray-300' }} rounded-full text-sm hover:bg-[#1d324f] hover:text-white transition duration-200">
                                    Next Month
                                </button>
                                <input type="hidden" name="date_range" id="date_range" value="{{ request('date_range') }}">
                            </div>
                        </div>

                        <!-- Event Types Filter -->
                        <div class="flex items-center gap-2">
                            <select name="event_type" class="px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm bg-white dark:bg-gray-700 dark:text-gray-300">
                                <option value="">{{ __('messages.all_event_types') }}</option>
                                <option value="public" {{ request('event_type') == 'public' ? 'selected' : '' }}>{{ __('messages.public_events') }}</option>
                                <option value="private" {{ request('event_type') == 'private' ? 'selected' : '' }}>{{ __('messages.private_events') }}</option>
                                <option value="workshop" {{ request('event_type') == 'workshop' ? 'selected' : '' }}>{{ __('messages.workshop') }}</option>
                                <option value="seminar" {{ request('event_type') == 'seminar' ? 'selected' : '' }}>{{ __('messages.seminar') }}</option>
                                <option value="reading" {{ request('event_type') == 'reading' ? 'selected' : '' }}>{{ __('messages.book_reading') }}</option>
                                <option value="discussion" {{ request('event_type') == 'discussion' ? 'selected' : '' }}>{{ __('messages.panel_discussion') }}</option>
                            </select>
                            <button type="submit" class="px-4 py-2 bg-[#bb0017] text-white rounded-lg text-sm hover:bg-[#a00014] transition duration-200">Apply</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Main Content -->
        <div class="py-12">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">

                @if (session('success'))
                    <div class="mb-6 bg-green-100 dark:bg-green-800 border border-green-400 dark:border-green-600 text-green-700 dark:text-green-200 px-4 py-3 rounded-lg relative" role="alert">
                        <span class="block sm:inline font-medium">{{ session('success') }}</span>
                    </div>
                @endif

                @if (session('error'))
                    <div class="mb-6 bg-red-100 dark:bg-red-800 border border-red-400 dark:border-red-600 text-red-700 dark:text-red-200 px-4 py-3 rounded-lg relative" role="alert">
                        <span class="block sm:inline font-medium">{{ session('error') }}</span>
                    </div>
                @endif

                <!-- Add Event Button -->
                @auth
                    @if(in_array(Auth::user()->type, ['admin', 'faculty_staff', 'student_activity_coordinator']))
                        <div class="mb-8 text-right">
                            <button onclick="toggleAddEventModal()" class="px-6 py-3 bg-[#bb0017] hover:bg-[#a00014] text-white rounded-md transition-colors font-medium">
                                Add New Event
                            </button>
                        </div>
                    @endif
                @endauth

                <!-- Add Event Modal -->
                <div id="addEventModal" class="fixed inset-0 z-50 hidden bg-black bg-opacity-50 flex items-center justify-center">
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg w-full max-w-2xl p-6">
                        <div class="flex justify-between items-center mb-4">
                            <h2 class="text-2xl font-serif font-bold">Add New Event</h2>
                            <button onclick="toggleAddEventModal()" class="text-gray-500 hover:text-gray-700 dark:text-gray-400 dark:hover:text-gray-200">
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        @php
                            $storeRoute = Auth::check() ?
                                (Auth::user()->type === 'admin' ? route('admin.events.store') : route('user.events.store'))
                                : route('user.events.store');
                        @endphp
                        <form action="{{ $storeRoute }}" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                                <div>
                                    <label for="title" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Title *</label>
                                    <input type="text" id="title" name="title" required class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                <div>
                                    <label for="event_date" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Event Date *</label>
                                    <input type="datetime-local" id="event_date" name="event_date" required class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Description</label>
                                    <textarea id="description" name="description" rows="4" class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300"></textarea>
                                </div>
                                <div>
                                    <label for="location" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Location</label>
                                    <input type="text" id="location" name="location" class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                <div>
                                    <label for="image" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Image</label>
                                    <input type="file" id="image" name="image" class="block w-full text-sm text-gray-500 dark:text-gray-400 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-[#263e62] file:text-white hover:file:bg-[#1d324f] dark:file:bg-gray-700 dark:file:text-gray-300 dark:hover:file:bg-gray-600">
                                </div>
                                <div>
                                    <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                                    <select id="status" name="status" class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300">
                                        <option value="draft">Draft</option>
                                        <option value="published" selected>Published</option>
                                        <option value="cancelled">Cancelled</option>
                                    </select>
                                </div>
                                <div>
                                    <label for="max_attendees" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Max Attendees (0 for unlimited)</label>
                                    <input type="number" id="max_attendees" name="max_attendees" min="0" value="0" class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300">
                                </div>
                                <div class="md:col-span-2">
                                    <label for="event_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Event Type</label>
                                    <select id="event_type" name="event_type" class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300">
                                        <option value="public" selected>Public</option>
                                        <option value="private">Private</option>
                                        <option value="workshop">Workshop</option>
                                        <option value="seminar">Seminar</option>
                                        <option value="reading">Book Reading</option>
                                        <option value="discussion">Panel Discussion</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mt-6 flex justify-end">
                                <button type="button" onclick="toggleAddEventModal()" class="mr-3 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                    Cancel
                                </button>
                                <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#263e62] hover:bg-[#1d324f]">
                                    Add Event
                                </button>
                            </div>
                        </form>
                    </div>
                </div>

                <!-- This week's events -->
                @php
                    $showThisWeekSection = !request()->filled('date_range') || in_array(request('date_range'), ['this_week', 'this_month']);
                @endphp
                @if($showThisWeekSection)
                <div class="mb-16">
                    <h2 class="text-2xl font-serif font-bold mb-8 pb-2 border-b border-gray-200 dark:border-gray-700">This Week</h2>
                    @if($thisWeekEvents->isNotEmpty())
                        <div class="space-y-8">
                            @foreach($thisWeekEvents as $event)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden md:flex">
                                    <div class="md:w-1/3 bg-[#263e62] text-white p-6 flex flex-col justify-center items-center text-center">
                                        <span class="text-5xl font-bold">{{ $event->event_date->format('d') }}</span>
                                        <span class="text-xl">{{ $event->event_date->format('M') }}</span>
                                    </div>
                                    <div class="md:w-2/3 p-6">
                                        <h3 class="text-xl font-serif font-bold mb-2 text-[#263e62] dark:text-blue-400">{{ $event->title }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                                            <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $event->event_date->format('g:i A') }}
                                        </p>
                                        @if($event->location)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                                            <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $event->location }}
                                        </p>
                                        @endif
                                        <p class="text-gray-700 dark:text-gray-300 mb-4 text-sm leading-relaxed h-16 overflow-y-auto">{{ Str::limit($event->description, 200) }}</p>
                                        <a href="{{ route('events.show', $event) }}" class="inline-block px-4 py-2 bg-[#bb0017] text-white text-sm font-medium rounded-md hover:bg-[#a00014] transition-colors">
                                            {{ __('messages.learn_more_register') }}
                                        </a>

                                        @auth
                                            @if(in_array(Auth::user()->type, ['admin', 'faculty_staff']))
                                                <div class="mt-4 pt-2 border-t border-gray-200 dark:border-gray-700">
                                                    @php
                                                        $editRoute = Auth::user()->type === 'admin' ? route('admin.events.edit', $event->id) : route('user.events.edit', $event->id);
                                                        $deleteRoute = Auth::user()->type === 'admin' ? route('admin.events.destroy', $event->id) : route('user.events.destroy', $event->id);
                                                    @endphp
                                                    <a href="{{ $editRoute }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-3 font-medium">Edit</a>
                                                    <form action="{{ $deleteRoute }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium">Delete</button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">{{ __('messages.no_events_scheduled') }}</p>
                    @endif
                </div>
                @endif

                <!-- Next week's events -->
                @php
                    $showNextWeekSection = !request()->filled('date_range') || request('date_range') == 'this_month';
                @endphp
                @if($showNextWeekSection)
                <div class="mb-16">
                    <h2 class="text-2xl font-serif font-bold mb-8 pb-2 border-b border-gray-200 dark:border-gray-700">Next Week</h2>
                     @if($nextWeekEvents->isNotEmpty())
                        <div class="space-y-8">
                            @foreach($nextWeekEvents as $event)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden md:flex">
                                    <div class="md:w-1/3 bg-[#bb0017] text-white p-6 flex flex-col justify-center items-center text-center">
                                        <span class="text-5xl font-bold">{{ $event->event_date->format('d') }}</span>
                                        <span class="text-xl">{{ $event->event_date->format('M') }}</span>
                                    </div>
                                    <div class="md:w-2/3 p-6">
                                        <h3 class="text-xl font-serif font-bold mb-2 text-[#bb0017] dark:text-red-400">{{ $event->title }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                                            <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $event->event_date->format('g:i A') }}
                                        </p>
                                        @if($event->location)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                                            <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $event->location }}
                                        </p>
                                        @endif
                                        <p class="text-gray-700 dark:text-gray-300 mb-4 text-sm leading-relaxed h-16 overflow-y-auto">{{ Str::limit($event->description, 200) }}</p>
                                        <a href="{{ route('events.show', $event) }}" class="inline-block px-4 py-2 bg-[#263e62] text-white text-sm font-medium rounded-md hover:bg-[#1d324f] transition-colors">
                                            {{ __('messages.learn_more_register') }}
                                        </a>
                                        @auth
                                            @if(in_array(Auth::user()->type, ['admin', 'faculty_staff']))
                                                <div class="mt-4 pt-2 border-t border-gray-200 dark:border-gray-700">
                                                    @php
                                                        $editRoute = Auth::user()->type === 'admin' ? route('admin.events.edit', $event->id) : route('user.events.edit', $event->id);
                                                        $deleteRoute = Auth::user()->type === 'admin' ? route('admin.events.destroy', $event->id) : route('user.events.destroy', $event->id);
                                                    @endphp
                                                    <a href="{{ $editRoute }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-3 font-medium">Edit</a>
                                                    <form action="{{ $deleteRoute }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium">Delete</button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">{{ __('messages.no_events_scheduled') }}</p>
                    @endif
                </div>
                @endif

                <!-- Upcoming Events -->
                @php
                    $showComingSoonSection = !request()->filled('date_range') || in_array(request('date_range'), ['this_month', 'next_month']);
                @endphp
                @if($showComingSoonSection)
                <div class="mb-16">
                    <h2 class="text-2xl font-serif font-bold mb-8 pb-2 border-b border-gray-200 dark:border-gray-700">Coming Soon</h2>
                    @if($upcomingEvents->isNotEmpty())
                        <div class="space-y-8">
                             @foreach($upcomingEvents as $event)
                                <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg overflow-hidden md:flex">
                                    <div class="md:w-1/3 bg-[#263e62] text-white p-6 flex flex-col justify-center items-center text-center">
                                        <span class="text-5xl font-bold">{{ $event->event_date->format('d') }}</span>
                                        <span class="text-xl">{{ $event->event_date->format('M') }}</span>
                                    </div>
                                    <div class="md:w-2/3 p-6">
                                        <h3 class="text-xl font-serif font-bold mb-2 text-[#263e62] dark:text-blue-400">{{ $event->title }}</h3>
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-1">
                                            <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                            {{ $event->event_date->format('g:i A') }}
                                        </p>
                                        @if($event->location)
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-3">
                                            <svg class="inline-block h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path></svg>
                                            {{ $event->location }}
                                        </p>
                                        @endif
                                        <p class="text-gray-700 dark:text-gray-300 mb-4 text-sm leading-relaxed h-16 overflow-y-auto">{{ Str::limit($event->description, 200) }}</p>
                                        <a href="{{ route('events.show', $event) }}" class="inline-block px-4 py-2 bg-[#bb0017] text-white text-sm font-medium rounded-md hover:bg-[#a00014] transition-colors">
                                            {{ __('messages.learn_more_register') }}
                                        </a>
                                        @auth
                                            @if(in_array(Auth::user()->type, ['admin', 'faculty_staff']))
                                                <div class="mt-4 pt-2 border-t border-gray-200 dark:border-gray-700">
                                                    @php
                                                        $editRoute = Auth::user()->type === 'admin' ? route('admin.events.edit', $event->id) : route('user.events.edit', $event->id);
                                                        $deleteRoute = Auth::user()->type === 'admin' ? route('admin.events.destroy', $event->id) : route('user.events.destroy', $event->id);
                                                    @endphp
                                                    <a href="{{ $editRoute }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300 mr-3 font-medium">Edit</a>
                                                    <form action="{{ $deleteRoute }}" method="POST" class="inline-block" onsubmit="return confirm('Are you sure you want to delete this event?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-300 font-medium">Delete</button>
                                                    </form>
                                                </div>
                                            @endif
                                        @endauth
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <p class="text-gray-600 dark:text-gray-400">{{ __('messages.no_events_scheduled') }}</p>
                    @endif
                </div>
                @endif

                <!-- Calendar View Button -->
                <div class="text-center mt-12">
                    <a href="{{ route('events') }}" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-full shadow-sm text-white bg-[#263e62] hover:bg-[#1d324f] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-[#263e62]">
                        <svg xmlns="http://www.w3.org/2000/svg" class="-ml-1 mr-2 h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                        </svg>
                        View Full Calendar
                    </a>
                </div>
            </div>
        </div>

        <!-- Event Registration Banner -->
        <div class="bg-gray-100 dark:bg-gray-800 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-900 rounded-xl shadow-xl overflow-hidden">
                    <div class="md:flex">
                        <div class="md:flex-shrink-0 bg-[#263e62] dark:bg-blue-800 md:w-48 flex items-center justify-center p-6">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-20 w-20 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                            </svg>
                        </div>
                        <div class="p-8 md:p-12">
                            <h3 class="text-2xl font-serif font-bold mb-2">Stay Updated</h3>
                            <p class="text-gray-600 dark:text-gray-400 mb-6">Receive notifications about upcoming events and activities at the Russian University Library.</p>
                            <div class="flex flex-col sm:flex-row gap-3">
                                <input type="email" placeholder="Enter your email" class="flex-1 px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg text-sm focus:outline-none focus:ring-2 focus:ring-[#263e62] dark:bg-gray-800 dark:text-gray-300">
                                <button class="px-6 py-3 bg-[#bb0017] text-white rounded-lg text-sm font-medium hover:bg-[#a00014] transition duration-200">Subscribe</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('components.footer')

        <script>
            function toggleAddEventModal() {
                const modal = document.getElementById('addEventModal');
                if (modal.classList.contains('hidden')) {
                    modal.classList.remove('hidden');
                } else {
                    modal.classList.add('hidden');
                }
            }

            function setDateRange(range) {
                document.getElementById('date_range').value = range;
                document.getElementById('filterForm').submit();
            }
        </script>
    </body>
</html>
