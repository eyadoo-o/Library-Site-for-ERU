<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('Users') }}
            </h2>
            <a href="{{ route('admin.users.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">
                {{ __('Create User') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form method="GET" action="{{ route('admin.users.index') }}" id="filter-form" class="flex items-center space-x-4 mb-6">
                    <div>
                        <select name="type" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" onchange="document.getElementById('filter-form').submit()">
                            <option value="">{{ __('All Types') }}</option>
                            @foreach($types as $value => $label)
                                <option value="{{ $value }}" {{ request('type') == $value ? 'selected' : '' }}>{{ $label }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="flex-1 max-w-xs">
                        <input type="text" name="search" placeholder="Search by name or email" value="{{ request('search') }}" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-400" onkeyup="delayedSubmit()">
                    </div>
                    <div class="flex-1 max-w-xs">
                        <input type="text" name="faculty_id_filter" placeholder="Search by Faculty ID" value="{{ request('faculty_id_filter') }}" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-400" onkeyup="delayedSubmit()">
                    </div>
                </form>
                <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                    <thead class="bg-gray-50 dark:bg-gray-700">
                        <tr>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Photo</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Name</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Email</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Faculty ID</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Type</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Confirmed</th>
                            <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse($users as $user)
                        <tr>
                            <td class="px-6 py-4">
                                @if($user->profile_photo_path)
                                    <img src="{{ asset('storage/' . $user->profile_photo_path) }}" alt="Profile Photo" class="h-10 w-10 rounded-full object-cover">
                                @else
                                    <span class="text-gray-500 dark:text-gray-400">N/A</span>
                                @endif
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $user->name }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $user->email }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ $user->faculty_id ?? 'N/A' }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">{{ str_replace('_', ' ', ucfirst($user->type)) }}</td>
                            <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                {{$user->confirmed ? 'Yes' : 'No'}}</td>
                            <td class="px-6 py-4 text-sm">
                                <a href="{{ route('admin.users.edit', $user) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">Edit</a>
                                @if(!$user->confirmed)
                                    <form class="inline-block ml-4" action="{{ route('admin.users.confirm', $user) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-600" onclick="return confirm('Are you sure you want to confirm this user?')">Confirm</button>
                                    </form>
                                @endif
                                <form class="inline-block" action="{{ route('admin.users.destroy', $user) }}" method="POST">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600 ml-4" onclick="return confirm('Are you sure?')">Delete</button>
                                </form>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                {{ __('No users found.') }}
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>

    <script>
        let delayTimer;
        function delayedSubmit() {
            clearTimeout(delayTimer);
            delayTimer = setTimeout(() => {
                document.getElementById('filter-form').submit();
            }, 500);
        }
    </script>
</x-app-layout>
