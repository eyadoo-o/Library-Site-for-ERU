<?php

namespace App\Http\Controllers;

use App\Models\FeatureRequest;
use Illuminate\Http\Request;

class FeatureRequestController extends Controller
{
    public function index(Request $request)
    {
        $query = FeatureRequest::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        $requests = $query->withCount('votes')
            ->latest()
            ->paginate(10);

        return view('feature-requests.index', [
            'requests' => $requests,
            'types' => ['book_request', 'system_feature', 'event_suggestion'],
            'statuses' => ['pending', 'under_review', 'approved', 'completed', 'rejected']
        ]);
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'type' => 'required|in:book_request,system_feature,event_suggestion'
        ]);

        $data['requested_by'] = auth()->id();
        $data['status'] = 'pending';

        FeatureRequest::create($data);

        return redirect()->route('feature-requests.index')
            ->with('success', 'Feature request submitted successfully.');
    }

    public function vote(FeatureRequest $request)
    {
        $request->votes()->updateOrCreate(
            ['user_id' => auth()->id()],
            ['user_id' => auth()->id()]
        );

        $request->increment('votes_count');

        return back()->with('success', 'Vote recorded successfully.');
    }

    public function updateStatus(Request $request, FeatureRequest $featureRequest)
    {
        $data = $request->validate([
            'status' => 'required|in:pending,under_review,approved,completed,rejected'
        ]);

        $featureRequest->update($data);

        return back()->with('success', 'Status updated successfully.');
    }
}
