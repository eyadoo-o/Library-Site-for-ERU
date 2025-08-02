<?php

namespace App\Http\Livewire;

use Livewire\Component;
use App\Models\Document;
use App\Models\DocumentUserView;
use Illuminate\Support\Facades\Auth;

class DocumentViewer extends Component
{
    public $document;

    public function mount(Document $document)
    {
        $this->document = $document;
        $this->recordView();
    }

    protected function recordView()
    {
        $userId = Auth::id();
        // Get the real IP from Livewire's request
        $ipAddress = request()->header('x-forwarded-for') ?? request()->ip();

        // Only record view if no view exists from this IP/user within the last hour
        if (!DocumentUserView::existsWithinTimeWindow($this->document->id, $userId, $ipAddress)) {
            try {
                DocumentUserView::create([
                    'user_id' => $userId,
                    'document_id' => $this->document->id,
                    'ip_address' => $ipAddress,
                    'viewed_at' => now(),
                ]);
            } catch (\Exception $e) {
                // Handle potential race condition with unique constraint
            }
        }
    }

    public function render()
    {
        return view('livewire.document-viewer');
    }
}
