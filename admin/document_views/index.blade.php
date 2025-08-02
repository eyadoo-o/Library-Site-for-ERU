<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Document Views') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form method="GET" action="{{ route('document-views.index') }}" id="filter-form" class="mb-6">
                    <div class="flex items-center space-x-4">
                        <div>
                            <select name="document_id" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" onchange="document.getElementById('filter-form').submit()">
                                <option value="">{{ __('All Documents') }}</option>
                                @foreach($documents as $id => $title)
                                    <option value="{{ $id }}" {{ request('document_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </form>

                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Document</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">User</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Viewed At</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($views as $view)
                        <tr>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                <a href="{{ route('admin.documents.show', $view->document) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">
                                    {{ $view->document->title }}
                                </a>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                {{ $view->user->name }}
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                {{ $view->viewed_at->format('M d, Y H:i:s') }}
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                {{ __('No document views found.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>

                <div class="mt-4">
                    {{ $views->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
