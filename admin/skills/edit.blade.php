<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit Skill') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('admin.skills.update', $skill) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="name" value="{{ __('Skill Name') }}" />
                            <x-input id="name" class="block mt-1 w-full" type="text" name="name" value="{{ old('name', $skill->name) }}" required autofocus />
                            @error('name')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="category" value="{{ __('Category') }}" />
                            <x-input id="category" class="block mt-1 w-full" type="text" name="category" value="{{ old('category', $skill->category) }}" required />
                            @error('category')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-label for="mentor_id" value="{{ __('Select Mentor') }}" />
                            <select id="mentor_id" name="mentor_id" required
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select a mentor</option>
                                @foreach($mentors as $mentor)
                                    <option value="{{ $mentor->id }}" {{ (old('mentor_id', $skill->mentor_id) == $mentor->id) ? 'selected' : '' }}>
                                        {{ $mentor->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('mentor_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-label for="description" value="{{ __('Description') }}" />
                            <textarea id="description" name="description" rows="4"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                            >{{ old('description', $skill->description) }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.skills.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-button>
                            {{ __('Update Skill') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
