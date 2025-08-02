<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('Documents') }}
            </h2>
            <a href="{{ route('admin.documents.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">
                {{ __('Upload Document') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.documents.index') }}" id="filter-form" class="mb-6">
                    <div class="flex items-center space-x-4">
                        <div>
                            <select name="type" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" onchange="document.getElementById('filter-form').submit()">
                                <option value="">{{ __('All Types') }}</option>
                                @foreach($types as $value => $label)
                                    <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 max-w-xs">
                            <input type="text" name="search" placeholder="Search documents..." value="{{ request('search') }}" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-400" onkeyup="delayedSubmit()">
                        </div>
                    </div>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($documents as $document)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow overflow-hidden">
                            <div class="p-4">
                                <h3 class="text-lg font-semibold text-gray-900 dark:text-white">{{ $document->title }}</h3>
                                <p class="text-sm text-gray-500 dark:text-gray-400 mt-2">
                                    {{ Str::limit($document->description, 100) }}
                                </p>
                                <div class="flex items-center mt-3 text-sm">
                                    <span class="px-2 py-1 bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100 rounded-full text-xs">
                                        {{ $types[$document->type] }}
                                    </span>
                                    <span class="ml-3 text-gray-500 dark:text-gray-400">
                                        {{ $document->views_count }} {{ Str::plural('view', $document->views_count) }}
                                    </span>
                                </div>
                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-2">
                                    Uploaded by {{ $document->uploader->name }} on {{ $document->created_at->format('M d, Y') }}
                                </p>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-600 px-4 py-3 flex justify-between items-center">
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.documents.show', $document) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600 text-sm">View</a>
                                    <a href="{{ route('admin.documents.download', $document) }}" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-600 text-sm">Download</a>
                                    <a href="{{ route('admin.document-views.stats', $document) }}" class="text-purple-600 hover:text-purple-800 dark:text-purple-400 dark:hover:text-purple-600 text-sm">Stats</a>
                                </div>
                                <div class="flex space-x-3">
                                    <a href="{{ route('admin.documents.edit', $document) }}" class="text-gray-600 hover:text-gray-800 dark:text-gray-400 dark:hover:text-gray-200 text-sm">Edit</a>
                                    <form class="inline-block" action="{{ route('admin.documents.destroy', $document) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600 text-sm" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 p-4 text-center text-gray-500 dark:text-gray-400">
                            No documents found.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $documents->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        let delayTimer;
        function delayedSubmit() {
            clearTimeout(delayTimer);
            delayTimer = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500);
        }
    </script>
</x-app-layout>
