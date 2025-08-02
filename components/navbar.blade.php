<nav class="bg-white dark:bg-gray-900 border-b border-gray-100 dark:border-gray-800 sticky top-0 z-50 shadow-sm">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between h-20">
            <!-- Logo and brand -->
            <div class="flex items-center">
                <a href="{{ url('/') }}" class="flex-shrink-0 flex items-center">
                   <x-application-logo class="h-8 w-auto" />
                </a>
            </div>

            <!-- Main Navigation Links -->
            <div class="hidden lg:flex lg:items-center lg:space-x-6">
                <a href="{{ route('books') }}" class="nav-bounce text-gray-700 dark:text-gray-300 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out rounded-md {{ (request()->routeIs('books*') && request()->query('availability') !== 'available') ? 'active-nav-link' : '' }}">
                    {{ __('messages.books') }}
                </a>
                <a href="{{ route('books') }}?availability=available" class="nav-bounce text-gray-700 dark:text-gray-300 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out rounded-md {{ (request()->routeIs('books*') && request()->query('availability') === 'available') ? 'active-nav-link' : '' }}">
                    {{ __('messages.available_books') }}
                </a>
                <a href="{{ route('documents.index') }}" class="nav-bounce text-gray-700 dark:text-gray-300 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out rounded-md {{ request()->routeIs('documents.index') ? 'active-nav-link' : '' }}">
                    {{ __('messages.documents_title') }}
                </a>
               <a href="{{ route('events') }}" class="nav-bounce text-gray-700 dark:text-gray-300 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out rounded-md {{ request()->routeIs('events*') ? 'active-nav-link' : '' }}">
                    {{ __('messages.events') }}
                </a>
                @if (auth()->check())
                    <a href="{{ route('reservations.my') }}" class="nav-bounce text-gray-700 dark:text-gray-300 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out rounded-md {{ request()->routeIs('reservations*') ? 'active-nav-link' : '' }}">
                        {{ __('messages.my_reservations') }}
                    </a>
                    <a href="{{ route('skills.index') }}" class="nav-bounce text-gray-700 dark:text-gray-300 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out rounded-md {{ request()->routeIs('skills*') ? 'active-nav-link' : '' }}">
                        {{ __('messages.skills') }}
                    </a>
                @endif
            </div>

            <!-- Auth Links & Language Switcher -->
            <div class="hidden lg:flex lg:items-center ml-4">
                @auth
                    <!-- User profile dropdown -->
                    <div class="relative ml-3">
                        <div>
                            <button type="button" class=" flex items-center text-gray-700 dark:text-gray-300 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out rounded-md" id="user-menu-button" aria-expanded="false" aria-haspopup="true">
                                {{ auth()->user()->name }}
                                <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 1 01-1.414 0l-4-4a1 1 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                        </div>
                        <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="user-menu-button" id="user-menu">
                            <button type="button" id="open-user-details-modal" class=" block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200" role="menuitem">{{ __('messages.my_account') }}</button>
                            <form method="POST" action="{{ route('client.logout') }}">
                                @csrf
                                <button type="submit" class=" block w-full text-left px-4 py-2 text-sm text-gray-700 dark:text-gray-200" role="menuitem">{{ __('messages.logout') }}</button>
                            </form>
                        </div>
                    </div>
                @else
                    <button type="button" data-modal-type="login" class=" open-auth-modal text-gray-700 dark:text-gray-300 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out mr-2 rounded-md">{{ __('messages.login') }}</button>
                    @if (Route::has('register'))
                        <button type="button" data-modal-type="register" class=" open-auth-modal inline-flex items-center px-5 py-2 bg-[#bb0017] text-white rounded-full text-sm font-medium hover:bg-[#a00014] hover:text-white transition duration-150 ease-in-out">{{ __('messages.register') }}</button>
                    @endif
                @endauth

                <!-- Language Switcher Dropdown -->
                <div class="relative ml-3">
                    <div>
                        <button type="button" class=" text-gray-700 dark:text-gray-300 px-3 py-2 text-sm font-medium transition duration-150 ease-in-out flex items-center rounded-md" id="language-menu-button" aria-expanded="false" aria-haspopup="true">
                            <svg fill="#000000" width="64px" height="64px" viewBox="0 0 32 32" class="h-5 w-5 mr-1" xmlns="http://www.w3.org/2000/svg">
                                <g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <title>globe1</title>
                                    <path d="M15.5 2c-8.008 0-14.5 6.492-14.5 14.5s6.492 14.5 14.5 14.5 14.5-6.492 14.5-14.5-6.492-14.5-14.5-14.5zM10.752 3.854c-0.714 1.289-1.559 3.295-2.113 6.131h-4.983c1.551-2.799 4.062-4.993 7.096-6.131zM3.154 10.987h5.316c-0.234 1.468-0.391 3.128-0.415 5.012h-6.060c0.067-1.781 0.468-3.474 1.159-5.012zM1.988 17.001h6.072c0.023 1.893 0.188 3.541 0.422 5.012h-5.29c-0.694-1.543-1.138-3.224-1.204-5.012zM3.67 23.015h4.977c0.559 2.864 1.416 4.867 2.134 6.142-3.046-1.134-5.557-3.336-7.111-6.142zM15.062 30.009c-1.052-0.033-2.067-0.199-3.045-0.46-0.755-1.236-1.736-3.363-2.356-6.534h5.401v6.994zM15.062 22.013h-5.578c-0.234-1.469-0.396-3.119-0.421-5.012h5.998v5.012zM15.062 15.999h-6.004c0.025-1.886 0.183-3.543 0.417-5.012h5.587v5.012zM15.062 9.985h-5.422c0.615-3.148 1.591-5.266 2.344-6.525 0.987-0.266 2.015-0.435 3.078-0.47v6.995zM29.003 15.999h-5.933c-0.025-1.884-0.182-3.544-0.416-5.012h5.172c0.693 1.541 1.108 3.23 1.177 5.012zM27.322 9.985h-4.837c-0.549-2.806-1.382-4.8-2.091-6.09 2.967 1.154 5.402 3.335 6.928 6.09zM16.063 2.989c1.067 0.047 2.102 0.216 3.092 0.493 0.751 1.263 1.72 3.372 2.331 6.503h-5.423v-6.996zM16.063 10.987h5.587c0.234 1.469 0.392 3.126 0.417 5.012h-6.004v-5.012zM16.063 17.001h5.998c-0.023 1.893-0.187 3.543-0.421 5.012h-5.577v-5.012zM16.063 29.991v-6.977h5.402c-0.617 3.152-1.591 5.271-2.343 6.512-0.978 0.272-2.005 0.418-3.059 0.465zM20.367 29.114c0.714-1.276 1.56-3.266 2.112-6.1h4.835c-1.522 2.766-3.967 4.95-6.947 6.1zM27.795 22.013h-5.152c0.234-1.471 0.398-3.119 0.423-5.012h5.927c-0.067 1.787-0.508 3.468-1.198 5.012z"></path>
                                </g>
                            </svg>
                            {{ strtoupper(app()->getLocale()) }}
                            <svg class="ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 111.414 1.414l-4 4a1 1 01-1.414 0l-4-4a 1 1 010-1.414z" clip-rule="evenodd" />
                            </svg>
                        </button>
                    </div>
                    <div class="origin-top-right absolute right-0 mt-2 w-48 rounded-md shadow-lg py-1 bg-white dark:bg-gray-800 ring-1 ring-black ring-opacity-5 focus:outline-none hidden" role="menu" aria-orientation="vertical" aria-labelledby="language-menu-button" id="language-menu">
                        <a href="{{ route('language.switcher', 'en') }}" class=" block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 transition-colors duration-200 font-medium" role="menuitem">EN</a>
                        <a href="{{ route('language.switcher', 'ar') }}" class=" block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 transition-colors duration-200 font-medium" role="menuitem">AR</a>
                        <a href="{{ route('language.switcher', 'ru') }}" class=" block px-4 py-2 text-sm text-gray-700 dark:text-gray-200 transition-colors duration-200 font-medium" role="menuitem">RU</a>
                    </div>
                </div>
            </div>

            <!-- Mobile menu button -->
            <div class="flex items-center lg:hidden">
                <button type="button" class="mobile-menu-button inline-flex items-center justify-center p-2 rounded-md text-gray-500 hover:text-[#263e62] hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-[#263e62]" aria-expanded="false">
                    <span class="sr-only">{{ __('messages.open_main_menu') }}</span>
                    <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Mobile menu -->
    <div class="lg:hidden hidden mobile-menu">
        <div class="pt-2 pb-3 space-y-1 px-4">
            <!-- Search on mobile -->
            <div class="relative mb-4 mt-2">
                <input type="text" placeholder="{{ __('messages.search_resources_placeholder') }}" class="w-full pl-10 pr-4 py-2 border border-gray-300 dark:border-gray-700 rounded-full text-sm focus:outline-none focus:ring-2 focus:ring-[#263e62] dark:bg-gray-800 dark:text-gray-300">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 text-gray-400 dark:text-gray-500 absolute left-3 top-2.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                </svg>
            </div>

            <!-- Library dropdown for mobile -->
            <div class="space-y-1 pl-3">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('messages.library') }}</p>
                <a href="{{ route('books') }}" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('books*') ? 'text-[#263e62] dark:text-blue-400 font-semibold' : '' }}">{{ __('messages.book_catalogs') }}</a>
                <a href="{{ route('books') }}?availability=available" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md {{ request()->fullUrl() == route('books').'?availability=available' ? 'text-[#263e62] dark:text-blue-400 font-semibold' : '' }}">{{ __('messages.check_availability') }}</a>
                <a href="{{ auth()->check() ? route('reservations.my') : route('books') . '#reservations' }}" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('reservations*') ? 'text-[#263e62] dark:text-blue-400 font-semibold' : '' }}">{{ __('messages.book_reservations') }}</a>
            </div>

            <!-- Digital Resources dropdown for mobile -->
            <div class="space-y-1 pl-3 mt-3">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('messages.digital_resources') }}</p>
                <a href="#research-papers" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md">{{ __('messages.research_papers') }}</a>
                <a href="#audio-books" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md">{{ __('messages.audio_books') }}</a>
                <a href="#e-journals" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md">{{ __('messages.e_journals') }}</a>
            </div>

            <!-- Other links for mobile -->
            <a href="{{ route('events') }}" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 mt-3 rounded-md {{ request()->routeIs('events*') ? 'text-[#263e62] dark:text-blue-400 font-semibold' : '' }}">{{ __('messages.campus_events') }}</a>
            @auth
            <a href="{{ route('skills.index') }}" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md {{ request()->routeIs('skills*') ? 'text-[#263e62] dark:text-blue-400 font-semibold' : '' }}">{{ __('messages.skills') }}</a>
            @endauth
            <a href="#study-spaces" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md">{{ __('messages.study_spaces') }}</a>
            <a href="#assistance" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md">{{ __('messages.research_assistance') }}</a>
        </div>

        <!-- Mobile auth links -->
        <div class="pt-4 pb-3 border-t border-gray-200 dark:border-gray-700">
            @auth
                <div class="px-4 space-y-3">
                    <div class="flex items-center px-3 py-2">
                        <div class="text-base font-medium text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</div>
                    </div>
                    <button type="button" id="open-user-details-modal-mobile" class=" block w-full px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md text-left">{{ __('messages.my_account') }}</button>
                    <form method="POST" action="{{ route('client.logout') }}" class="w-full">
                        @csrf
                        <button type="submit" class=" block w-full px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md text-left">{{ __('messages.logout') }}</button>
                    </form>
                </div>
            @else
                <div class="px-4 space-y-3">
                    <button type="button" data-modal-type="login" class=" open-auth-modal block w-full px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md text-left">{{ __('messages.login') }}</button>
                    @if (Route::has('register'))
                        <button type="button" data-modal-type="register" class=" open-auth-modal block w-full px-3 py-2 text-base font-medium text-white bg-[#bb0017] hover:bg-[#a00014] hover:text-white rounded-md text-left transition duration-150 ease-in-out">{{ __('messages.register') }}</button>
                    @endif
                </div>
            @endauth
            <!-- Mobile Language Switcher -->
            <div class="px-4 mt-3">
                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ __('messages.language') }}</p>
                <a href="{{ route('language.switcher', 'en') }}" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md transition-colors duration-200">EN</a>
                <a href="{{ route('language.switcher', 'ar') }}" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md transition-colors duration-200">AR</a>
                <a href="{{ route('language.switcher', 'ru') }}" class=" block px-3 py-2 text-base font-medium text-gray-700 dark:text-gray-300 rounded-md transition-colors duration-200">RU</a>
            </div>
        </div>
    </div>
