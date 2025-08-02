<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Edit Book') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('admin.books.update', $book) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="title" value="{{ __('Title') }}" />
                            <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title', $book->title)" required autofocus />
                            @error('title')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="authors" value="{{ __('Authors (comma-separated)') }}" />
                            <x-input id="authors" class="block mt-1 w-full" type="text" name="authors" :value="old('authors', is_array($book->authors) ? implode(', ', $book->authors) : $book->authors)" required placeholder="Author 1, Author 2, ..." />
                            @error('authors')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="isbn" value="{{ __('ISBN') }}" />
                            <x-input id="isbn" class="block mt-1 w-full" type="text" name="isbn" :value="old('isbn', $book->isbn)" required />
                            @error('isbn')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="edition" value="{{ __('Edition (Optional)') }}" />
                            <x-input id="edition" class="block mt-1 w-full" type="text" name="edition" :value="old('edition', $book->edition)" />
                            @error('edition')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="category_id" value="{{ __('Category') }}" />
                            <select id="category_id" name="category_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                <option value="">Select Category</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id', $book->category_id) == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="format" value="{{ __('Format') }}" />
                            <select id="format" name="format" class="border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                <option value="physical" {{ old('format', $book->format) == 'physical' ? 'selected' : '' }}>Physical</option>
                                <option value="digital" {{ old('format', $book->format) == 'digital' ? 'selected' : '' }}>Digital</option>
                            </select>
                            @error('format')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="quantity-field" class="{{ $book->format === 'digital' ? 'hidden' : '' }}">
                            <x-label for="quantity" value="{{ __('Quantity') }}" />
                            <x-input id="quantity" class="block mt-1 w-full" type="number" min="0" name="quantity" :value="old('quantity', $book->quantity)" required />
                            @error('quantity')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="book-file-field" class="{{ $book->format === 'digital' ? '' : 'hidden' }}">
                            <x-label for="book_file" value="{{ __('Digital Book File') }}" />

                            @if($book->file_path)
                                <div class="mt-2 mb-3">
                                    <div class="flex items-center space-x-2 p-2 bg-blue-50 dark:bg-gray-700 rounded">
                                        <svg class="h-6 w-6 text-blue-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                        </svg>
                                        <div>
                                            <p class="text-sm font-medium">Current file: {{ basename($book->file_path) }}</p>
                                            <p class="text-xs text-gray-500 dark:text-gray-400">Upload a new file to replace it</p>
                                        </div>
                                    </div>
                                </div>
                            @endif

                            <input id="book_file" type="file" name="book_file"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100
                                        dark:file:bg-gray-700 dark:file:text-gray-200"
                                    accept=".pdf,.epub,.mobi" {{ (!$book->file_path && $book->format === 'digital') ? 'required' : '' }} />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Supported formats: PDF, EPUB, MOBI (max 20MB)</p>
                            @error('book_file')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-label for="image" value="{{ __('Book Cover Image') }}" />
                            @if($book->image)
                                <div class="mt-2 mb-3">
                                    <img src="{{ Storage::url($book->image) }}" alt="{{ $book->title }}" class="h-32 w-auto object-cover rounded">
                                    <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Current cover image. Upload a new image to replace it.</p>
                                </div>
                            @endif
                            <input id="image" type="file" name="image"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100
                                        dark:file:bg-gray-700 dark:file:text-gray-200"
                                    accept="image/*" />
                            @error('image')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>
                    </div>

                    <div class="flex items-center justify-end mt-6">
                        <a href="{{ route('admin.books.index') }}" class="text-gray-600 dark:text-gray-400 hover:underline mr-4">
                            {{ __('Cancel') }}
                        </a>
                        <x-button>
                            {{ __('Update Book') }}
                        </x-button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const formatSelect = document.getElementById('format');
            const quantityField = document.getElementById('quantity-field');
            const bookFileField = document.getElementById('book-file-field');
            const quantityInput = document.getElementById('quantity');
            const bookFileInput = document.getElementById('book_file');
            const hasExistingFile = {{ $book->file_path ? 'true' : 'false' }};

            function toggleFields() {
                if (formatSelect.value === 'digital') {
                    quantityField.classList.add('hidden');
                    bookFileField.classList.remove('hidden');
                    // Remove required from quantity when digital
                    quantityInput.removeAttribute('required');

                    // Only make file required if there's no existing file
                    if (!hasExistingFile) {
                        bookFileInput.setAttribute('required', 'required');
                    }
                } else {
                    quantityField.classList.remove('hidden');
                    bookFileField.classList.add('hidden');
                    // Make quantity required for physical books
                    quantityInput.setAttribute('required', 'required');
                    // Remove required from book_file when physical
                    bookFileInput.removeAttribute('required');
                }
            }

            // Initial toggle based on the current value
            toggleFields();

            // Toggle on change
            formatSelect.addEventListener('change', toggleFields);
        });
    </script>
</x-app-layout>
