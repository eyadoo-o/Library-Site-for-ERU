<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit Borrowing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('admin.borrowings.update', $borrowing) }}" method="POST">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="user_id" value="{{ __('Borrower') }}" />
                            <select id="user_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" disabled>
                                <option>{{ $borrowing->user->name }}</option>
                            </select>
                        </div>

                        <div>
                            <x-label for="book_id" value="{{ __('Book') }}" />
                            <select id="book_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm" disabled>
                                <option>{{ $borrowing->book->title }}</option>
                            </select>
                        </div>

                        <div>
                            <x-label for="borrowed_at" value="{{ __('Borrowed Date') }}" />
                            <x-input id="borrowed_at" class="block mt-1 w-full" type="date" name="borrowed_at" :value="old('borrowed_at', $borrowing->borrowed_at->format('Y-m-d'))" required />
                            @error('borrowed_at')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="due_date" value="{{ __('Due Date') }}" />
                            <x-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', $borrowing->due_date->format('Y-m-d'))" required />
                            @error('due_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        @if($borrowing->returned_at)
                        <div>
                            <x-label for="returned_at" value="{{ __('Returned Date') }}" />
                            <x-input id="returned_at" class="block mt-1 w-full" type="date" :value="$borrowing->returned_at->format('Y-m-d')" disabled />
                        </div>
                        @endif
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.borrowings.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-button>
                            {{ __('Update Borrowing') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
