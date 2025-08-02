<x-app-layout>
    <x-slot name="header">
        <div class="flex justify-between items-center">
            <h2 class="text-2xl font-bold text-gray-800 dark:text-gray-200">
                {{ __('Skills & Exchanges') }}
            </h2>
            <div class="space-x-2">
                @auth
                <a href="{{ route('admin.skills.create') }}" class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-md">
                    {{ __('Offer New Skill') }}
                </a>
                <a href="{{ route('admin.skills.exchanges.create') }}" class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-md">
                    {{ __('Request Exchange') }}
                </a>
                @endauth
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            {{-- Tab Navigation --}}
            <div class="mb-4 border-b border-gray-200 dark:border-gray-700">
                <ul class="flex flex-wrap -mb-px text-sm font-medium text-center" role="tablist">
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 rounded-t-lg" id="skills-tab" data-tabs-target="#skills" type="button" role="tab" aria-controls="skills" aria-selected="true">Available Skills</button>
                    </li>
                    <li class="mr-2" role="presentation">
                        <button class="inline-block p-4 border-b-2 border-transparent rounded-t-lg hover:text-gray-600 hover:border-gray-300" id="exchanges-tab" data-tabs-target="#exchanges" type="button" role="tab" aria-controls="exchanges" aria-selected="false">All Exchanges</button>
                    </li>
                </ul>
            </div>

            {{-- Tab Content --}}
            <div id="tabContent">
                {{-- Skills Tab --}}
                <div class="hidden p-4 rounded-lg bg-white dark:bg-gray-800" id="skills" role="tabpanel" aria-labelledby="skills-tab">
                        @if(session('success'))
                            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                                {{ session('success') }}
                            </div>
                        @endif

                        {{-- Pending Skills for Confirmation --}}
                        @if($pendingSkills->count() > 0)
                        <div class="bg-yellow-50 border-l-4 border-yellow-400 p-4 mb-6">
                            <div class="flex">
                                <div class="flex-shrink-0">
                                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                                <div class="ml-3">
                                    <p class="text-sm text-yellow-700">
                                        There are {{ $pendingSkills->count() }} skills pending confirmation.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="bg-white shadow-md rounded-lg overflow-hidden mb-8">
                            <div class="px-4 py-5 sm:px-6 bg-gray-50 border-b border-gray-200">
                                <h3 class="text-lg leading-6 font-medium text-gray-900">Pending Skills</h3>
                                <p class="mt-1 max-w-2xl text-sm text-gray-500">These skills require your approval before they are visible to users.</p>
                            </div>
                            <div class="px-4 py-5 sm:p-6">
                                <div class="overflow-x-auto">
                                    <table class="min-w-full divide-y divide-gray-200">
                                        <thead class="bg-gray-50">
                                            <tr>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Skill</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Mentor</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Submitted</th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            @foreach($pendingSkills as $pendingSkill)
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900">{{ $pendingSkill->name }}</div>
                                                    <div class="text-sm text-gray-500">{{ Str::limit($pendingSkill->description, 50) }}</div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                        {{ $pendingSkill->category }}
                                                    </span>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $pendingSkill->mentor->name ?? 'Unknown' }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    {{ $pendingSkill->created_at->format('M d, Y') }}
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                    <div class="flex space-x-2">
                                                        <form action="{{ route('admin.skills.confirm', $pendingSkill) }}" method="POST">
                                                            @csrf
                                                            <button type="submit" class="text-green-600 hover:text-green-900">Confirm</button>
                                                        </form>
                                                        <form action="{{ route('admin.skills.reject', $pendingSkill) }}" method="POST" onsubmit="return confirm('Are you sure you want to reject this skill? This action cannot be undone.')">
                                                            @csrf
                                                            <button type="submit" class="text-red-600 hover:text-red-900">Reject</button>
                                                        </form>
                                                    </div>
                                                </td>
                                            </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                        @endif

                        {{-- Filter Form --}}
                        <form method="GET" action="{{ route('admin.skills.index') }}" class="mb-6">
                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                                <div>
                                    <label for="category" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Category</label>
                                    <select name="category" id="category" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">All Categories</option>
                                        @foreach($categories as $category)
                                            <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>{{ $category }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div>
                                    <label for="mentor" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Mentor</label>
                                    <select name="mentor" id="mentor" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-200 focus:ring-blue-500 focus:border-blue-500">
                                        <option value="">All Mentors</option>
                                        @foreach($mentors as $mentor)
                                            <option value="{{ $mentor->id }}" {{ request('mentor') == $mentor->id ? 'selected' : '' }}>{{ $mentor->name }}</option>
                                        @endforeach
                                    </select>
                                </div>

                                <div class="flex items-end">
                                    <button type="submit" class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-md mr-2">Filter</button>
                                    <a href="{{ route('admin.skills.index') }}" class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white rounded-md">Reset</a>
                                </div>
                            </div>
                        </form>

                        {{-- Skills List --}}
                        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                            @forelse($skills as $skill)
                                <div class="bg-gray-50 dark:bg-gray-700 rounded-lg shadow-sm overflow-hidden flex flex-col justify-between">
                                    <div class="p-6">
                                        <div class="flex justify-between items-start mb-2">
                                            <h3 class="text-xl font-semibold text-gray-900 dark:text-white">{{ $skill->name }}</h3>
                                            @if($skill->rating)
                                                <span class="text-yellow-500 flex items-center">
                                                    <svg class="w-4 h-4 fill-current mr-1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                                    {{ number_format($skill->rating, 1) }}
                                                </span>
                                            @endif
                                        </div>
                                        <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Category: {{ $skill->category }}</p>
                                        <p class="text-gray-600 dark:text-gray-300 mb-4">{{ Str::limit($skill->description, 100) }}</p>
                                        <div class="text-sm text-gray-500 dark:text-gray-400 space-y-1">
                                            @if($skill->mentor)
                                                <div>Mentor: {{ $skill->mentor->name }}</div>
                                            @else
                                                <div>Mentor: Not Available</div>
                                            @endif
                                            <div>Offered {{ $skill->created_at->diffForHumans() }}</div>
                                        </div>
                                    </div>
                                    <div class="bg-gray-100 dark:bg-gray-600 px-6 py-3 flex justify-between items-center">
                                        <div class="flex space-x-3">
                                            <a href="{{ route('admin.skills.edit', $skill) }}" class="text-sm text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">Edit</a>
                                            <form action="{{ route('admin.skills.destroy', $skill) }}" method="POST" class="inline">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" class="text-sm text-red-600 hover:text-red-800 dark:text-red-400 dark:hover:text-red-600" onclick="return confirm('Are you sure you want to delete this skill?')">Delete</button>
                                            </form>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="col-span-full text-center py-8 text-gray-500 dark:text-gray-400">
                                    No skills found matching your criteria.
                                </div>
                            @endforelse
                        </div>

                        {{-- Pagination --}}
                        <div class="mt-6">
                            {{ $skills->appends(request()->query())->links() }}
                        </div>

                </div>

                {{-- Exchanges Tab --}}
                <div class="hidden p-4" id="exchanges" role="tabpanel" aria-labelledby="exchanges-tab">
                    @include('admin.skills.exchanges.index', [
                        'exchanges' => $SkillExchanges,
                        'users' => $mentors,
                        'statuses' => $statuses,
                        'isEmbedded' => true
                    ])
                </div>
            </div>
        </div>
    </div>

    {{-- Tab Functionality Script --}}
    <script>
        // Get all tab buttons and content
        const tabButtons = document.querySelectorAll('[role="tab"]');
        const tabPanels = document.querySelectorAll('[role="tabpanel"]');

        tabButtons.forEach(button => {
            button.addEventListener('click', () => {
                // Hide all panels and remove active states
                tabPanels.forEach(panel => panel.classList.add('hidden'));
                tabButtons.forEach(btn => {
                    btn.classList.remove('text-blue-600', 'border-blue-600');
                    btn.classList.add('border-transparent');
                });

                // Show selected panel and set active state
                const panelId = button.getAttribute('data-tabs-target').substring(1);
                document.getElementById(panelId).classList.remove('hidden');
                button.classList.remove('border-transparent');
                button.classList.add('text-blue-600', 'border-blue-600');
            });
        });

        // If there's a hash in the URL, switch to that tab
        if (window.location.hash) {
            const tab = document.querySelector(`[data-tabs-target="${window.location.hash}"]`);
            if (tab) tab.click();
        } else if (new URLSearchParams(window.location.search).has('tab') && new URLSearchParams(window.location.search).get('tab') === 'exchanges') {
            // Default to the exchanges tab if 'tab=exchanges' is in the query string
            document.getElementById('exchanges').classList.remove('hidden');
            document.getElementById('exchanges-tab').classList.add('text-blue-600', 'border-blue-600');
        } else {
            // Default to the skills tab
            document.getElementById('skills').classList.remove('hidden');
            document.getElementById('skills-tab').classList.add('text-blue-600', 'border-blue-600');
        }
    </script>
</x-app-layout>
