<?php

namespace App\Http\Controllers;

use App\Models\ReadingHistory;
use Illuminate\Http\Request;

class ReadingHistoryController extends Controller
{
    public function index(Request $request)
    {
        $query = ReadingHistory::with(['user', 'book', 'document'])
            ->latest('accessed_at');

        if ($request->filled('type')) {
            if ($request->type === 'books') {
                $query->whereNotNull('book_id');
            } elseif ($request->type === 'documents') {
                $query->whereNotNull('document_id');
            }
        }

        if ($request->filled('search')) {
            $query->where(function($q) use ($request) {
                $q->whereHas('book', function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%');
                })->orWhereHas('document', function($q) use ($request) {
                    $q->where('title', 'like', '%' . $request->search . '%');
                });
            });
        }

        $history = $query->paginate(15)->withQueryString();

        return view('admin.reading-history.index', [
            'history' => $history,
            'types' => [
                'all' => 'All Items',
                'books' => 'Books Only',
                'documents' => 'Documents Only'
            ]
        ]);
    }
}
