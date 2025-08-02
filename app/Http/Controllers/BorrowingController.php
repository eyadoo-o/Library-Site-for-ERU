<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Borrowing;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class BorrowingController extends Controller
{
    public function index(Request $request)
    {
        $query = Borrowing::with(['user', 'book'])
            ->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            if ($request->status === 'active') {
                $query->whereNull('returned_at');
            } elseif ($request->status === 'returned') {
                $query->whereNotNull('returned_at');
            } elseif ($request->status === 'overdue') {
                $query->whereNull('returned_at')->where('due_date', '<', now());
            }
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%')
                  ->orWhere('isbn', 'like', '%' . $search . '%');
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $borrowings = $query->paginate(10)->withQueryString();

        return view('admin.borrowings.index', [
            'borrowings' => $borrowings,
            'statuses' => [
                'all' => 'All Borrowings',
                'active' => 'Active Borrowings',
                'returned' => 'Returned Books',
                'overdue' => 'Overdue Books'
            ]
        ]);
    }

    public function create()
    {
        $books = Book::where('quantity', '>', 0)
            ->where('format', 'physical')
            ->orderBy('title')
            ->get()
            ->map(function ($book) {
                return [
                    'id' => $book->id,
                    'title' => $book->title . ' (' . $book->author . ')',
                    'available' => $book->quantity
                ];
            })
            ->pluck('title', 'id');

        return view('admin.borrowings.create', compact('books'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'borrowed_at' => 'required|date',
            'due_date' => 'required|date|after:borrowed_at',
        ]);

        // Check if book is available
        $book = Book::findOrFail($request->book_id);

        if ($book->quantity <= 0) {
            return redirect()->back()->withErrors(['book_id' => 'This book is not available for borrowing.']);
        }

        DB::transaction(function () use ($request, $book) {
            // Create new borrowing record
            Borrowing::create([
                'user_id' => $request->user_id,
                'book_id' => $request->book_id,
                'borrowed_at' => $request->borrowed_at,
                'due_date' => $request->due_date,
            ]);

            $book->decrement('quantity');
        });

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Book borrowed successfully.');
    }

    public function show(Borrowing $borrowing)
    {
        return view('admin.borrowings.show', compact('borrowing'));
    }

    public function edit(Borrowing $borrowing)
    {
        $books = Book::orderBy('title')->get()->map(function ($book) {
            return ['id' => $book->id, 'title' => $book->title . ' (' . $book->author . ')'];
        })->pluck('title', 'id');

        return view('admin.borrowings.edit', compact('borrowing', 'books'));
    }

    public function update(Request $request, Borrowing $borrowing)
    {
        $request->validate([
            'borrowed_at' => 'required|date',
            'due_date' => 'required|date|after:borrowed_at',
        ]);

        $borrowing->update([
            'borrowed_at' => $request->borrowed_at,
            'due_date' => $request->due_date,
        ]);

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Borrowing record updated successfully.');
    }

    public function return(Request $request, Borrowing $borrowing)
    {
        if ($borrowing->returned_at) {
            return redirect()->route('admin.borrowings.index')
                ->with('error', 'This book has already been returned.');
        }

        DB::transaction(function () use ($borrowing) {

            $borrowing->update([
                'returned_at' => now(),
            ]);

            $borrowing->book->increment('quantity');
        });

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Book marked as returned successfully.');
    }

    public function destroy(Borrowing $borrowing)
    {
        // If the book wasn't returned, increase the quantity
        if (!$borrowing->returned_at) {
            DB::transaction(function () use ($borrowing) {
                $borrowing->delete();
                $borrowing->book->increment('quantity');
            });
        } else {
            $borrowing->delete();
        }

        return redirect()->route('admin.borrowings.index')
            ->with('success', 'Borrowing record deleted successfully.');
    }
}
