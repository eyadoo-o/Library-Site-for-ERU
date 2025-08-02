<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Create New Activity') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('admin.activities.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 gap-6">
                        <div>
                            <x-label for="name" value="{{ __('Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')" required autofocus />
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="description" value="{{ __('Description') }}" />
                            <textarea id="description" name="description" rows="4" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="location" value="{{ __('Location') }}" />
                            <x-input id="location" class="block mt-1 w-full" type="text" name="location" :value="old('location')" />
                            @error('location')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="image" value="{{ __('Image') }}" />
                            <input type="file" id="image" name="image" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"/>
                            @error('image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-label for="start_date" value="{{ __('Start Date') }}" />
                                <x-input id="start_date" class="block mt-1 w-full" type="datetime-local" name="start_date" :value="old('start_date')" />
                            </div>
                            <div>
                                <x-label for="end_date" value="{{ __('End Date') }}" />
                                <x-input id="end_date" class="block mt-1 w-full" type="datetime-local" name="end_date" :value="old('end_date')" />
                            </div>
                        </div>

                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <x-label for="activity_type" value="{{ __('Activity Type') }}" />
                                <select id="activity_type" name="activity_type" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="public" {{ old('activity_type') == 'public' ? 'selected' : '' }}>Public</option>
                                    <option value="private" {{ old('activity_type') == 'private' ? 'selected' : '' }}>Private</option>
                                </select>
                            </div>
                            <div>
                                <x-label for="status" value="{{ __('Status') }}" />
                                <select id="status" name="status" class="w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                    <option value="active" {{ old('status') == 'active' ? 'selected' : '' }}>Active</option>
                                    <option value="inactive" {{ old('status') == 'inactive' ? 'selected' : '' }}>Inactive</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.activities.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-button>
                            {{ __('Create Activity') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
