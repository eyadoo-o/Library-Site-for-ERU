<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('Events') }}
            </h2>
            <a href="{{ route('admin.events.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">
                {{ __('Create Event') }}
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

                <form method="GET" action="{{ route('admin.events.index') }}" class="mb-6">
                    <div class="flex-1 max-w-xs">
                        <input type="text" name="search" placeholder="Search events..." value="{{ request('search') }}"
                            class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200">
                    </div>
                </form>

                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @forelse($events as $event)
                        <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm overflow-hidden">
                            @if($event->image)
                                <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}" class="w-full h-48 object-cover">
                            @endif
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-2">
                                    <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $event->title }}</h3>
                                    <span class="px-2 py-1 text-xs font-medium rounded-full
                                        {{ $event->status === 'published' ? 'bg-green-200 text-green-900' :
                                          ($event->status === 'cancelled' ? 'bg-red-200 text-red-900' :
                                          'bg-blue-200 text-blue-900') }}">
                                        {{ ucfirst($event->status ?? 'draft') }}
                                    </span>
                                </div>
                                <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($event->description, 100) }}</p>
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                    </svg>
                                    {{ $event->event_date->format('F j, Y g:i A') }}
                                </div>
                                @if($event->location)
                                <div class="flex items-center text-sm text-gray-500 dark:text-gray-400 mb-2">
                                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    {{ $event->location }}
                                </div>
                                @endif
                                <div class="flex flex-wrap gap-2 items-center text-sm mb-4">
                                    <span class="bg-blue-200 text-blue-900 font-medium px-2 py-1 rounded-full">
                                        {{ $event->registrations_count }} {{ Str::plural('Registration', $event->registrations_count) }}
                                    </span>
                                    @if($event->max_attendees > 0)
                                        <span class="bg-purple-200 text-purple-900 font-medium px-2 py-1 rounded-full">
                                            {{ $event->current_attendees }}/{{ $event->max_attendees }} Attendees
                                        </span>
                                    @endif
                                    <span class="{{ $event->event_type === 'public' ? 'bg-green-200 text-green-900' : 'bg-orange-200 text-orange-900' }} font-medium px-2 py-1 rounded-full">
                                        {{ ucfirst($event->event_type ?? 'public') }}
                                    </span>
                                </div>
                            </div>
                            <div class="bg-gray-100 dark:bg-gray-600 px-6 py-3 flex justify-between items-center">
                                <div class="flex space-x-3">

                                        <a href="{{ route('admin.events.edit', $event) }}" class="text-blue-700 hover:text-blue-900 dark:text-blue-300 dark:hover:text-blue-100 font-medium">

                                            Edit
                                        </a>
                                        <form action="{{ route('admin.events.destroy', $event) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-700 hover:text-red-900 dark:text-red-300 dark:hover:text-red-100 font-medium"
                                                   onclick="return confirm('Are you sure you want to delete this event?')">

                                                Delete
                                            </button>
                                        </form>


                                    @if($event->isRegistered(auth()->user()))
                                        <form action="{{ route('admin.events.unregister', $event) }}" method="POST" class="inline">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="text-red-700 hover:text-red-900 dark:text-red-300 dark:hover:text-red-100 font-medium">
                                                Unregister
                                            </button>
                                        </form>
                                    @else
                                        @if(auth()->user()->can('update', $event) || auth()->user()->is_admin)
                                            <button type="button" onclick="toggleUserRegistrationModal({{ $event->id }})"
                                                class="text-green-700 hover:text-green-900 dark:text-green-300 dark:hover:text-green-100 font-medium">

                                                Register User
                                            </button>
                                        @else
                                            <form action="{{ route('admin.events.register', $event) }}" method="POST" class="inline">
                                                @csrf
                                                <button type="submit"
                                                    class="{{ ($event->max_attendees > 0 && $event->current_attendees >= $event->max_attendees) ? 'text-gray-500 cursor-not-allowed' : 'text-green-700 hover:text-green-900 dark:text-green-300 dark:hover:text-green-100' }} font-medium"
                                                    {{ ($event->max_attendees > 0 && $event->current_attendees >= $event->max_attendees) ? 'disabled' : '' }}>

                                                    {{ ($event->max_attendees > 0 && $event->current_attendees >= $event->max_attendees) ? 'Full' : 'Register' }}
                                                </button>
                                            </form>
                                        @endif
                                    @endif
                                </div>
                                <a href="{{ route('admin.events.registrations', $event) }}" class="text-indigo-700 hover:text-indigo-900 dark:text-indigo-300 dark:hover:text-indigo-100 font-medium">

                                    View Registrations
                                </a>
                            </div>
                        </div>
                    @empty
                        <div class="col-span-3 text-center py-8 text-gray-500 dark:text-gray-400">
                            No events found.
                        </div>
                    @endforelse
                </div>

                <div class="mt-6">
                    {{ $events->links() }}
                </div>
            </div>
        </div>
    </div>

    <!-- Modal for User Registration -->
    <div id="userRegistrationModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 hidden overflow-y-auto h-full w-full" style="z-index: 50;">
        <div class="relative top-20 mx-auto p-5 border w-96 shadow-lg rounded-md bg-white dark:bg-gray-800">
            <div class="mt-3 text-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900 dark:text-white">Register User</h3>
                <div class="mt-2 px-7 py-3">
                    <form id="registerUserForm" method="POST">
                        @csrf
                        <input type="hidden" id="eventIdField" name="event_id" value="">

                        <div class="mb-4">
                            <label for="user_email" class="block text-sm font-medium text-gray-700 dark:text-gray-300 text-left">User Email</label>
                            <input id="user_email" name="user_email" type="email" class="mt-1 block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-white rounded-md shadow-sm" required>
                        </div>

                        <div class="flex justify-between mt-4">
                            <button type="button" onclick="closeUserRegistrationModal()" class="px-4 py-2 bg-gray-200 text-gray-800 text-base font-medium rounded-md shadow-sm hover:bg-gray-300 focus:outline-none">
                                Cancel
                            </button>
                            <button type="submit" class="px-4 py-2 bg-green-600 text-white text-base font-medium rounded-md shadow-sm hover:bg-green-700 focus:outline-none">
                                Register
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        function toggleUserRegistrationModal(eventId) {
            const modal = document.getElementById('userRegistrationModal');
            document.getElementById('eventIdField').value = eventId;
            document.getElementById('registerUserForm').action = `/admin/events/${eventId}/register-user`;
            modal.classList.toggle('hidden');
        }

        function closeUserRegistrationModal() {
            document.getElementById('userRegistrationModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
