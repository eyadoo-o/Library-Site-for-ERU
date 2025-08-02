<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ $document->title }}
            </h2>
            <div>
                <a href="{{ route('admin.documents.download', $document) }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">
                    {{ __('Download') }}
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <div class="mb-6">
                    <div class="flex items-center mb-4">
                        <span class="px-3 py-1 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 rounded-full text-sm mr-2">
                            @php
                                $documentTypes = [
                                    'exam' => 'Exam',
                                    'article' => 'Article',
                                    'book' => 'Book',
                                    'research_paper' => 'Research Paper',
                                    'audio_book' => 'Audio Book',
                                    'podcast' => 'Podcast',
                                ];
                            @endphp
                            {{ $documentTypes[$document->type] ?? $document->type }}
                        </span>
                        <span class="text-gray-500 dark:text-gray-400 text-sm">
                            Uploaded by {{ $document->uploader->name }} on {{ $document->created_at->format('F j, Y') }}
                        </span>
                    </div>

                    <div class="prose dark:prose-invert max-w-none">
                        @if($document->description)
                            <p>{{ $document->description }}</p>
                        @else
                            <p class="text-gray-500 dark:text-gray-400 italic">No description provided</p>
                        @endif
                    </div>
                </div>

                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white mb-4">Document Preview</h3>

                    @php
                        $extension = pathinfo(Storage::url($document->file_path), PATHINFO_EXTENSION);
                    @endphp

                    <div class="bg-gray-100 dark:bg-gray-700 rounded-lg p-4">
                        @if(in_array($extension, ['pdf']))
                            <iframe src="{{ Storage::url($document->file_path) }}" class="w-full h-96 border-0 rounded"></iframe>
                        @elseif(in_array($extension, ['jpg', 'jpeg', 'png', 'gif']))
                            <img src="{{ Storage::url($document->file_path) }}" alt="{{ $document->title }}" class="max-w-full h-auto mx-auto">
                        @elseif(in_array($extension, ['mp3', 'wav', 'ogg']))
                            <audio controls class="w-full">
                                <source src="{{ Storage::url($document->file_path) }}" type="audio/{{ $extension }}">
                                Your browser does not support the audio element.
                            </audio>
                        @elseif(in_array($extension, ['mp4', 'webm', 'ogg']))
                            <video controls class="w-full">
                                <source src="{{ Storage::url($document->file_path) }}" type="video/{{ $extension }}">
                                Your browser does not support the video tag.
                            </video>
                        @else
                            <div class="text-center py-10">
                                <svg class="mx-auto h-12 w-12 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                </svg>
                                <p class="mt-2 text-sm text-gray-500 dark:text-gray-400">Preview not available for this file type</p>
                                <p class="mt-1 text-xs text-gray-500 dark:text-gray-400">File type: {{ strtoupper($extension) }}</p>
                            </div>
                        @endif
                    </div>
                </div>

                <div class="mt-6 flex justify-between">
                    <div>
                        <a href="{{ route('admin.documents.index') }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">
                            &larr; Back to Documents
                        </a>
                    </div>
                    <div>
                        <a href="{{ route('admin.document-views.stats', $document) }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-600">
                            View Statistics
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
