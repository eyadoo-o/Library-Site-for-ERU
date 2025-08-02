<footer class="bg-gray-900 text-white py-12">
    <div class="mx-auto max-w-7xl px-4 sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 md:grid-cols-4 gap-12">
            <div>
                <div class="flex items-center" style="margin-left: 0.125rem;">
                    <x-application-logo class="h-8 w-8 text-[#263e62] mr-2" />
                    <span class="text-xl font-serif text-[#263e62]">{{ __('messages.eru_in_egypt_footer') }}</span>
                </div>
                <p class="mt-4 text-gray-400 ml-[2.5rem]">{{ __('messages.footer_tagline') }}</p>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4 text-[#263e62]">{{ __('messages.library_hours_title') }}</h3>
                <ul class="space-y-2 text-gray-400">
                    <li>{{ __('messages.library_hours_sun_thu') }}</li>
                    <li>{{ __('messages.library_hours_online') }}</li>
                    <li>{{ __('messages.library_hours_fri_sat') }}</li>
                    <li>{{ __('messages.library_hours_finals') }}</li>
                </ul>
            </div>
            <div>
                <h3 class="text-lg font-semibold mb-4 text-[#263e62]">{{ __('messages.contact_information_title') }}</h3>
                <ul class="space-y-2 text-gray-400">
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-[#263e62]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        {{ __('messages.contact_address') }}
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-[#263e62]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                        </svg>
                        {{ __('messages.contact_phone') }}
                    </li>
                    <li class="flex items-start">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2 mt-0.5 text-[#263e62]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                        </svg>
                        {{ __('messages.contact_email') }}
                    </li>
                </ul>
            </div>
        </div>
        <div class="border-t border-gray-800 mt-12 pt-8 text-center text-gray-400">
            <p>&copy; {{ date('Y') }} {{ __('messages.eru_library_footer_copy') }}. {{ __('messages.all_rights_reserved') }}</p>
        </div>
    </div>
</footer>
