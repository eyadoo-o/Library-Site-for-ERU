<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Create Skill Exchange') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('admin.skills.exchanges.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- First User -->
                        <div>
                            <x-label for="user1_id" value="{{ __('First User') }}" />
                            <select id="user1_id" name="user1_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select first user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user1_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user1_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Second User -->
                        <div>
                            <x-label for="user2_id" value="{{ __('Second User') }}" />
                            <select id="user2_id" name="user2_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select second user</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}" {{ old('user2_id') == $user->id ? 'selected' : '' }}>
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                            @error('user2_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Skill from First User -->
                        <div>
                            <x-label for="skill1_id" value="{{ __('Skill from First User') }}" />
                            <select id="skill1_id" name="skill1_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select a skill</option>
                                @if(!empty($user1Skills))
                                    @foreach($user1Skills as $skill)
                                        <option value="{{ $skill->id }}" {{ old('skill1_id') == $skill->id ? 'selected' : '' }}>
                                            {{ $skill->name }} ({{ $skill->category }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('skill1_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Skill from Second User -->
                        <div>
                            <x-label for="skill2_id" value="{{ __('Skill from Second User') }}" />
                            <select id="skill2_id" name="skill2_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" required>
                                <option value="">Select a skill</option>
                                @if(!empty($user2Skills))
                                    @foreach($user2Skills as $skill)
                                        <option value="{{ $skill->id }}" {{ old('skill2_id') == $skill->id ? 'selected' : '' }}>
                                            {{ $skill->name }} ({{ $skill->category }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                            @error('skill2_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-label for="description" value="{{ __('Description') }}" />
                            <textarea id="description" name="description" rows="4"
                                class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm"
                                required>{{ old('description') }}</textarea>
                            @error('description')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.skills.index') }}#exchanges"  class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-button>
                            {{ __('Create Exchange') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Update skills dropdowns when users change
        document.getElementById('user1_id').addEventListener('change', function() {
            fetchUserSkills(this.value, 'skill1_id');
        });

        document.getElementById('user2_id').addEventListener('change', function() {
            fetchUserSkills(this.value, 'skill2_id');
        });

        function fetchUserSkills(userId, targetSelect) {
            if (!userId) return;
            fetch(`/admin/user-skills/${userId}`)
                .then(response => {
                    if (!response.ok) {
                        throw new Error('Network response was not ok');
                    }
                    return response.json();
                })
                .then(data => {
                    const select = document.getElementById(targetSelect);
                    select.innerHTML = '<option value="">Select a skill</option>';

                    data.forEach(skill => {
                        const option = document.createElement('option');
                        option.value = skill.id;
                        option.textContent = `${skill.name} (${skill.category})`;
                        select.appendChild(option);
                    });
                })
                .catch(error => {
                    console.error('Error fetching skills:', error);
                });
        }
    </script>
</x-app-layout>