</nav>

@include('components.auth-modal')
@auth
    @include('components.user-details-modal')
@endauth

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const mobileMenuButton = document.querySelector('.mobile-menu-button');
        const mobileMenu = document.querySelector('.mobile-menu');
        const languageMenuButton = document.getElementById('language-menu-button');
        const languageMenu = document.getElementById('language-menu');
        const userMenuButton = document.getElementById('user-menu-button');
        const userMenu = document.getElementById('user-menu');

        if (mobileMenuButton && mobileMenu) {
            mobileMenuButton.addEventListener('click', () => {
                mobileMenu.classList.toggle('hidden');
            });
        }

        // User menu dropdown toggle
        if (userMenuButton && userMenu) {
            userMenuButton.addEventListener('click', () => {
                const isExpanded = userMenuButton.getAttribute('aria-expanded') === 'true' || false;
                userMenuButton.setAttribute('aria-expanded', !isExpanded);
                userMenu.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!userMenuButton.contains(event.target) && !userMenu.contains(event.target)) {
                    userMenu.classList.add('hidden');
                    userMenuButton.setAttribute('aria-expanded', 'false');
                }
            });
        }

        const userDetailsModal = document.getElementById('user-details-modal');
        const openUserDetailsModalButton = document.getElementById('open-user-details-modal');
        const openUserDetailsModalButtonMobile = document.getElementById('open-user-details-modal-mobile');
        const closeUserDetailsModalButton = document.getElementById('user-details-modal-close');

        function showUserDetailsModal() {
            if (userDetailsModal) userDetailsModal.classList.remove('hidden');
        }
        function hideUserDetailsModal() {
            if (userDetailsModal) userDetailsModal.classList.add('hidden');
        }

        if (openUserDetailsModalButton) {
            openUserDetailsModalButton.addEventListener('click', () => {
                showUserDetailsModal();
                if (userMenu) userMenu.classList.add('hidden');
            });
        }

        if (openUserDetailsModalButtonMobile) {
            openUserDetailsModalButtonMobile.addEventListener('click', () => {
                showUserDetailsModal();
                if (mobileMenu) mobileMenu.classList.add('hidden');
            });
        }

        if (closeUserDetailsModalButton) {
            closeUserDetailsModalButton.addEventListener('click', hideUserDetailsModal);
        }

        if (userDetailsModal) {
            userDetailsModal.addEventListener('click', e => {
                if (e.target === userDetailsModal) {
                    hideUserDetailsModal();
                }
            });
        }

        if (languageMenuButton && languageMenu) {
            languageMenuButton.addEventListener('click', () => {
                const isExpanded = languageMenuButton.getAttribute('aria-expanded') === 'true' || false;
                languageMenuButton.setAttribute('aria-expanded', !isExpanded);
                languageMenu.classList.toggle('hidden');
            });

            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!languageMenuButton.contains(event.target) && !languageMenu.contains(event.target)) {
                    languageMenu.classList.add('hidden');
                    languageMenuButton.setAttribute('aria-expanded', 'false');
                }
            });
        }
    });
</script>

<style>
    .nav-bounce {
        transition: color 0.15s, font-size 0.15s, transform 0.2s;
    }
    .nav-bounce:hover {
        font-size: 1.25rem; /* text-lg */
        color: #1a2650 !important;
        transform: scale(1.13) translateY(-2px);
        animation: bounce-nav 0.3s;
    }
    @media (prefers-color-scheme: dark) {
        .nav-bounce:hover {
            color: #263e62 !important;
        }
    }
    @keyframes bounce-nav {
        0%   { transform: scale(1);}
        100% { transform: scale(1.08);}
    }
    .active-nav-link {
        position: relative;
        color: #263e62 !important;
        font-weight: 600;
    }
    .active-nav-link::after {
        content: '';
        display: block;
        margin: 0 auto;
        margin-top: 2px;
        width: 70%;
        border-bottom: 2px solid #b6c3d6;
        border-radius: 1px;
    }
    @media (prefers-color-scheme: dark) {
        .active-nav-link {
            color: #467cbd !important;
        }
        .active-nav-link::after {
            border-bottom-color: #3b82f6;
        }
    }
</style>
