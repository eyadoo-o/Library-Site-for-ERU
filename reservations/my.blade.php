<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ __('My Book Reservations') }} - ERU  Library</title>

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
    <body class="bg-gray-50 dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">
        @include('components.navbar')

        <main>
            <section class="py-20">
                <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                    <div class="text-center mb-16">
                        <h1 class="font-serif text-3xl md:text-4xl font-bold text-gray-900 dark:text-white">{{ __('My Book Reservations') }}</h1>
                        <div class="w-24 h-1 bg-[#263e62] dark:bg-blue-500 mx-auto mt-4"></div>
                        <p class="mt-6 text-lg text-gray-600 dark:text-gray-400 max-w-3xl mx-auto">
                            {{ __('Here you can view all the books you have reserved, along with their current status.') }}
                        </p>
                    </div>

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <div class="p-6 sm:p-8 text-gray-900 dark:text-gray-100">
                            @if($reservations->isEmpty())
                                <p class="text-center text-gray-500 dark:text-gray-400 py-8">{{ __('You have no book reservations at the moment.') }}</p>
                                <div class="text-center mt-6">
                                    <a href="{{ route('books') }}" class="px-6 py-3 bg-[#263e62] text-white rounded-full font-medium hover:bg-[#1d324f] transition duration-300">
                                        {{ __('Explore Books') }}
                                    </a>
                                </div>
                            @else
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                                        <thead class="bg-gray-50 dark:bg-gray-700">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ __('Book Title') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ __('Author') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ __('Reserved At') }}
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 dark:text-gray-300 uppercase tracking-wider">
                                                    {{ __('Status') }}
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                                            @foreach ($reservations as $reservation)
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 dark:text-gray-100">
                                                        {{ $reservation->book->title }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                        {{ $reservation->book->author }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 dark:text-gray-300">
                                                        {{ $reservation->reserved_at->format('M d, Y H:i') }}
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full
                                                            @switch($reservation->status)
                                                                @case('pending') bg-yellow-100 text-yellow-800 dark:bg-yellow-700 dark:text-yellow-100 @break
                                                                @case('approved') bg-green-100 text-green-800 dark:bg-green-700 dark:text-green-100 @break
                                                                @case('rejected')
                                                                @case('cancelled') bg-red-100 text-red-800 dark:bg-red-700 dark:text-red-100 @break
                                                                @case('completed') bg-blue-100 text-blue-800 dark:bg-blue-700 dark:text-blue-100 @break
                                                                @default bg-gray-100 text-gray-800 dark:bg-gray-600 dark:text-gray-100 @break
                                                            @endswitch">
                                                            {{ $statusOptions[$reservation->status] ?? ucfirst($reservation->status) }}
                                                        </span>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>

                                <div class="mt-8">
                                    {{ $reservations->links() }}
                                </div>
                            @endif
                        </div>
                    </div>
                </div>
            </section>
        </main>

        @include('components.footer')

    </body>
</html>
