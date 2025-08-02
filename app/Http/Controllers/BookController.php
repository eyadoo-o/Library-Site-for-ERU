<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Storage;

class BookController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::pluck('name', 'id');
        $query = Book::query();

        if ($request->filled('category_id')) {
            $query->where('category_id', $request->category_id);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        $books = $query->with('category')->latest()->paginate(10)->withQueryString();

        return view('admin.books.index', compact('books', 'categories'));
    }

    public function publicIndex(Request $request)
    {
        $categories = Category::pluck('name', 'id');
        $query = Book::query();

        if ($request->filled('category')) {
            $query->where('category_id', $request->category);
        }

        if ($request->filled('availability')) {
            if ($request->availability === 'available') {
                $query->where(function($q) {
                    $q->where('quantity', '>', 0)
                      ->orWhere('format', 'digital'); // Digital books are always available
                });
            } elseif ($request->availability === 'reserved') {
                $query->where('quantity', 0)->where('format', 'physical');
            }
        }

        if ($request->filled('format')) {
            $query->where('format', $request->format);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('author', 'like', '%' . $request->search . '%')
                  ->orWhere('isbn', 'like', '%' . $request->search . '%');
            });
        }

        $books = $query->with('category')
                       ->latest()
                       ->paginate(12)
                       ->withQueryString();

        // Get featured books for public display (newest books and those with highest quantity)
        $featuredBooks = Book::where('quantity', '>', 5)
                    ->orWhere('created_at', '>', now()->subDays(30))
                    ->orderBy('created_at', 'desc')
                    ->take(4)
                    ->get();

        return view('books', compact('books', 'categories', 'featuredBooks'));
    }

    public function create()
    {
        $categories = Category::pluck('name', 'id');
        return view('admin.books.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $validationRules = [
            'title' => 'required|string|max:255',
            'authors' => 'required|string',
            'isbn' => 'required|string|unique:books',
            'category_id' => 'required|exists:categories,id',
            'format' => 'required|in:physical,digital',
            'image' => 'nullable|image|max:2048',
            'edition' => 'nullable|string|max:255',
        ];

        // Quantity is required for physical books only
        if ($request->format === 'physical') {
            $validationRules['quantity'] = 'required|integer|min:1';
        } else {
            $validationRules['quantity'] = 'nullable|integer|min:0';
            $validationRules['book_file'] = 'required|file|mimes:pdf,epub,mobi|max:20480'; // 20MB max for digital books
        }

        $data = $request->validate($validationRules);

        // Process authors from comma-separated string to array
        $data['authors'] = array_map('trim', explode(',', $data['authors']));

        // Add the current user as the one who added the book
        $data['added_by'] = auth()->id();

        // For digital books, set quantity to 0 (unlimited)
        if ($request->format === 'digital') {
            $data['quantity'] = 0;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('books', 'public');
        }

        // Handle digital book file upload
        if ($request->hasFile('book_file')) {
            $file = $request->file('book_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('book-files', $fileName, 'public');
            $data['file_path'] = $filePath;
        }

        Book::create($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book created successfully.');
    }

    public function edit(Book $book)
    {
        $categories = Category::pluck('name', 'id');
        return view('admin.books.edit', compact('book', 'categories'));
    }

    public function update(Request $request, Book $book)
    {
        $validationRules = [
            'title' => 'required|string|max:255',
            'authors' => 'required|string',
            'isbn' => 'required|string|unique:books,isbn,' . $book->id,
            'category_id' => 'required|exists:categories,id',
            'format' => 'required|in:physical,digital',
            'image' => 'nullable|image|max:2048',
            'edition' => 'nullable|string|max:255',
        ];

        // Quantity is required for physical books only
        if ($request->format === 'physical') {
            $validationRules['quantity'] = 'required|integer|min:0';
        } else {
            $validationRules['quantity'] = 'nullable|integer|min:0';

            // Make book_file required only for digital books that don't already have a file
            if (!$book->file_path) {
                $validationRules['book_file'] = 'required|file|mimes:pdf,epub,mobi|max:20480';
            } else {
                $validationRules['book_file'] = 'nullable|file|mimes:pdf,epub,mobi|max:20480';
            }
        }

        $data = $request->validate($validationRules);

        // Process authors from comma-separated string to array
        $data['authors'] = array_map('trim', explode(',', $data['authors']));

        // For digital books, set quantity to 0 (unlimited)
        if ($request->format === 'digital') {
            $data['quantity'] = 0;
        }

        // Handle image upload
        if ($request->hasFile('image')) {
            // Delete old image if exists
            if ($book->image) {
                Storage::disk('public')->delete($book->image);
            }
            $data['image'] = $request->file('image')->store('books', 'public');
        }

        // Handle digital book file upload
        if ($request->hasFile('book_file')) {
            // Delete old file if exists
            if ($book->file_path) {
                Storage::disk('public')->delete($book->file_path);
            }

            $file = $request->file('book_file');
            $fileName = time() . '_' . $file->getClientOriginalName();
            $filePath = $file->storeAs('book-files', $fileName, 'public');
            $data['file_path'] = $filePath;
        }

        $book->update($data);

        return redirect()->route('admin.books.index')
            ->with('success', 'Book updated successfully.');
    }

    public function destroy(Book $book)
    {
        $book->delete();

        return redirect()->route('admin.books.index')
            ->with('success', 'Book deleted successfully.');
    }

    public function show(Book $book)
    {
        return view('books.show', compact('book'));
    }
}
