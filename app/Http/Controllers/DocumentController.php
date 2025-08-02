<?php

namespace App\Http\Controllers;

use App\Models\Document;
use App\Models\DocumentUserView;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;

class DocumentController extends Controller
{
    public function index(Request $request)
    {
        $query = Document::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('title', 'like', '%' . $request->search . '%')
                  ->orWhere('description', 'like', '%' . $request->search . '%');
            });
        }

        $documents = $query->with('uploader')
            ->withCount('views')
            ->latest()
            ->paginate(12)
            ->withQueryString();

        return view('admin.documents.index', [
            'documents' => $documents,
            'types' => Document::typeOptions()
        ]);
    }

    public function publicIndex(Request $request)
    {
        $query = Document::query();

        if ($request->filled('type') && $request->type !== 'all') {
            $query->where('type', $request->type);
        }

        $documents = $query->latest()->paginate(12)->withQueryString();

        return view('documents', compact('documents'));
    }

    public function create()
    {
        return view('admin.documents.create', [
            'types' => Document::typeOptions()
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'file' => 'required|file|max:10240', // 10MB max
            'type' => 'required|in:exam,article,book,research_paper,audio_book,podcast',
        ]);

        $file = $request->file('file');
        $filePath = $file->store('documents', 'public');

        Document::create([
            'title' => $request->title,
            'description' => $request->description,
            'file_path' => $filePath,
            'uploaded_by' => Auth::id(),
            'type' => $request->type,
        ]);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document uploaded successfully.');
    }

    public function show(Request $request, Document $document)
    {
        $this->recordView($request, $document);
        return view('admin.documents.show', compact('document'));
    }

    public function edit(Document $document)
    {
        return view('admin.documents.edit', [
            'document' => $document,
            'types' => Document::typeOptions()
        ]);
    }

    public function update(Request $request, Document $document)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'type' => 'required|in:exam,article,book,research_paper,audio_book,podcast',
            'file' => 'nullable|file|max:10240', // 10MB max
        ]);

        $data = [
            'title' => $request->title,
            'description' => $request->description,
            'type' => $request->type,
        ];

        if ($request->hasFile('file')) {
            // Delete the old file
            Storage::disk('public')->delete($document->file_path);

            // Store the new file
            $file = $request->file('file');
            $data['file_path'] = $file->store('documents', 'public');
        }

        $document->update($data);

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document updated successfully.');
    }

    public function destroy(Document $document)
    {
        // Delete the file from storage
        Storage::disk('public')->delete($document->file_path);

        $document->delete();

        return redirect()->route('admin.documents.index')
            ->with('success', 'Document deleted successfully.');
    }

    public function download(Request $request, Document $document)
    {
        $this->recordView($request, $document);
        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
        $filename = $document->title . ($extension ? '.' . $extension : '');
        return Storage::disk('public')->download($document->file_path, $filename);
    }

    public function publicDownload(Request $request, Document $document)
    {
        $this->recordView($request, $document);
        $extension = pathinfo($document->file_path, PATHINFO_EXTENSION);
        $filename = $document->title . ($extension ? '.' . $extension : '');
        return \Storage::disk('public')->download($document->file_path, $filename);
    }

    protected function recordView(Request $request, Document $document)
    {
        $userId = Auth::id();
        $ipAddress = $request->ip();
        $userAgent = $request->userAgent();

        // Parse user agent string
        $browserInfo = $this->parseUserAgent($userAgent);

        if (!DocumentUserView::existsWithinTimeWindow($document->id, $userId, $ipAddress)) {
            try {
                DocumentUserView::create([
                    'user_id' => $userId,
                    'document_id' => $document->id,
                    'ip_address' => $ipAddress,
                    'viewed_at' => now(),
                    'user_agent' => $userAgent,
                    'browser' => $browserInfo['browser'],
                    'platform' => $browserInfo['platform'],
                    'device' => $browserInfo['device']
                ]);
            } catch (\Exception $e) {
                report($e);
            }
        }
    }

    protected function parseUserAgent($userAgent)
    {
        $browser = 'Unknown';
        $platform = 'Unknown';
        $device = 'Unknown';

        // Platform detection
        $platforms = [
            'Windows' => '/windows|win32/i',
            'Mac' => '/macintosh|mac os x/i',
            'Linux' => '/linux/i',
            'iOS' => '/iphone|ipad|ipod/i',
            'Android' => '/android/i'
        ];

        foreach ($platforms as $name => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                $platform = $name;
                break;
            }
        }

        // Browser detection
        $browsers = [
            'Chrome' => '/chrome/i',
            'Firefox' => '/firefox/i',
            'Safari' => '/safari/i',
            'Edge' => '/edg/i',
            'Opera' => '/opera|opr/i',
            'IE' => '/msie|trident/i'
        ];

        foreach ($browsers as $name => $pattern) {
            if (preg_match($pattern, $userAgent)) {
                $browser = $name;
                break;
            }
        }

        // Device detection
        if (preg_match('/(tablet|ipad|playbook)|(android(?!.*(mobi|opera mini)))/i', strtolower($userAgent))) {
            $device = 'Tablet';
        } else if (preg_match('/(up.browser|up.link|mmp|symbian|smartphone|midp|wap|phone|android|iemobile)/i', strtolower($userAgent))) {
            $device = 'Mobile';
        } else {
            $device = 'Desktop';
        }

        return [
            'browser' => $browser,
            'platform' => $platform,
            'device' => $device
        ];
    }
}
