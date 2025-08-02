<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>{{ __('messages.documents_title') }}</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=instrument-sans:400,500,600|playfair-display:400,600,700" rel="stylesheet" />

    <!-- Styles / Scripts -->
    @if (file_exists(public_path('build/manifest.json')) || file_exists(public_path('hot')))
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    @endif
</head>
<body class="bg-white dark:bg-gray-900 text-gray-900 dark:text-gray-100 antialiased">
    @include('components.navbar')

    <!-- Document Library Section -->
    <section class="py-20 bg-white dark:bg-gray-900">
        <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h1 class="font-serif text-4xl font-bold text-gray-900 dark:text-white">{{ __('messages.documents_title') }}</h1>
                <p class="mt-4 text-lg text-gray-600 dark:text-gray-400">{{ __('messages.documents_subtitle') }}</p>
            </div>

            <!-- Filter Navigation -->
            <div class="flex flex-wrap justify-center mb-8 gap-2">
                <a href="{{ route('documents.index', ['type' => 'all']) }}" class="px-4 py-2 text-sm font-medium rounded-full {{ request('type') == 'all' || !request('type') ? 'bg-[#263e62] text-white dark:bg-blue-400 dark:text-gray-900' : 'text-gray-700 dark:text-gray-300 hover:underline' }}">{{ __('messages.documents_filter_all') }}</a>
                <a href="{{ route('documents.index', ['type' => 'exam']) }}" class="px-4 py-2 text-sm font-medium rounded-full {{ request('type') == 'exam' ? 'bg-blue-100 text-blue-900 dark:bg-blue-900 dark:text-blue-100' : 'text-gray-700 dark:text-gray-300 hover:underline' }}">{{ __('messages.documents_filter_exam') }}</a>
                <a href="{{ route('documents.index', ['type' => 'article']) }}" class="px-4 py-2 text-sm font-medium rounded-full {{ request('type') == 'article' ? 'bg-yellow-100 text-yellow-900 dark:bg-yellow-900 dark:text-yellow-100' : 'text-gray-700 dark:text-gray-300 hover:underline' }}">{{ __('messages.documents_filter_article') }}</a>
                <a href="{{ route('documents.index', ['type' => 'book']) }}" class="px-4 py-2 text-sm font-medium rounded-full {{ request('type') == 'book' ? 'bg-green-100 text-green-900 dark:bg-green-900 dark:text-green-100' : 'text-gray-700 dark:text-gray-300 hover:underline' }}">{{ __('messages.documents_filter_book') }}</a>
                <a href="{{ route('documents.index', ['type' => 'research_paper']) }}" class="px-4 py-2 text-sm font-medium rounded-full {{ request('type') == 'research_paper' ? 'bg-red-100 text-red-900 dark:bg-red-900 dark:text-red-100' : 'text-gray-700 dark:text-gray-300 hover:underline' }}">{{ __('messages.documents_filter_research_paper') }}</a>
                <a href="{{ route('documents.index', ['type' => 'audio_book']) }}" class="px-4 py-2 text-sm font-medium rounded-full {{ request('type') == 'audio_book' ? 'bg-purple-100 text-purple-900 dark:bg-purple-900 dark:text-purple-100' : 'text-gray-700 dark:text-gray-300 hover:underline' }}">{{ __('messages.documents_filter_audio_book') }}</a>
                <a href="{{ route('documents.index', ['type' => 'podcast']) }}" class="px-4 py-2 text-sm font-medium rounded-full {{ request('type') == 'podcast' ? 'bg-gray-200 text-gray-900 dark:bg-gray-700 dark:text-gray-100' : 'text-gray-700 dark:text-gray-300 hover:underline' }}">{{ __('messages.documents_filter_podcast') }}</a>
            </div>

            <!-- Document Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @forelse($documents as $document)
                    @php
                        $typeStyles = [
                            'exam' => 'bg-blue-50 dark:bg-blue-900 border-blue-200 dark:border-blue-700',
                            'article' => 'bg-yellow-50 dark:bg-yellow-900 border-yellow-200 dark:border-yellow-700',
                            'book' => 'bg-green-50 dark:bg-green-900 border-green-200 dark:border-green-700',
                            'research_paper' => 'bg-red-50 dark:bg-red-900 border-red-200 dark:border-red-700',
                            'audio_book' => 'bg-purple-50 dark:bg-purple-900 border-purple-200 dark:border-purple-700',
                            'podcast' => 'bg-gray-100 dark:bg-gray-800 border-gray-200 dark:border-gray-700',
                        ];
                        $typeIcons = [
                            'exam' => '<svg class="h-8 w-8 text-blue-500 dark:text-blue-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M16 4h2a2 2 0 012 2v14a2 2 0 01-2 2H6a2 2 0 01-2-2V6a2 2 0 012-2h2"/><path stroke-linecap="round" stroke-linejoin="round" d="M8 2h8v4H8z"/></svg>',
                            'article' => '<svg class="h-8 w-8 text-yellow-500 dark:text-yellow-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1"/></svg>',
                            'book' => '<svg class="h-8 w-8 text-green-500 dark:text-green-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"/></svg>',
                            'research_paper' => '<svg class="h-8 w-8 text-red-500 dark:text-red-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/></svg>',
                            'audio_book' => '<svg class="h-8 w-8 text-purple-500 dark:text-purple-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" d="M15.536 8.464a5 5 0 010 7.072m2.828-9.9a9 9 0 010 12.728M5.586 15.536a5 5 0 017.072 0m-9.9-2.828a9 9 0 0112.728 0M12 18.75a.75.75 0 110-1.5.75.75 0 010 1.5z"/></svg>',
                            'podcast' => '<svg class="h-8 w-8 text-gray-500 dark:text-gray-300" fill="none" stroke="currentColor" stroke-width="1.5" viewBox="0 0 24 24"><circle cx="12" cy="12" r="3"/><path stroke-linecap="round" stroke-linejoin="round" d="M19.07 4.93a10 10 0 010 14.14M4.93 19.07a10 10 0 010-14.14"/></svg>',
                        ];
                        $style = $typeStyles[$document->type] ?? 'bg-gray-50 dark:bg-gray-900 border-gray-200 dark:border-gray-700';
                        $icon = $typeIcons[$document->type] ?? '';
                        $typeLabels = [
                            'exam' => __('messages.documents_type_exam'),
                            'article' => __('messages.documents_type_article'),
                            'book' => __('messages.documents_type_book'),
                            'research_paper' => __('messages.documents_type_research_paper'),
                            'audio_book' => __('messages.documents_type_audio_book'),
                            'podcast' => __('messages.documents_type_podcast'),
                        ];
                        $typeLabel = $typeLabels[$document->type] ?? ucfirst($document->type);
                    @endphp
                    <div class="p-6 rounded-xl shadow-md transition duration-300 border-2 {{ $style }} flex flex-col h-full">
                        <div class="flex items-center mb-4">
                            {!! $icon !!}
                            <span class="ml-3 px-3 py-1 rounded-full text-xs font-semibold {{
                                $document->type === 'exam' ? 'bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100' :
                                ($document->type === 'article' ? 'bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100' :
                                ($document->type === 'book' ? 'bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100' :
                                ($document->type === 'research_paper' ? 'bg-red-100 text-red-800 dark:bg-red-800 dark:text-red-100' :
                                ($document->type === 'audio_book' ? 'bg-purple-100 text-purple-800 dark:bg-purple-800 dark:text-purple-100' :
                                'bg-gray-200 text-gray-800 dark:bg-gray-700 dark:text-gray-100'))))
                            }}">
                                {{ $typeLabel }}
                            </span>
                        </div>
                        <h3 class="font-serif text-xl font-semibold mb-2 text-gray-900 dark:text-white">{{ $document->title }}</h3>
                        <p class="text-sm text-gray-600 dark:text-gray-400 mb-4 flex-1">{{ Str::limit($document->description, 100) }}</p>
                        <a href="{{ route('documents.download', $document) }}" class="inline-block mt-auto text-[#263e62] dark:text-blue-400 font-medium hover:underline">{{ __('messages.documents_download') }}</a>
                    </div>
                @empty
                    <p class="col-span-3 text-center text-gray-500">{{ __('messages.documents_no_documents') }}</p>
                @endforelse
            </div>

            <!-- Pagination -->
            <div class="mt-8">
                {{ $documents->links() }}
            </div>
        </div>
    </section>

    @include('components.footer')
</body>
</html>
