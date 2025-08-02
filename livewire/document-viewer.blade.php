<div>
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
</div>
