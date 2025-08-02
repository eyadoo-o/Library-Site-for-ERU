<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Skill Exchange Details') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                @if(session('success'))
                    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4">
                        {{ session('success') }}
                    </div>
                @endif

                @if(session('error'))
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4">
                        {{ session('error') }}
                    </div>
                @endif

                <div class="mb-6">
                    <a href="{{ route('admin.skills.index') }}#exchanges" class="text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-600">
                        &larr; Back to Exchanges
                    </a>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                        <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">Exchange Information</h3>

                        <div class="space-y-4">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Skill</h4>
                                <p class="text-gray-900 dark:text-white">{{ $exchange->skill->name }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Category</h4>
                                <p class="text-gray-900 dark:text-white">{{ $exchange->skill->category }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Status</h4>
                                <p>
                                    @if($exchange->status === 'pending')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-yellow-100 text-yellow-800 dark:bg-yellow-800 dark:text-yellow-100">Pending</span>
                                    @elseif($exchange->status === 'accepted')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-green-100 text-green-800 dark:bg-green-800 dark:text-green-100">Accepted</span>
                                    @elseif($exchange->status === 'completed')
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800 dark:bg-blue-800 dark:text-blue-100">Completed</span>
                                    @else
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-gray-100 text-gray-800 dark:bg-gray-700 dark:text-gray-300">Cancelled</span>
                                    @endif
                                </p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Created</h4>
                                <p class="text-gray-900 dark:text-white">{{ $exchange->created_at->format('F j, Y \a\t g:i A') }}</p>
                            </div>

                            @if($exchange->status === 'completed' && $exchange->rating)
                                <div>
                                    <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Rating</h4>
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            @if($i <= $exchange->rating)
                                                <svg class="w-5 h-5 text-yellow-400 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                            @else
                                                <svg class="w-5 h-5 text-gray-300 fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                            @endif
                                        @endfor
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>

                    <div class="bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                        <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">Participants</h3>

                        <div class="space-y-6">
                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Mentor</h4>
                                <p class="text-gray-900 dark:text-white">{{ $exchange->mentor->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $exchange->mentor->email }}</p>
                            </div>

                            <div>
                                <h4 class="text-sm font-medium text-gray-500 dark:text-gray-400">Student</h4>
                                <p class="text-gray-900 dark:text-white">{{ $exchange->student->name }}</p>
                                <p class="text-sm text-gray-500 dark:text-gray-400">{{ $exchange->student->email }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-8 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                    <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">Description</h3>
                    <p class="text-gray-900 dark:text-white whitespace-pre-line">{{ $exchange->description }}</p>
                </div>

                @if($exchange->feedback)
                    <div class="mt-6 bg-gray-50 dark:bg-gray-700 p-6 rounded-lg shadow-sm">
                        <h3 class="font-bold text-lg mb-4 text-gray-900 dark:text-white">Feedback</h3>
                        <p class="text-gray-900 dark:text-white whitespace-pre-line">{{ $exchange->feedback }}</p>
                    </div>
                @endif

                <div class="mt-8 flex flex-col md:flex-row justify-between">
                    <div class="space-x-2 mb-4 md:mb-0">
                        @if($exchange->status === 'pending' && $exchange->mentor_id === Auth::id())
                            <form action="{{ route('admin.skills.exchanges.accept', $exchange) }}" method="POST" class="inline">
                                @csrf
                                <x-button type="submit" class="bg-green-600 hover:bg-green-700">Accept</x-button>
                            </form>
                        @endif

                        @if($exchange->status === 'accepted' && $exchange->mentor_id === Auth::id())
                            <form action="{{ route('admin.skills.exchanges.complete', $exchange) }}" method="POST" class="inline">
                                @csrf
                                <x-button type="submit" class="bg-blue-600 hover:bg-blue-700">Complete</x-button>
                            </form>
                        @endif

                        @if($exchange->status === 'completed' && $exchange->student_id === Auth::id() && !$exchange->rating)
                            <button type="button" class="inline-flex items-center px-4 py-2 bg-yellow-600 border border-transparent rounded-md font-semibold text-xs text-white uppercase tracking-widest hover:bg-yellow-700 focus:outline-none focus:border-yellow-700 focus:ring focus:ring-yellow-200 active:bg-yellow-700 disabled:opacity-25 transition" onclick="document.getElementById('ratingModal').classList.remove('hidden')">
                                Rate This Exchange
                            </button>
                        @endif
                    </div>

                    <div class="space-x-2">
                        <form action="{{ route('admin.skills.exchanges.cancel', $exchange) }}" method="POST" class="inline">
                            @csrf
                            @method('PATCH')
                            <x-button type="submit" class="bg-yellow-600 hover:bg-yellow-700">Cancel</x-button>
                        </form>

                        <form action="{{ route('admin.skills.exchanges.destroy', $exchange) }}" method="POST" class="inline">
                            @csrf
                            @method('DELETE')
                            <x-button type="submit" class="bg-red-600 hover:bg-red-700" onclick="return confirm('Are you sure you want to delete this exchange request?')">Delete</x-button>
                        </form>
                    </div>
                </div>

                <!-- Rating Modal -->
                @if($exchange->status === 'completed' && $exchange->student_id === Auth::id() && !$exchange->rating)
                    <div id="ratingModal" class="fixed inset-0 bg-gray-900 bg-opacity-50 flex items-center justify-center z-50 hidden">
                        <div class="bg-white dark:bg-gray-800 rounded-lg p-6 max-w-md w-full">
                            <h3 class="text-lg font-bold mb-4 text-gray-900 dark:text-white">Rate This Exchange</h3>

                            <form action="{{ route('admin.skills.exchanges.rate', $exchange) }}" method="POST">
                                @csrf

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Rating</label>
                                    <div class="flex items-center space-x-2">
                                        @for($i = 1; $i <= 5; $i++)
                                            <label class="cursor-pointer">
                                                <input type="radio" name="rating" value="{{ $i }}" class="hidden" required>
                                                <svg class="w-8 h-8 star-rating" data-rating="{{ $i }}" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20"><path d="M10 15l-5.878 3.09 1.123-6.545L.489 6.91l6.572-.955L10 0l2.939 5.955 6.572.955-4.756 4.635 1.123 6.545z"/></svg>
                                            </label>
                                        @endfor
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Feedback (optional)</label>
                                    <textarea name="feedback" rows="3" class="block w-full border-gray-300 rounded-md shadow-sm dark:bg-gray-700 dark:text-gray-300 focus:ring-blue-500 focus:border-blue-500"></textarea>
                                </div>

                                <div class="flex justify-end space-x-2">
                                    <button type="button" class="px-4 py-2 bg-gray-500 text-white rounded-md hover:bg-gray-600" onclick="document.getElementById('ratingModal').classList.add('hidden')">
                                        Cancel
                                    </button>
                                    <button type="submit" class="px-4 py-2 bg-blue-600 text-white rounded-md hover:bg-blue-700">
                                        Submit Rating
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>

                    <script>
                        document.querySelectorAll('.star-rating').forEach(star => {
                            star.addEventListener('click', () => {
                                const rating = star.dataset.rating;
                                document.querySelectorAll('.star-rating').forEach(s => {
                                    if (s.dataset.rating <= rating) {
                                        s.classList.add('text-yellow-400');
                                    } else {
                                        s.classList.remove('text-yellow-400');
                                    }
                                });
                            });
                        });
                    </script>
                @endif
            </div>
        </div>
    </div>
</x-app-layout>
