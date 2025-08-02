<x-app-layout>
    <x-slot name="header">
        <div class="max-w-4xl mx-auto flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('Edit User') }}
            </h2>
            <a href="{{ route('admin.users.index') }}" class="bg-gray-500 hover:bg-gray-600 text-white font-semibold py-2 px-4 rounded-md">
                {{ __('Back') }}
            </a>
        </div>
    </x-slot>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form method="POST" action="{{ route('admin.users.update', $user) }}" class="space-y-6" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <div>
                        <x-input-label for="name" value="Name" />
                        <x-text-input id="name" name="name" type="text" class="block w-full mt-1" :value="old('name', $user->name)" required />
                        <x-input-error :messages="$errors->get('name')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="email" value="Email" />
                        <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)" required />
                        <x-input-error :messages="$errors->get('email')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="faculty_id" value="Faculty ID" />
                        <x-text-input id="faculty_id" name="faculty_id" type="text" class="block w-full mt-1" :value="old('faculty_id', $user->faculty_id)" />
                        <x-input-error :messages="$errors->get('faculty_id')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="type" value="Type" />
                        <select id="type" name="type" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" required>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ $user->type == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                        <x-input-error :messages="$errors->get('type')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="profile_photo" value="Profile Photo" />
                        @if($user->profile_photo_path)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="h-20 w-20 rounded-full object-cover">
                            </div>
                        @endif
                        <input id="profile_photo" name="profile_photo" type="file" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" />
                        <x-input-error :messages="$errors->get('profile_photo')" class="mt-2" />
                    </div>

                    <div>
                        <x-input-label for="password" value="Password (leave empty to keep current)" />
                        <x-text-input id="password" name="password" type="password" class="block w-full mt-1" />
                        <x-input-error :messages="$errors->get('password')" class="mt-2" />
                    </div>

                    <div class="flex justify-between items-center mt-6">
                        <div class="flex space-x-4">
                            <a href="{{ route('admin.users.index') }}" class="bg-green-500 hover:bg-green-600 text-white font-semibold py-2 px-4 rounded-md">
                                {{ __('Cancel') }}
                            </a>
                             <button type="submit" class="bg-red-500 hover:bg-red-600 text-white font-semibold py-2 px-4 rounded-md">
                                {{ __('Update User') }}
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
