<x-app-layout>
    <x-slot name="header">
        <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
            {{ __('Reading History') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form method="GET" action="{{ route('admin.reading-history.index') }}" id="filter-form" class="mb-6">
                    <div class="flex items-center space-x-4">
                        <div>
                            <select name="type" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" onchange="this.form.submit()">
                                <option value="">All Types</option>
                                <option value="books" {{ request('type') == 'books' ? 'selected' : '' }}>Books Only</option>
                                <option value="documents" {{ request('type') == 'documents' ? 'selected' : '' }}>Documents Only</option>
                            </select>
                        </div>
                        <div class="flex-1 max-w-xs">
                            <input type="text" name="search" placeholder="Search..." value="{{ request('search') }}"
                                class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Item</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Type</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Accessed At</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Device Info</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($history as $item)
                                <tr>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        @if($item->book)
                                            {{ $item->book->title }}
                                        @elseif($item->document)
                                            {{ $item->document->title }}
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        {{ $item->book ? 'Book' : 'Document' }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        {{ $item->accessed_at->format('M d, Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                        <span class="block">{{ $item->browser }} on {{ $item->platform }}</span>
                                        <span class="text-xs text-gray-500">{{ $item->device }}</span>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="4" class="px-6 py-4 text-center text-gray-500 dark:text-gray-400">
                                        No reading history found.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $history->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
