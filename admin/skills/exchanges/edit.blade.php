<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit Skill Exchange Request') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('admin.skills.exchanges.update', $exchange) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="skill_id" value="{{ __('Requested Skill') }}" />
                            <select id="skill_id" name="skill_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select a skill</option>
                                @foreach($skills as $skill)
                                    <option value="{{ $skill->id }}" {{ old('skill_id', $exchange->skill_id) == $skill->id ? 'selected' : '' }}>
                                        {{ $skill->name }} (Mentor: {{ $skill->mentor->name }})
                                    </option>
                                @endforeach
                            </select>
                            @error('skill_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="exchange_with_id" value="{{ __('Your Skill to Exchange') }}" />
                            <select id="exchange_with_id" name="exchange_with_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select your skill to exchange</option>
                                @foreach(Auth::user()->skills as $skill)
                                    <option value="{{ $skill->id }}" {{ old('exchange_with_id', $exchange->exchange_with_id) == $skill->id ? 'selected' : '' }}>
                                        {{ $skill->name }} ({{ $skill->category }})
                                    </option>
                                @endforeach
                            </select>
                            @error('exchange_with_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-label for="description" value="{{ __('Description') }}" />
                            <textarea id="description" name="description" rows="4"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>{{ old('description', $exchange->description) }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.skills.exchanges') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-button>
                            {{ __('Update Exchange Request') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
