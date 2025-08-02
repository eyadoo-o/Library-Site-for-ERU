<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('Activities') }}
            </h2>
            <a href="{{ route('admin.activities.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">
                {{ __('Create Activity') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.activities.index') }}" class="mb-6">
                    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Search</label>
                            <input type="text" name="search" id="search" placeholder="Search activities..." value="{{ request('search') }}"
                                class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                        </div>

                        <div>
                            <label for="status" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Status</label>
                            <select name="status" id="status" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" {{ request('status') == 'all' || !request('status') ? 'selected' : '' }}>All Statuses</option>
                                <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Active</option>
                                <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                            </select>
                        </div>

                        <div>
                            <label for="activity_type" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Type</label>
                            <select name="activity_type" id="activity_type" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                                <option value="all" {{ request('activity_type') == 'all' || !request('activity_type') ? 'selected' : '' }}>All Types</option>
                                <option value="public" {{ request('activity_type') == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ request('activity_type') == 'private' ? 'selected' : '' }}>Private</option>
                            </select>
                        </div>

                        <div class="md:col-span-4 grid grid-cols-1 md:grid-cols-3 gap-4 mt-2">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date From</label>
                                <input type="date" name="date_from" id="date_from" value="{{ request('date_from') }}"
                                    class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Date To</label>
                                <input type="date" name="date_to" id="date_to" value="{{ request('date_to') }}"
                                    class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                            </div>

                            <div class="flex items-end">
                                <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md mr-2">Filter</button>
                                <a href="{{ route('admin.activities.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md">Reset</a>
                            </div>
                        </div>
                    </div>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($activities as $activity)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm overflow-hidden">
                            <div class="p-6">
                                <div class="flex justify-between items-start mb-2">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $activity->name }}</h3>
                                    <span class="px-2 py-1 text-xs rounded-full {{ $activity->status_badge }}">
                                        {{ ucfirst($activity->status) }}
                                    </span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($activity->description, 100) }}</p>
                                <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                                    <div>Created by {{ $activity->creator->name }}</div>
                                    @if($activity->location)
                                        <div>Location: {{ $activity->location }}</div>
                                    @endif
                                    @if($activity->start_date)
                                        <div>Starts: {{ $activity->start_date->format('M d, Y H:i') }}</div>
                                    @endif
                                    @if($activity->end_date)
                                        <div>Ends: {{ $activity->end_date->format('M d, Y H:i') }}</div>
                                    @endif
                                    <div>Type: {{ ucfirst($activity->activity_type) }}</div>
                                    <div>Members: {{ $activity->current_members }}</div>
                                    <div>Created {{ $activity->created_at->diffForHumans() }}</div>
                                    <div>Total visits: {{ $activity->visits->count() }}</div>
                                </div>
                                @if($activity->image)
                                    <img src="{{ Storage::url($activity->image) }}" alt="{{ $activity->name }}" class="mt-4 rounded-lg w-full h-48 object-cover">
                                @endif
                            </div>
                            @if(auth()->user()->can('update', $activity))
                                <div class="bg-gray-100 dark:bg-gray-600 px-6 py-3 flex justify-end space-x-3">
                                    <a href="{{ route('admin.activities.edit', $activity) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">Edit</a>
                                    <form action="{{ route('admin.activities.destroy', $activity) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </div>
                            @endif
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-8 text-gray-500 dark:text-gray-400">
                            No activities found.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $activities->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
