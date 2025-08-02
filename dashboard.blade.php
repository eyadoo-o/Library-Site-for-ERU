<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                <div class="py-6">
                    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-6">
                        <div class="bg-gradient-to-r from-blue-500 to-blue-700 text-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold">Total Users</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\User::count() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-green-500 to-green-700 text-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold">Total Books</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\Book::count() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-purple-500 to-purple-700 text-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold">Total Categories</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\Category::count() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-red-500 to-red-700 text-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold">Active Borrowings</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\Borrowing::whereNull('returned_at')->count() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-yellow-500 to-yellow-700 text-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold">Total Reservations</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\Reservation::count() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-indigo-500 to-indigo-700 text-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold">Total Documents</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\Document::count() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-pink-500 to-pink-700 text-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold">Total Events</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\Event::count() }}</p>
                        </div>
                        <div class="bg-gradient-to-r from-orange-500 to-orange-700 text-white overflow-hidden shadow-xl sm:rounded-lg p-6">
                            <h3 class="text-lg font-semibold">Total Activities</h3>
                            <p class="text-3xl font-bold">{{ \App\Models\ActivityHub::count() }}</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
