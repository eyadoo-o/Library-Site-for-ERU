<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit Event') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('admin.events.update', $event) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="title" value="{{ __('Event Title') }}" />
                            <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $event->title)" required />
                            @error('title')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="event_date" value="{{ __('Event Date & Time') }}" />
                            <x-input id="event_date" class="block mt-1 w-full" type="datetime-local" name="event_date" :value="old('event_date', $event->event_date->format('Y-m-d\TH:i'))" required />
                            @error('event_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="location" value="{{ __('Location') }}" />
                            <x-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location', $event->location)" />
                            @error('location')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="image" value="{{ __('Event Image') }}" />

                            @if($event->image)
                                <div class="mb-2">
                                    <img src="{{ $event->image_url }}" alt="{{ $event->title }}" class="h-24 w-auto object-cover rounded">
                                    <div class="mt-1">

                                    </div>
                                </div>
                            @endif

                            <input id="image" type="file" name="image"
                                class="block w-full text-sm text-gray-500 dark:text-gray-400
                                       file:mr-4 file:py-2 file:px-4
                                       file:rounded-md file:border-0
                                       file:text-sm file:font-semibold
                                       file:bg-blue-50 file:text-blue-700
                                       hover:file:bg-blue-100
                                       dark:file:bg-gray-700 dark:file:text-gray-200"
                                accept="image/*" />
                            @error('image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="status" value="{{ __('Status') }}" />
                            <select id="status" name="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="draft" {{ old('status', $event->status) == 'draft' ? 'selected' : '' }}>Draft</option>
                                <option value="published" {{ old('status', $event->status) == 'published' ? 'selected' : '' }}>Published</option>
                                <option value="cancelled" {{ old('status', $event->status) == 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                            </select>
                            @error('status')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="event_type" value="{{ __('Event Type') }}" />
                            <select id="event_type" name="event_type" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="public" {{ old('event_type', $event->event_type) == 'public' ? 'selected' : '' }}>Public</option>
                                <option value="private" {{ old('event_type', $event->event_type) == 'private' ? 'selected' : '' }}>Private</option>
                                <option value="workshop" {{ old('event_type', $event->event_type) == 'workshop' ? 'selected' : '' }}>Workshop</option>
                                <option value="seminar" {{ old('event_type', $event->event_type) == 'seminar' ? 'selected' : '' }}>Seminar</option>
                                <option value="reading" {{ old('event_type', $event->event_type) == 'reading' ? 'selected' : '' }}>Book Reading</option>
                                <option value="discussion" {{ old('event_type', $event->event_type) == 'discussion' ? 'selected' : '' }}>Panel Discussion</option>
                            </select>
                            @error('event_type')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="max_attendees" value="{{ __('Maximum Attendees (0 for unlimited)') }}" />
                            <x-input id="max_attendees" class="block mt-1 w-full" type="number" min="0" name="max_attendees" :value="old('max_attendees', $event->max_attendees)" />
                            @error('max_attendees')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-label for="description" value="{{ __('Description') }}" />
                            <textarea id="description" name="description" rows="4"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            >{{ old('description', $event->description) }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.events.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-button>
                            {{ __('Update Event') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
