<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Reservation;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;

class ReservationController extends Controller
{
    public function index(Request $request)
    {
        $query = Reservation::with(['user', 'book'])->orderBy('created_at', 'desc');

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('book', function ($q) use ($search) {
                $q->where('title', 'like', '%' . $search . '%')
                  ->orWhere('author', 'like', '%' . $search . '%');
            })->orWhereHas('user', function ($q) use ($search) {
                $q->where('name', 'like', '%' . $search . '%')
                  ->orWhere('email', 'like', '%' . $search . '%');
            });
        }

        $reservations = $query->paginate(10)->withQueryString();

        return view('admin.reservations.index', [
            'reservations' => $reservations,
            'statuses' => Reservation::statusOptions()
        ]);
    }

    public function create()
    {
        $books = Book::orderBy('title')->get()->map(function ($book) {
            return ['id' => $book->id, 'title' => $book->title . ' (' . $book->author . ')'];
        })->pluck('title', 'id');

        return view('admin.reservations.create', [
            'books' => $books,
            'statuses' => Reservation::statusOptions()
        ]);
    }

    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'user_id' => 'required|exists:users,id',
            'book_id' => 'required|exists:books,id',
            'status' => ['required', Rule::in(array_keys(Reservation::statusOptions()))],
        ]);

        $book = Book::findOrFail($validatedData['book_id']);

        if ($validatedData['status'] === 'approved') {
            if ($book->quantity <= 0) {
                return redirect()->back()
                    ->withErrors(['status' => 'Cannot create approved reservation: book is out of stock.'])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($validatedData, $book) {
            Reservation::create([
                'user_id' => $validatedData['user_id'],
                'book_id' => $validatedData['book_id'],
                'reserved_at' => now(),
                'status' => $validatedData['status'],
            ]);

            if ($validatedData['status'] === 'approved') {
                $book->decrement('quantity');
            }
        });

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation created successfully.');
    }

    public function edit(Reservation $reservation)
    {
        $users = User::where('type', '!=', 'admin')->orderBy('name')->pluck('name', 'id');
        $books = Book::orderBy('title')->get()->map(function ($book) {
            return ['id' => $book->id, 'title' => $book->title . ' (' . $book->author . ')'];
        })->pluck('title', 'id');

        return view('admin.reservations.edit', [
            'reservation' => $reservation,
            'users' => $users,
            'books' => $books,
            'statuses' => Reservation::statusOptions()
        ]);
    }

    public function update(Request $request, Reservation $reservation)
    {
        $validatedData = $request->validate([
            'status' => ['required', Rule::in(array_keys(Reservation::statusOptions()))],
        ]);

        $newStatus = $validatedData['status'];
        $oldStatus = $reservation->status;

        if ($newStatus === $oldStatus) {
            return redirect()->route('admin.reservations.index')->with('info', 'Reservation status not changed.');
        }

        if ($newStatus === 'approved' && $oldStatus !== 'approved') {
            if ($reservation->book->quantity <= 0) {
                return redirect()->back()
                    ->withErrors(['status' => 'Cannot approve reservation: book is out of stock.'])
                    ->withInput();
            }
        }

        DB::transaction(function () use ($reservation, $newStatus, $oldStatus) {
            $reservation->update(['status' => $newStatus]);

            $book = $reservation->book;
            if ($newStatus === 'approved' && $oldStatus !== 'approved') {
                $book->decrement('quantity');
            } elseif ($oldStatus === 'approved' && $newStatus !== 'approved') {
                $book->increment('quantity');
            }
        });

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation updated successfully.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();

        return redirect()->route('admin.reservations.index')
            ->with('success', 'Reservation deleted successfully.');
    }

    public function approve(Reservation $reservation)
    {
        if ($reservation->status !== 'pending') {
            return redirect()->route('admin.reservations.index')->with('warning', 'Reservation is not pending and cannot be approved this way.');
        }

        if ($reservation->book->quantity <= 0) {
            return redirect()->route('admin.reservations.index')->with('error', 'Cannot approve reservation: book is out of stock.');
        }

        DB::transaction(function () use ($reservation) {
            $reservation->update(['status' => 'approved']);
            $reservation->book->decrement('quantity');
        });

        return redirect()->route('admin.reservations.index')->with('success', 'Reservation approved successfully.');
    }

    public function storeFromBook(Request $request, Book $book)
    {
        // Check if user already has a pending or approved reservation for this book
        $existingReservation = Reservation::where('user_id', auth()->id())
                                          ->where('book_id', $book->id)
                                          ->whereIn('status', ['pending', 'approved'])
                                          ->first();

        if ($existingReservation) {
            return redirect()->back()->with('warning', 'You already have an active reservation for this book.');
        }

        Reservation::create([
            'user_id' => auth()->id(),
            'book_id' => $book->id,
            'reserved_at' => now(),
            'status' => 'pending', // User-initiated reservations start as 'pending'
        ]);

        return redirect()->back()->with('success', 'Book reserved successfully! Your reservation is pending approval.');
    }

    public function myReservations(Request $request)
    {
        $user = auth()->user();
        $reservations = Reservation::with('book')
                            ->where('user_id', $user->id)
                            ->orderBy('created_at', 'desc')
                            ->paginate(10);

        return view('reservations.my', [
            'reservations' => $reservations,
            'statusOptions' => Reservation::statusOptions()
        ]);
    }
}
