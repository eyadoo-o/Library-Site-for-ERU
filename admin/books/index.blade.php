<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('Books') }}
            </h2>
            <a href="{{ route('admin.books.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">
                {{ __('Add Book') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form method="GET" action="{{ route('admin.books.index') }}" id="filter-form" class="flex items-center space-x-4 mb-6">
                    <div>
                        <select name="category_id" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" onchange="document.getElementById('filter-form').submit()">
                            <option value="">{{ __('All Categories') }}</option>
                            @foreach($categories as $id => $name)
                                <option value="{{ $id }}" {{ request('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 max-w-xs">
                        <input type="text" name="search" placeholder="Search by title, author, or ISBN" value="{{ request('search') }}" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-400" onkeyup="delayedSubmit()">
                    </div>
                </form>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Image</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Title</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Authors</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">ISBN</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Edition</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Category</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($books as $book)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                @if($book->image)
                                    <img src="{{ asset('storage/' . $book->image) }}" alt="{{ $book->title }}" class="w-16 h-20 object-cover rounded">
                                @else
                                    <div class="w-16 h-20 bg-gray-200 dark:bg-gray-700 flex items-center justify-center rounded">
                                        <span class="text-gray-500 dark:text-gray-400 text-xs">No image</span>
                                    </div>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $book->title }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $book->authors_string }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $book->isbn }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $book->edition ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $book->category->name }}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.books.edit', $book) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">Edit</a>
                                <form class="inline-block" action="{{ route('admin.books.destroy', $book) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600 ml-4" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                {{ __('No books found.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $books->links() }}
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
