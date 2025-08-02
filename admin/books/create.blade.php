<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Add New Book') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 shadow-md rounded-lg p-6">
                <form action="{{ route('admin.books.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <x-label for="title" value="{{ __('Title') }}" />
                            <x-input id="title" class="block mt-1 w-full" type="text" name="title" :value="old('title')" required autofocus />
                            @error('title')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="authors" value="{{ __('Authors (comma-separated)') }}" />
                            <x-input id="authors" class="block mt-1 w-full" type="text" name="authors" :value="old('authors')" required placeholder="Author 1, Author 2, ..." />
                            @error('authors')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="isbn" value="{{ __('ISBN') }}" />
                            <x-input id="isbn" class="block mt-1 w-full" type="text" name="isbn" :value="old('isbn')" required />
                            @error('isbn')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="edition" value="{{ __('Edition (Optional)') }}" />
                            <x-input id="edition" class="block mt-1 w-full" type="text" name="edition" :value="old('edition')" />
                            @error('edition')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="category_id" value="{{ __('Category') }}" />
                            <select id="category_id" name="category_id" class="border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                <option value="">Select Category</option>
                                @foreach($categories as $id => $name)
                                    <option value="{{ $id }}" {{ old('category_id') == $id ? 'selected' : '' }}>{{ $name }}</option>
                                @endforeach
                            </select>
                            @error('category_id')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <x-label for="format" value="{{ __('Format') }}" />
                            <select id="format" name="format" class="border-gray-300 dark:border-gray-700 dark:bg-gray-700 dark:text-gray-300 focus:border-indigo-500 dark:focus:border-indigo-600 focus:ring-indigo-500 dark:focus:ring-indigo-600 rounded-md shadow-sm block mt-1 w-full">
                                <option value="physical" {{ old('format') == 'physical' ? 'selected' : '' }}>Physical</option>
                                <option value="digital" {{ old('format') == 'digital' ? 'selected' : '' }}>Digital</option>
                            </select>
                            @error('format')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="quantity-field">
                            <x-label for="quantity" value="{{ __('Quantity') }}" />
                            <x-input id="quantity" class="block mt-1 w-full" type="number" min="1" name="quantity" :value="old('quantity', 1)" required />
                            @error('quantity')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div id="book-file-field" class="hidden">
                            <x-label for="book_file" value="{{ __('Digital Book File') }}" />
                            <input id="book_file" type="file" name="book_file"
                                    class="block w-full text-sm text-gray-500 dark:text-gray-400
                                        file:mr-4 file:py-2 file:px-4
                                        file:rounded-md file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-blue-50 file:text-blue-700
                                        hover:file:bg-blue-100
                                        dark:file:bg-gray-700 dark:file:text-gray-200"
                                    accept=".pdf,.epub,.mobi" />
                            <p class="text-sm text-gray-500 dark:text-gray-400 mt-1">Supported formats: PDF, EPUB, MOBI (max 20MB)</p>
                            @error('book_file')
                                <p class="text-sm text-red-600 mt-1">{{ $message }}</p>
                            @enderror
                        </div>

                        <div class="md:col-span-2">
                            <x-label for="image" value="{{ __('Book Cover Image') }}" />
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
                            {{ __('Add Book') }}
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

            function toggleFields() {
                if (formatSelect.value === 'digital') {
                    quantityField.classList.add('hidden');
                    bookFileField.classList.remove('hidden');
                    // When switching to digital, make book_file required
                    bookFileInput.setAttribute('required', 'required');
                    // Remove required from quantity when digital
                    quantityInput.removeAttribute('required');
                } else {
                    quantityField.classList.remove('hidden');
                    bookFileField.classList.add('hidden');
                    // Make quantity required for physical books
                    quantityInput.setAttribute('required', 'required');
                    // Remove required from book_file when physical
                    bookFileInput.removeAttribute('required');
                }
            }

            toggleFields();

            formatSelect.addEventListener('change', toggleFields);
        });
    </script>
</x-app-layout>
