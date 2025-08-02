<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('Reservations') }}
            </h2>
            <a href="{{ route('admin.reservations.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">
                {{ __('New Reservation') }}
            </a>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
                        <span class="block sm:inline">{{ session('success') }}</span>
                    </div>
                @endif

                <form method="GET" action="{{ route('admin.reservations.index') }}" id="filter-form" class="mb-6">
                    <div class="flex items-center space-x-4">
                        <div>
                            <select name="status" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300" onchange="document.getElementById('filter-form').submit()">
                                <option value="">{{ __('All Statuses') }}</option>
                                @foreach($statuses as $value => $label)
                                    <option value="{{ $value }}" {{ request('status') == $value ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="flex-1 max-w-xs">
                            <input type="text" name="search" placeholder="Search reservations..." value="{{ request('search') }}" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 placeholder-gray-400 dark:placeholder-gray-400" onkeyup="delayedSubmit()">
                        </div>
                    </div>
                </form>

                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200 dark:divide-gray-700">
                        <thead class="bg-gray-50 dark:bg-gray-700">
                            <tr>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Book</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">User</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Reserved Date</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Status</th>
                                <th class="px-6 py-3 text-left text-sm font-medium text-gray-500 dark:text-gray-300">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="bg-white dark:bg-gray-800 divide-y divide-gray-200 dark:divide-gray-700">
                            @forelse($reservations as $reservation)
                            <tr>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                    {{ $reservation->book->title }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                    {{ $reservation->user->name }}
                                </td>
                                <td class="px-6 py-4 text-sm text-gray-900 dark:text-gray-300">
                                    {{ $reservation->reserved_at->format('Y-m-d') }}
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    @if($reservation->status == 'pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800">
                                            Pending
                                        </span>
                                    @elseif($reservation->status == 'approved')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800">
                                            Approved
                                        </span>
                                    @elseif($reservation->status == 'canceled')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-red-100 text-red-800">
                                            Canceled
                                        </span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-sm">
                                    <a href="{{ route('admin.reservations.edit', $reservation) }}" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600 mr-2">Edit</a>
                                    @if($reservation->status == 'pending')
                                    <form class="inline-block" action="{{ route('admin.reservations.approve', $reservation) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-green-600 hover:text-green-800 dark:text-green-400 dark:hover:text-green-600 mr-2">Approve</button>
                                    </form>
                                    @endif
                                    <form class="inline-block" action="{{ route('admin.reservations.destroy', $reservation) }}" method="POST">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600" onclick="return confirm('Are you sure?')">Delete</button>
                                    </form>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500 dark:text-gray-300">
                                    {{ __('No reservations found.') }}
                                </td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $reservations->links() }}
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
