<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('New Borrowing') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('admin.borrowings.store') }}" method="POST">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="user_search" value="{{ __('Search Borrower') }}" />
                            <div class="mt-1 relative">
                                <input type="text" id="user_search" placeholder="Search by name, email or ID"
                                    class="block w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <div id="user_results" class="absolute z-10 w-full bg-white dark:bg-gray-700 border border-gray-300 dark:border-gray-600 mt-1 rounded-md shadow-lg hidden">
                                    <!-- Results will be populated here -->
                                </div>
                            </div>

                            <input type="hidden" name="user_id" id="user_id" value="{{ old('user_id') }}">
                            <div id="selected_user" class="mt-2 p-2 bg-gray-100 dark:bg-gray-600 rounded-md {{ old('user_id') ? '' : 'hidden' }}">
                                <span id="selected_user_name"></span>
                                <button type="button" class="text-red-500 hover:text-red-700 float-right" onclick="clearUser()">×</button>
                            </div>

                            @error('user_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="book_id" value="{{ __('Book') }}" />
                            <select id="book_id" name="book_id" class="block mt-1 w-full border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm">
                                <option value="">Select Book</option>
                                @foreach($books as $id => $title)
                                    <option value="{{ $id }}" {{ old('book_id') == $id ? 'selected' : '' }}>{{ $title }}</option>
                                @endforeach
                            </select>
                            @error('book_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="borrowed_at" value="{{ __('Borrowed Date') }}" />
                            <x-input id="borrowed_at" class="block mt-1 w-full" type="date" name="borrowed_at" :value="old('borrowed_at', date('Y-m-d'))" required />
                            @error('borrowed_at')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="due_date" value="{{ __('Due Date') }}" />
                            <x-input id="due_date" class="block mt-1 w-full" type="date" name="due_date" :value="old('due_date', date('Y-m-d', strtotime('+2 weeks')))" required />
                            @error('due_date')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.borrowings.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-button>
                            {{ __('Create Borrowing') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        let searchTimer;
        const userSearch = document.getElementById('user_search');
        const userResults = document.getElementById('user_results');
        const userId = document.getElementById('user_id');
        const selectedUser = document.getElementById('selected_user');
        const selectedUserName = document.getElementById('selected_user_name');

        userSearch.addEventListener('input', function() {
            clearTimeout(searchTimer);
            const query = this.value.trim();

            if (query.length < 2) {
                userResults.classList.add('hidden');
                return;
            }

            searchTimer = setTimeout(() => {
                console.log("Searching for:", query);
                fetchUsers(query);
            }, 300);
        });

        userSearch.addEventListener('focus', function() {
            if (this.value.trim().length >= 2) {
                userResults.classList.remove('hidden');
            }
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!userSearch.contains(e.target) && !userResults.contains(e.target)) {
                userResults.classList.add('hidden');
            }
        });

        function fetchUsers(query) {
            // Use CSRF token for the request
            const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');

            fetch(`/admin/admin/api/user-search?q=${encodeURIComponent(query)}`, {
                headers: {
                    'X-CSRF-TOKEN': csrfToken,
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            })
            .then(response => response.json())
            .then(users => {
                userResults.innerHTML = '';
                console.log("Found users:", users);

                if (users.length === 0) {
                    userResults.innerHTML = '<p class="p-3 text-sm text-gray-500 dark:text-gray-400">No users found</p>';
                    userResults.classList.remove('hidden');
                    return;
                }

                users.forEach(user => {
                    const div = document.createElement('div');
                    div.className = 'p-2 hover:bg-gray-100 dark:hover:bg-gray-600 cursor-pointer';
                    div.innerHTML = `
                        <p class="font-medium">${user.name}</p>
                        <p class="text-sm text-gray-500 dark:text-gray-400">${user.email}</p>
                    `;
                    div.addEventListener('click', () => selectUser(user));
                    userResults.appendChild(div);
                });

                userResults.classList.remove('hidden');
            })
            .catch(error => {
                console.error('Error fetching users:', error);
            });
        }

        function selectUser(user) {
            userId.value = user.id;
            userSearch.value = user.name;
            selectedUserName.textContent = `${user.name} (${user.email})`;
            selectedUser.classList.remove('hidden');
            userResults.classList.add('hidden');
        }

        function clearUser() {
            userId.value = '';
            userSearch.value = '';
            selectedUser.classList.add('hidden');
        }

        // Initialize selected user if there's a value
        document.addEventListener('DOMContentLoaded', function() {
            if (userId.value) {
                const userIdValue = document.getElementById('user_id').value;
                fetch(`/admin/api/user/${userIdValue}`, {
                    headers: {
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').getAttribute('content'),
                        'Accept': 'application/json'
                    }
                })
                .then(response => response.json())
                .then(user => {
                    if (user) {
                        selectUser(user);
                    }
                })
                .catch(error => {
                    console.error('Error fetching user:', error);
                });
            }
        });
    </script>
</x-app-layout>
