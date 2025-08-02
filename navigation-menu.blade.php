<nav x-data="{ open: false, darkMode: false }" class="bg-white dark:bg-gray-800 border-b border-gray-100 dark:border-gray-700">
    <!-- Primary Navigation Menu -->
    <div class="max-w-8xl mx-auto px-6 sm:px-8 lg:px-10">
        <div class="flex justify-between h-20">
            <div class="flex space-x-6">
                <!-- Logo -->
                <div class="shrink-0 flex items-center">
                    <a href="{{ route('admin.dashboard') }}">
                        <x-application-mark class="block h-12 w-auto" />
                    </a>
                </div>

                <!-- Navigation Links -->
                <div class="hidden space-x-10 sm:-my-px sm:ms-12 sm:flex">
                    <x-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                        {{ __('Dashboard') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.index')">
                        {{ __('Users') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.books.index') }}" :active="request()->routeIs('admin.books.*')">
                        {{ __('Books') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">
                        {{ __('Categories') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.borrowings.index') }}" :active="request()->routeIs('admin.borrowings.*')">
                        {{ __('Borrowings') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.reservations.index') }}" :active="request()->routeIs('admin.reservations.*')">
                        {{ __('Reservations') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.documents.index') }}" :active="request()->routeIs('admin.documents.*')">
                        {{ __('Documents') }}
                    </x-nav-link>
                    {{-- <x-nav-link href="{{ route('admin.reading-history.index') }}" :active="request()->routeIs('admin.reading-history.*')">
                        {{ __('Reading History') }}
                    </x-nav-link> --}}
                    <x-nav-link href="{{ route('admin.events.index') }}" :active="request()->routeIs('admin.events.*')">
                        {{ __('Events') }}
                    </x-nav-link>
                    <x-nav-link href="{{ route('admin.skills.index') }}" :active="request()->routeIs('admin.skills.index')">
                        {{ __('Skills') }}
                    </x-nav-link>
            </div>

            <div class="hidden sm:flex sm:items-center sm:ms-8 space-x-6">
                <!-- Dark Mode Toggle -->
                <div x-data="themeSwitcher()" x-init="switchTheme()" class="flex items-center space-x-4">
                    <input id="darkModeToggle" type="checkbox" class="hidden" :checked="switchOn">

                    <button
                        x-ref="switchButton"
                        type="button"
                        @click="switchOn = !switchOn; switchTheme()"
                        :class="switchOn ? 'bg-blue-600' : 'bg-neutral-200'"
                        class="relative inline-flex h-8 py-1 focus:outline-none rounded-full w-12">
                        <span :class="switchOn ? 'translate-x-[22px]' : 'translate-x-1'" class="w-6 h-6 duration-200 ease-in-out bg-white rounded-full shadow-md"></span>
                    </button>

                    <label @click="$refs.switchButton.click(); $refs.switchButton.focus()"
                        :class="{ 'text-blue-600': switchOn, 'text-gray-400': !switchOn }"
                        class="text-sm select-none">
                        Dark Mode
                    </label>
                </div>

                <!-- Settings Dropdown -->
                <div class="ms-4 relative">
                    <x-dropdown align="right" width="56">
                        <x-slot name="trigger">
                            @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                                <button class="flex text-sm border-2 border-transparent rounded-full focus:outline-none focus:border-gray-300 transition">
                                    <img class="size-10 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                                </button>
                            @else
                                <span class="inline-flex rounded-md">
                                    <button type="button" class="inline-flex items-center px-4 py-3 border border-transparent text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                                        {{ Auth::user()->name }}

                                        <svg class="ms-2 -me-0.5 size-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                        </svg>
                                    </button>
                                </span>
                            @endif
                        </x-slot>

                        <x-slot name="content">
                            <!-- Account Management -->
                            <div class="block px-6 py-3 text-xs text-gray-400">
                                {{ __('Manage Account') }}
                            </div>

                            <x-dropdown-link href="{{ route('profile.show') }}">
                                {{ __('Profile') }}
                            </x-dropdown-link>

                            @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                                <x-dropdown-link href="{{ route('api-tokens.index') }}">
                                    {{ __('API Tokens') }}
                                </x-dropdown-link>
                            @endif

                            <div class="border-t border-gray-200 dark:border-gray-600"></div>

                            <!-- Authentication -->
                            <form method="POST" action="{{ route('logout') }}" x-data>
                                @csrf

                                <x-dropdown-link href="{{ route('logout') }}"
                                         @click.prevent="$root.submit();">
                                    {{ __('Log Out') }}
                                </x-dropdown-link>
                            </form>
                        </x-slot>
                    </x-dropdown>
                </div>
            </div>

            <!-- Hamburger -->
            <div class="-me-4 flex items-center sm:hidden">
                <button @click="open = ! open" class="inline-flex items-center justify-center p-3 rounded-md text-gray-400 dark:text-gray-500 hover:text-gray-500 dark:hover:text-gray-400 hover:bg-gray-100 dark:hover:bg-gray-900 focus:outline-none focus:bg-gray-100 dark:focus:bg-gray-900 focus:text-gray-500 dark:focus:text-gray-400 transition duration-150 ease-in-out">
                    <svg class="size-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{'hidden': open, 'inline-flex': ! open }" class="inline-flex" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{'hidden': ! open, 'inline-flex': open }" class="hidden" stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
        </div>
    </div>

    <!-- Responsive Navigation Menu -->
    <div :class="{'block': open, 'hidden': ! open}" class="hidden sm:hidden">
        <div class="pt-4 pb-6 space-y-2">
            <x-responsive-nav-link href="{{ route('admin.dashboard') }}" :active="request()->routeIs('admin.dashboard')">
                {{ __('Dashboard') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin.users.index') }}" :active="request()->routeIs('admin.users.index')">
                {{ __('Users') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin.books.index') }}" :active="request()->routeIs('admin.books.*')">
                {{ __('Books') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin.categories.index') }}" :active="request()->routeIs('admin.categories.*')">
                {{ __('Categories') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin.borrowings.index') }}" :active="request()->routeIs('admin.borrowings.*')">
                {{ __('Borrowings') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin.reservations.index') }}" :active="request()->routeIs('admin.reservations.*')">
                {{ __('Reservations') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin.documents.index') }}" :active="request()->routeIs('admin.documents.*')">
                {{ __('Documents') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin.reading-history.index') }}" :active="request()->routeIs('admin.reading-history.*')">
                {{ __('Reading History') }}
            </x-responsive-nav-link>
            <x-responsive-nav-link href="{{ route('admin.events.index') }}" :active="request()->routeIs('admin.events.*')">
                {{ __('Events') }}
            </x-responsive-nav-link>
            {{-- <x-responsive-nav-link href="{{ route('admin.activities.index') }}" :active="request()->routeIs('admin.activities.*')">
                {{ __('Activities') }}
            </x-responsive-nav-link> --}}

            <x-responsive-nav-link href="{{ route('admin.skills.index') }}" :active="request()->routeIs('admin.skills.index')">
                {{ __('Skills') }}
            </x-responsive-nav-link>

        </div>

        <!-- Responsive Settings Options -->
        <div class="pt-6 pb-4 border-t border-gray-200 dark:border-gray-600">
            <div class="flex items-center px-6">
                @if (Laravel\Jetstream\Jetstream::managesProfilePhotos())
                    <div class="shrink-0 me-4">
                        <img class="size-12 rounded-full object-cover" src="{{ Auth::user()->profile_photo_url }}" alt="{{ Auth::user()->name }}" />
                    </div>
                @endif

                <div>
                    <div class="font-medium text-lg text-gray-800 dark:text-gray-200">{{ Auth::user()->name }}</div>
                    <div class="font-medium text-sm text-gray-500">{{ Auth::user()->email }}</div>
                </div>
            </div>

            <div class="mt-4 space-y-2">
                <!-- Account Management -->
                <x-responsive-nav-link href="{{ route('profile.show') }}" :active="request()->routeIs('profile.show')">
                    {{ __('Profile') }}
                </x-responsive-nav-link>

                @if (Laravel\Jetstream\Jetstream::hasApiFeatures())
                    <x-responsive-nav-link href="{{ route('api-tokens.index') }}" :active="request()->routeIs('api-tokens.index')">
                        {{ __('API Tokens') }}
                    </x-responsive-nav-link>
                @endif

                <!-- Authentication -->
                <form method="POST" action="{{ route('logout') }}" x-data>
                    @csrf

                    <x-responsive-nav-link href="{{ route('logout') }}"
                                   @click.prevent="$root.submit();">
                        {{ __('Log Out') }}
                    </x-responsive-nav-link>
                </form>
            </div>
        </div>
    </div>
</nav>
