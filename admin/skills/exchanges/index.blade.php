<div class="{{ isset($isEmbedded) && $isEmbedded ? '' : 'py-12' }}">
    <div class="{{ isset($isEmbedded) && $isEmbedded ? '' : 'max-w-7xl mx-auto sm:px-6 lg:px-8' }}">
        @if (session('success'))
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-4" role="alert">
                <p>{{ session('success') }}</p>
            </div>
        @endif

        @if (session('error'))
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-4" role="alert">
                <p>{{ session('error') }}</p>
            </div>
        @endif

        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg p-6">
            <h3 class="text-lg font-semibold mb-4">Filter Exchanges</h3>
            <form method="GET" action="{{ route('admin.skills.index') }}" class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
                <div>
                    <x-label for="user_id" :value="__('User')" />
                    <select name="user_id" id="user_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">All Users</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ request('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <x-label for="status" :value="__('Status')" />
                    <select name="status" id="status" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                        <option value="">All Statuses</option>
                        @foreach($statuses as $status)
                            <option value="{{ $status }}" {{ request('status') == $status ? 'selected' : '' }}>
                                {{ ucfirst($status) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="md:col-span-2 flex items-end">
                    <x-button class="ml-auto">
                        {{ __('Filter') }}
                    </x-button>
                </div>
            </form>

            <div class="overflow-x-auto">
                <table class="min-w-full bg-white dark:bg-gray-800">
                    <thead>
                        <tr>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Student</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mentor</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Skill</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Status</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Created</th>
                            <th class="py-3 px-6 text-left text-xs font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200 dark:divide-gray-700">
                        @forelse ($exchanges as $exchange)
                            <tr>
                                <td class="py-4 px-6 text-sm">{{ $exchange->student->name }}</td>
                                <td class="py-4 px-6 text-sm">{{ $exchange->mentor->name }}</td>
                                <td class="py-4 px-6 text-sm">{{ $exchange->skill->name }}</td>
                                <td class="py-4 px-6 text-sm">
                                    <span class="px-2 py-1 text-xs rounded-full
                                        @if($exchange->status === 'pending') bg-yellow-100 text-yellow-800
                                        @elseif($exchange->status === 'accepted') bg-blue-100 text-blue-800
                                        @elseif($exchange->status === 'completed') bg-green-100 text-green-800
                                        @else bg-gray-100 text-gray-800 @endif
                                    ">
                                        {{ ucfirst($exchange->status) }}
                                    </span>
                                </td>
                                <td class="py-4 px-6 text-sm">{{ $exchange->created_at->format('M d, Y') }}</td>
                                <td class="py-4 px-6 text-sm">
                                    <a href="{{ route('admin.skills.exchanges.show', $exchange) }}" class="text-blue-600 hover:text-blue-900 mr-2">
                                        View
                                    </a>
                                    <a href="{{ route('admin.skills.index') }}#exchanges" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                                        {{ __('Cancel') }}
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="py-4 px-6 text-sm text-center text-gray-500">No exchanges found.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $exchanges->links() }}
            </div>
        </div>
    </div>
</div>
