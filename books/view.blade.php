<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" dir="{{ app()->getLocale() == 'ar' ? 'rtl' : 'ltr' }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ $book->title }} - {{ __('messages.online_reader') }}</title>

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

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
            <div class="flex items-center justify-between mb-6">
                <h1 class="text-3xl font-serif font-bold">{{ $book->title }}</h1>
                <a href="{{ route('books.show', $book) }}" class="text-[#263e62] hover:text-[#bb0017]">
                    <span>&larr; {{ __('messages.back_to_book') }}</span>
                </a>
            </div>

            <div class="bg-gray-50 dark:bg-gray-800 rounded-lg shadow-lg p-6">
                <div class="w-full h-screen">
                    @if($book->file_path)
                        @php
                            $extension = pathinfo(Storage::url($book->file_path), PATHINFO_EXTENSION);
                        @endphp

                        @if(in_array(strtolower($extension), ['pdf']))
                            <div class="relative w-full h-full">
                                <iframe id="pdfViewer" src="{{ Storage::url($book->file_path) }}" class="w-full h-full border-0"></iframe>
                                <button onclick="toggleFullScreen()" class="absolute top-4 right-4 bg-[#263e62] text-white py-2 px-4 rounded hover:bg-[#bb0017] transition">
                                    {{ __('messages.full_screen') ?? 'Full Screen' }}
                                </button>
                            </div>
                            <script>
                                function toggleFullScreen() {
                                    const iframe = document.getElementById('pdfViewer');
                                    if (iframe.requestFullscreen) {
                                        iframe.requestFullscreen();
                                    } else if (iframe.mozRequestFullScreen) { /* Firefox */
                                        iframe.mozRequestFullScreen();
                                    } else if (iframe.webkitRequestFullscreen) { /* Chrome, Safari & Opera */
                                        iframe.webkitRequestFullscreen();
                                    } else if (iframe.msRequestFullscreen) { /* IE/Edge */
                                        iframe.msRequestFullscreen();
                                    }
                                }
                            </script>
                        @elseif(in_array(strtolower($extension), ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ Storage::url($book->file_path) }}" alt="{{ $book->title }}" class="max-w-full h-auto mx-auto">
                        @elseif(in_array(strtolower($extension), ['epub', 'mobi']))
                            <div class="text-center py-10">
                                <p class="mb-4">{{ __('messages.download_required') }}</p>
                                <a href="{{ Storage::url($book->file_path) }}" download class="bg-[#263e62] text-white py-2 px-4 rounded hover:bg-[#bb0017] transition">
                                    {{ __('messages.download_book') }}
                                </a>
                            </div>
                        @else
                            <div class="text-center py-10">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-4 text-lg">{{ __('messages.format_not_supported') }}</p>
                                <p class="text-sm text-gray-500">File type: {{ strtoupper($extension) }}</p>
                            </div>
                        @endif
                    @else
                        <div class="text-center py-10">
                            <div class="bg-amber-50 border-l-4 border-amber-500 p-4 mb-6">
                                <p class="text-amber-700">
                                    {{ __('messages.digital_book_no_file') ?? 'This digital book has no file attached.' }}
                                </p>
                            </div>
                            <div class="max-w-2xl mx-auto">
                                <h2 class="text-xl font-semibold mb-4">{{ $book->title }}</h2>
                                @if(is_array($book->authors))
                                    <p class="mb-2">{{ __('messages.by') ?? 'By' }}: {{ implode(', ', $book->authors) }}</p>
                                @endif
                                <p class="mb-4">{{ __('messages.from') ?? 'From' }}: {{ $book->category->name }}</p>
                                <div class="flex justify-center">
                                    @if(isset($book->image) && $book->image)
                                        <img src="{{ Storage::url($book->image) }}" alt="{{ $book->title }}" class="max-w-xs h-auto">
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Footer -->
        @include('components.footer')
    </body>
</html>
