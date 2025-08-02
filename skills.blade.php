<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('messages.skills_title') }}</title>

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

        /* Accordion animation */
        .accordion-content {
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.3s ease-out;
        }
        .accordion-content.open {
            max-height: 500px;
        }
    </style>
</head>
<body class="bg-gray-100 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">
    @include('components.navbar')

    <!-- Page Header -->
    <div class="bg-[#263e62] dark:bg-blue-900 py-12">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-white text-center">
                <h1 class="text-4xl md:text-5xl font-serif font-bold mb-4">{{ __('messages.skill_exchange_platform') }}</h1>
                <p class="text-xl text-blue-100 max-w-3xl mx-auto">{{ __('messages.skill_exchange_description') }}</p>
            </div>
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

            <!-- Search & Filter Section -->
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 mb-8">
                <form action="{{ route('skills.index') }}" method="GET" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-grow">
                        <label for="search" class="sr-only">{{ __('messages.search_skills') }}</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400 dark:text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" id="search" name="search" value="{{ request('search') }}" class="pl-10 focus:ring-[#263e62] focus:border-[#263e62] block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300" placeholder="{{ __('messages.search_skills_placeholder') }}">
                        </div>
                    </div>
                    <div>
                        <label for="category" class="sr-only">{{ __('messages.category') }}</label>
                        <select id="category" name="category" class="focus:ring-[#263e62] focus:border-[#263e62] block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-gray-300">
                            <option value="">{{ __('messages.all_categories') }}</option>
                            @foreach($categories as $category)
                                <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <button type="submit" class="w-full sm:w-auto px-4 py-2 bg-[#263e62] hover:bg-[#1d324f] text-white rounded-md transition-colors">
                            {{ __('messages.filter') }}
                        </button>
                    </div>
                </form>
            </div>

            <!-- Skills Grid -->
            <div class="mb-12">
                <h2 class="text-2xl font-serif font-bold mb-6">{{ __('messages.available_skills') }}</h2>

                @if($skills->isEmpty())
                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-8 text-center">
                        <p class="text-gray-600 dark:text-gray-400">{{ __('messages.no_skills_available') }}</p>
                        @auth
                        <div class="mt-4">
                            <p class="text-gray-600 dark:text-gray-400">{{ __('messages.be_first_to_add_skill') }}</p>
                            <button onclick="toggleAddSkillForm()" class="mt-2 px-4 py-2 bg-[#bb0017] hover:bg-[#a00014] text-white rounded-md transition-colors">
                                {{ __('messages.add_your_skill') }}
                            </button>
                        </div>
                        @endauth
                    </div>
                @else
                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($skills as $skill)
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md overflow-hidden border border-gray-200 dark:border-gray-700 hover:shadow-lg transition-shadow">
                                <div class="p-6">
                                    <div class="flex justify-between items-start mb-2">
                                        <h3 class="font-serif font-bold text-xl text-[#263e62] dark:text-blue-400">{{ $skill->name }}</h3>
                                        @if($skill->rating)
                                            <div class="flex items-center bg-yellow-100 dark:bg-yellow-900 text-yellow-800 dark:text-yellow-200 text-xs px-2 py-1 rounded-md">
                                                <svg class="h-4 w-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                </svg>
                                                {{ number_format($skill->rating, 1) }}
                                            </div>
                                        @endif
                                    </div>

                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        {{ __('messages.category') }}: <span class="font-medium">{{ $skill->category }}</span>
                                    </p>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-3">
                                        {{ __('messages.mentor') }}: <span class="font-medium">{{ $skill->mentor->name }}</span>
                                    </p>

                                    <p class="text-gray-700 dark:text-gray-300 mb-4 h-20 overflow-y-auto">
                                        {{ $skill->description }}
                                    </p>

                                    @auth
                                        @if($skill->mentor_id == Auth::id())
                                            <div class="mt-4 text-center py-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                                <p class="text-sm text-gray-600 dark:text-gray-400">{{ __('messages.this_is_your_skill') }}</p>
                                            </div>
                                        @elseif($userSkills->isNotEmpty())
                                            <button
                                                onclick="toggleExchangeForm('exchangeForm{{ $skill->id }}')"
                                                class="w-full mt-4 py-2 px-4 bg-[#bb0017] hover:bg-[#a00014] text-white rounded-md transition-colors text-sm font-medium"
                                            >
                                                {{ __('messages.request_exchange') }}
                                            </button>

                                            <!-- Exchange Request Form -->
                                            <div id="exchangeForm{{ $skill->id }}" class="accordion-content mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                                <form action="{{ route('skills.exchange', $skill->id) }}" method="POST">
                                                    @csrf
                                                    <div class="mb-3">
                                                        <label for="offered_skill_{{ $skill->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                            {{ __('messages.what_skill_to_offer') }}:
                                                        </label>
                                                        <select
                                                            id="offered_skill_{{ $skill->id }}"
                                                            name="offered_skill_id"
                                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300"
                                                            required
                                                        >
                                                            @foreach($userSkills as $userSkill)
                                                                <option value="{{ $userSkill->id }}">{{ $userSkill->name }}</option>
                                                            @endforeach
                                                        </select>
                                                    </div>
                                                    <div class="mb-3">
                                                        <label for="message_{{ $skill->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                            {{ __('messages.optional_message') }}:
                                                        </label>
                                                        <textarea
                                                            id="message_{{ $skill->id }}"
                                                            name="message"
                                                            rows="2"
                                                            class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300"
                                                            placeholder="{{ __('messages.message_placeholder') }}"
                                                        ></textarea>
                                                    </div>
                                                    <div class="flex justify-end">
                                                        <button type="button" onclick="toggleExchangeForm('exchangeForm{{ $skill->id }}')" class="mr-2 py-2 px-3 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 rounded-md transition-colors text-xs">
                                                            {{ __('messages.cancel') }}
                                                        </button>
                                                        <button type="submit" class="py-2 px-4 bg-[#263e62] hover:bg-[#1d324f] text-white rounded-md transition-colors text-xs font-medium">
                                                            {{ __('messages.send_request') }}
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        @else
                                            <div class="mt-4 text-center py-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ __('messages.add_skill_first') }}
                                                    <button type="button" onclick="toggleAddSkillForm()" class="text-[#bb0017] hover:underline">
                                                        {{ __('messages.add_now') }}
                                                    </button>
                                                </p>
                                            </div>
                                        @endif
                                    @else
                                        <div class="mt-4 text-center py-2 bg-gray-100 dark:bg-gray-700 rounded-md">
                                            <p class="text-sm text-gray-600 dark:text-gray-400">
                                                <a href="{{ route('login') }}" class="text-[#bb0017] hover:underline">{{ __('messages.login_to_exchange') }}</a>
                                            </p>
                                        </div>
                                    @endauth
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <!-- Pagination -->
                    <div class="mt-8">
                        {{ $skills->links('vendor.pagination.custom') }}
                    </div>
                @endif
            </div>

            @auth
                <!-- Add New Skill Section -->
                <div id="addSkillSection" class="hidden mb-12 bg-white dark:bg-gray-800 rounded-lg shadow-md p-6">
                    <h2 class="text-2xl font-serif font-bold mb-4">{{ __('messages.add_new_skill') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('messages.share_your_expertise') }}</p>

                    <form action="{{ route('skills.request') }}" method="POST">
                        @csrf
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label for="name" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('messages.skill_name') }} *
                                </label>
                                <input
                                    type="text"
                                    id="name"
                                    name="name"
                                    required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300"
                                >
                            </div>
                            <div>
                                <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('messages.skill_category') }} *
                                </label>
                                <input
                                    type="text"
                                    id="category"
                                    name="category"
                                    required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300"
                                    list="categoryOptions"
                                >
                                <datalist id="categoryOptions">
                                    @foreach($categories as $category)
                                        <option value="{{ $category }}">
                                    @endforeach
                                </datalist>
                            </div>
                            <div class="md:col-span-2">
                                <label for="description" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                    {{ __('messages.skill_description') }} *
                                </label>
                                <textarea
                                    id="description"
                                    name="description"
                                    rows="4"
                                    required
                                    class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300"
                                ></textarea>
                            </div>
                        </div>
                        <div class="mt-6 flex justify-end">
                            <button type="button" onclick="toggleAddSkillForm()" class="mr-3 py-2 px-4 border border-gray-300 dark:border-gray-600 rounded-md shadow-sm text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-700 hover:bg-gray-50 dark:hover:bg-gray-600">
                                {{ __('messages.cancel') }}
                            </button>
                            <button type="submit" class="py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-[#263e62] hover:bg-[#1d324f]">
                                {{ __('messages.add_skill') }}
                            </button>
                        </div>
                    </form>
                </div>

                <!-- Pending Skills Section -->
                @if($pendingSkills->isNotEmpty())
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-bold mb-4">{{ __('messages.pending_approval') }}</h2>

                    <div class="bg-yellow-50 dark:bg-yellow-900 border-l-4 border-yellow-400 dark:border-yellow-600 p-4 rounded-md">
                        <div class="flex">
                            <div class="flex-shrink-0">
                                <svg class="h-5 w-5 text-yellow-400 dark:text-yellow-300" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                </svg>
                            </div>
                            <div class="ml-3">
                                <p class="text-sm text-yellow-700 dark:text-yellow-200">
                                    {{ __('messages.pending_skills_notice') }}
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                        @foreach($pendingSkills as $pendingSkill)
                            <div class="bg-white dark:bg-gray-800 border border-gray-200 dark:border-gray-700 border-l-4 border-l-yellow-400 dark:border-l-yellow-600 rounded-lg shadow-md overflow-hidden">
                                <div class="p-6">
                                    <div class="flex justify-between items-start">
                                        <h3 class="font-serif font-bold text-xl text-gray-800 dark:text-white mb-2">{{ $pendingSkill->name }}</h3>
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 dark:bg-yellow-800 text-yellow-800 dark:text-yellow-200">
                                            {{ __('messages.pending') }}
                                        </span>
                                    </div>
                                    <p class="text-sm text-gray-600 dark:text-gray-400 mb-1">
                                        {{ __('messages.category') }}: <span class="font-medium">{{ $pendingSkill->category }}</span>
                                    </p>
                                    <p class="text-gray-700 dark:text-gray-300 mt-2 h-16 overflow-y-auto">{{ $pendingSkill->description }}</p>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-4">
                                        {{ __('messages.submitted_on') }}: {{ $pendingSkill->created_at->format('M d, Y') }}
                                    </p>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- My Exchanges Section -->
                <div class="mb-12">
                    <h2 class="text-2xl font-serif font-bold mb-2">{{ __('messages.my_exchanges') }}</h2>
                    <p class="text-gray-600 dark:text-gray-400 mb-6">{{ __('messages.manage_your_skill_exchanges') }}</p>

                    <!-- Sent Exchanges -->
                    <div class="mb-8">
                        <h3 class="text-xl font-medium mb-4 pl-4 border-l-4 border-[#bb0017]">{{ __('messages.sent_requests') }}</h3>

                        @if($sentExchanges->isEmpty())
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
                                <p class="text-gray-600 dark:text-gray-400">{{ __('messages.no_sent_exchanges') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($sentExchanges as $exchange)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4
                                        @if($exchange->status == 'pending') border-yellow-500
                                        @elseif($exchange->status == 'accepted') border-green-500
                                        @elseif($exchange->status == 'completed') border-blue-500
                                        @else border-red-500 @endif">

                                        <div class="flex justify-between items-start flex-wrap">
                                            <div class="mb-2">
                                                <p class="font-medium">
                                                    {{ __('messages.requesting') }} <span class="text-[#263e62] dark:text-blue-400">{{ $exchange->skill->name }}</span>
                                                    {{ __('messages.from') }} {{ $exchange->mentor->name }} [ {{$exchange->mentor->email}} ]
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ __('messages.offering') }} <span class="font-medium">{{ $exchange->exchangeWith->name }}</span>
                                                </p>
                                            </div>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                @if($exchange->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($exchange->status == 'accepted') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($exchange->status == 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ ucfirst($exchange->status) }}
                                            </span>
                                        </div>

                                        @if($exchange->description)
                                            <p class="text-gray-600 dark:text-gray-400 text-sm italic my-2">"{{ $exchange->description }}"</p>
                                        @endif

                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            {{ __('messages.requested_on') }}: {{ $exchange->created_at->format('M d, Y') }}
                                        </p>

                                        <div class="mt-4 flex flex-wrap gap-2 items-center">
                                            @if($exchange->status == 'pending')
                                                <form action="{{ route('exchange.cancel', $exchange->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition-colors">
                                                        {{ __('messages.cancel_request') }}
                                                    </button>
                                                </form>
                                            @elseif($exchange->status == 'accepted')
                                                <form action="{{ route('exchange.complete', $exchange->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-md transition-colors">
                                                        {{ __('messages.mark_complete') }}
                                                    </button>
                                                </form>
                                            @elseif($exchange->status == 'completed' && !$exchange->rating)
                                                <button type="button" onclick="toggleRatingForm('ratingForm{{ $exchange->id }}')" class="px-3 py-1 bg-[#263e62] hover:bg-[#1d324f] text-white text-xs rounded-md transition-colors">
                                                    {{ __('messages.rate_experience') }}
                                                </button>

                                                <!-- Rating Form -->
                                                <div id="ratingForm{{ $exchange->id }}" class="accordion-content w-full mt-3 pt-3 border-t border-gray-200 dark:border-gray-700">
                                                    <form action="{{ route('exchange.rate', $exchange->id) }}" method="POST">
                                                        @csrf
                                                        <div class="mb-3">
                                                            <label for="rating{{ $exchange->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                {{ __('messages.rating') }}:
                                                            </label>
                                                            <select
                                                                id="rating{{ $exchange->id }}"
                                                                name="rating"
                                                                required
                                                                class="block w-full md:w-1/3 rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300"
                                                            >
                                                                <option value="5">5 - {{ __('messages.excellent') }}</option>
                                                                <option value="4">4 - {{ __('messages.very_good') }}</option>
                                                                <option value="3">3 - {{ __('messages.good') }}</option>
                                                                <option value="2">2 - {{ __('messages.fair') }}</option>
                                                                <option value="1">1 - {{ __('messages.poor') }}</option>
                                                            </select>
                                                        </div>
                                                        <div class="mb-3">
                                                            <label for="feedback{{ $exchange->id }}" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">
                                                                {{ __('messages.feedback') }} ({{ __('messages.optional') }}):
                                                            </label>
                                                            <textarea
                                                                id="feedback{{ $exchange->id }}"
                                                                name="feedback"
                                                                rows="2"
                                                                class="block w-full rounded-md border-gray-300 dark:border-gray-600 focus:ring-[#263e62] focus:border-[#263e62] dark:bg-gray-700 dark:text-gray-300"
                                                            ></textarea>
                                                        </div>
                                                        <div class="flex justify-end">
                                                            <button type="submit" class="px-3 py-1 bg-[#263e62] hover:bg-[#1d324f] text-white text-xs rounded transition-colors">
                                                                {{ __('messages.submit_rating') }}
                                                            </button>
                                                        </div>
                                                    </form>
                                                </div>
                                            @elseif($exchange->status == 'completed' && $exchange->rating)
                                                <div class="flex items-center text-xs text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-md">
                                                    <svg class="h-4 w-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                    <span>{{ __('messages.rated') }}: {{ $exchange->rating }}/5</span>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>

                    <!-- Received Exchanges -->
                    <div>
                        <h3 class="text-xl font-medium mb-4 pl-4 border-l-4 border-[#263e62]">{{ __('messages.received_requests') }}</h3>

                        @if($receivedExchanges->isEmpty())
                            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 text-center">
                                <p class="text-gray-600 dark:text-gray-400">{{ __('messages.no_received_exchanges') }}</p>
                            </div>
                        @else
                            <div class="space-y-4">
                                @foreach($receivedExchanges as $exchange)
                                    <div class="bg-white dark:bg-gray-800 rounded-lg shadow-md p-6 border-l-4
                                        @if($exchange->status == 'pending') border-yellow-500
                                        @elseif($exchange->status == 'accepted') border-green-500
                                        @elseif($exchange->status == 'completed') border-blue-500
                                        @else border-red-500 @endif">

                                        <div class="flex justify-between items-start flex-wrap">
                                            <div class="mb-2">
                                                <p class="font-medium">
                                                    {{ $exchange->student->name }} {{ __('messages.wants_to_learn') }}
                                                    <span class="text-[#263e62] dark:text-blue-400">{{ $exchange->skill->name }}</span>
                                                </p>
                                                <p class="text-sm text-gray-600 dark:text-gray-400">
                                                    {{ __('messages.they_offer') }} <span class="font-medium">{{ $exchange->exchangeWith->name }}</span>
                                                </p>
                                            </div>
                                            <span class="px-3 py-1 text-xs font-medium rounded-full
                                                @if($exchange->status == 'pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                                                @elseif($exchange->status == 'accepted') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                                                @elseif($exchange->status == 'completed') bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200
                                                @else bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200 @endif">
                                                {{ ucfirst($exchange->status) }}
                                            </span>
                                        </div>

                                        @if($exchange->description)
                                            <p class="text-gray-600 dark:text-gray-400 text-sm italic my-2">"{{ $exchange->description }}"</p>
                                        @endif

                                        <p class="text-xs text-gray-500 dark:text-gray-500 mt-2">
                                            {{ __('messages.received_on') }}: {{ $exchange->created_at->format('M d, Y') }}
                                        </p>

                                        <div class="mt-4 flex flex-wrap gap-2 items-center">
                                            @if($exchange->status == 'pending')
                                                <form action="{{ route('exchange.accept', $exchange->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-green-500 hover:bg-green-600 text-white text-xs rounded-md transition-colors">
                                                        {{ __('messages.accept') }}
                                                    </button>
                                                </form>
                                                <form action="{{ route('exchange.reject', $exchange->id) }}" method="POST" class="inline">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-red-500 hover:bg-red-600 text-white text-xs rounded-md transition-colors">
                                                        {{ __('messages.reject') }}
                                                    </button>
                                                </form>
                                            @elseif($exchange->status == 'accepted')
                                                <form action="{{ route('exchange.complete', $exchange->id) }}" method="POST">
                                                    @csrf
                                                    <button type="submit" class="px-3 py-1 bg-blue-500 hover:bg-blue-600 text-white text-xs rounded-md transition-colors">
                                                        {{ __('messages.mark_complete') }}
                                                    </button>
                                                </form>
                                            @elseif($exchange->status == 'completed' && $exchange->rating !== null)
                                                <div class="flex items-center text-xs text-gray-700 dark:text-gray-300 bg-gray-100 dark:bg-gray-700 px-3 py-1 rounded-md">
                                                    <svg class="h-4 w-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                    <span>{{ __('messages.student_rated') }}: {{ $exchange->rating }}/5</span>
                                                </div>
                                            @elseif($exchange->status == 'completed' && $exchange->rating === null)
                                                <span class="px-3 py-1 text-xs font-medium rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-200">
                                                    {{ __('messages.pending_student_rating') }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>
            @endauth

        </div>
    </div>

    @include('components.footer')

    <script>
        // Toggle the exchange request form
        function toggleExchangeForm(formId) {
            const form = document.getElementById(formId);
            if (form.classList.contains('open')) {
                form.classList.remove('open');
            } else {
                // Close any other open forms first
                document.querySelectorAll('.accordion-content.open').forEach(item => {
                    if (item.id !== formId) {
                        item.classList.remove('open');
                    }
                });
                form.classList.add('open');
            }
        }

        // Toggle the add skill form
        function toggleAddSkillForm() {
            const form = document.getElementById('addSkillSection');
            form.classList.toggle('hidden');
            // Scroll to form if it's being opened
            if (!form.classList.contains('hidden')) {
                form.scrollIntoView({ behavior: 'smooth' });
            }
        }

        // Toggle the rating form
        function toggleRatingForm(formId) {
            const form = document.getElementById(formId);
            if (form.classList.contains('open')) {
                form.classList.remove('open');
            } else {
                form.classList.add('open');
            }
        }
    </script>
</body>
</html>
