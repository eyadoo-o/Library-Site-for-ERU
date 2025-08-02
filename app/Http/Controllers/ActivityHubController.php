<?php

namespace App\Http\Controllers;

use App\Models\ActivityHub;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class ActivityHubController extends Controller
{
    use AuthorizesRequests;

    public function index(Request $request)
    {
        $query = ActivityHub::query();

        if ($request->filled('search')) {
            $query->where('name', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('activity_type') && $request->activity_type !== 'all') {
            $query->where('activity_type', $request->activity_type);
        }

        if ($request->filled('date_from')) {
            $query->where('start_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->where('end_date', '<=', $request->date_to);
        }

        $activities = $query->latest()->paginate(10)->withQueryString();
        return view('admin.activities.index', compact('activities'));
    }

    public function create()
    {
        return view('admin.activities.create');
    }

    public function store(Request $request)
    {
        Log::info('Store method triggered', $request->all());

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048',
            'activity_type' => 'required|in:public,private',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after_or_equal:start_date',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        $data['status'] = 'active';
        $data['created_by'] = auth()->id();
        $data['current_members'] = 0;

        ActivityHub::create($data);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Activity created successfully.');
    }

    public function edit(ActivityHub $activity)
    {
        $this->authorize('update', $activity);
        return view('admin.activities.edit', compact('activity'));
    }

    public function update(Request $request, ActivityHub $activity)
    {
        $this->authorize('update', $activity);

        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'required|in:active,inactive',
            'location' => 'nullable|string|max:255',
            'activity_type' => 'required|in:public,private',
            'start_date' => 'nullable|date',
            'end_date' => 'nullable|date|after:start_date',
        ]);

        if ($request->hasFile('image')) {
            Storage::disk('public')->delete($activity->image);
            $data['image'] = $request->file('image')->store('activities', 'public');
        }

        $activity->update($data);

        return redirect()->route('admin.activities.index')
            ->with('success', 'Activity updated successfully.');
    }

    public function destroy(ActivityHub $activity)
    {
        $this->authorize('delete', $activity);

        try {
            $activity->delete();
            return redirect()->route('admin.activities.index')
                ->with('success', 'Activity deleted successfully.');
        } catch (\Exception $e) {
            return redirect()->route('admin.activities.index')
                ->with('error', 'Unable to delete activity.');
        }
    }
}
