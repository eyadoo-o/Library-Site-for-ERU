<div class="bg-gray-50 dark:bg-gray-800 rounded-lg overflow-hidden shadow-md hover:shadow-xl transition duration-300 flex flex-col md:flex-row">
    <div class="bg-{{ $colorTheme ?? '[#263e62]' }} dark:bg-{{ $colorTheme === '[#bb0017]' ? 'red' : 'blue' }}-800 text-white p-6 flex flex-col items-center justify-center md:w-1/3">
        <span class="text-3xl font-bold">{{ $day ?? '01' }}</span>
        <span class="text-xl">{{ $month ?? 'Jan' }}</span>
        <span class="mt-2">{{ $time ?? '00:00' }}</span>
    </div>
    <div class="p-6 md:w-2/3">
        <h3 class="font-serif text-xl font-semibold mb-2 text-gray-900 dark:text-white">{{ $title ?? 'Event Title' }}</h3>
        <p class="text-gray-600 dark:text-gray-400 mb-4">{{ $description ?? 'No description available.' }}</p>
        <div class="flex items-center text-gray-500 dark:text-gray-400 text-sm">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
            </svg>
            {{ $location ?? 'Location not specified' }}
        </div>
        <a href="{{ $actionUrl ?? '#' }}" class="inline-block mt-4 text-{{ $buttonColor ?? '[#bb0017]' }} dark:text-{{ $buttonColor === '[#263e62]' ? 'blue' : 'red' }}-400 hover:underline font-medium">{{ $actionText ?? 'Register Now' }}</a>
    </div>
</div>
