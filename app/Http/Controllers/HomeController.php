<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Event;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    public function index()
    {
        $featuredBooks = Book::where('quantity', '>', 5)
                            ->orWhere('created_at', '>', now()->subDays(30))
                            ->with('category')
                            ->take(4)
                            ->get();

        $upcomingEvents = Event::where('status', 'published')
                              ->where('event_date', '>', now())
                              ->orderBy('event_date')
                              ->take(2)
                              ->get();

        return view('welcome', compact('featuredBooks', 'upcomingEvents'));
    }
}
