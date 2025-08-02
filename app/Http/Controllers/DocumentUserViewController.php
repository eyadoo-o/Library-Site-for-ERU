<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentUserView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DocumentUserViewController extends Controller
{
    public function index(Request $request)
    {
        $query = DocumentUserView::with(['document', 'user']);

        if ($request->filled('document_id')) {
            $query->where('document_id', $request->document_id);
        }

        if ($request->filled('user_id')) {
            $query->where('user_id', $request->user_id);
        }

        $views = $query->latest('viewed_at')->paginate(20)->withQueryString();

        $documents = Document::orderBy('title')->pluck('title', 'id');

        return view('document_views.index', compact('views', 'documents'));
    }

    public function documentStats(Document $document)
    {
        $viewsCount = $document->views()->count();

        $viewsByDate = $document->views()
            ->select(DB::raw('DATE(viewed_at) as date'), DB::raw('COUNT(*) as count'))
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $uniqueViewers = $document->views()
            ->select('user_id')
            ->distinct()
            ->count();

        return view('admin.document_views.stats', compact('document', 'viewsCount', 'viewsByDate', 'uniqueViewers'));
    }
}
