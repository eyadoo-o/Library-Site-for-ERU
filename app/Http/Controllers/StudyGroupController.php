<?php

namespace App\Http\Controllers;

use App\Models\StudyGroup;
use Illuminate\Http\Request;

class StudyGroupController extends Controller
{
    public function index(Request $request)
    {
        $query = StudyGroup::query();

        if ($request->filled('subject')) {
            $query->where('subject', $request->subject);
        }

        $groups = $query->with(['members', 'creator'])
            ->withCount('members')
            ->latest()
            ->paginate(10);

        return view('study-groups.index', compact('groups'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'required|string',
            'subject' => 'required|string',
            'max_members' => 'required|integer|min:2'
        ]);

        $data['created_by'] = auth()->id();
        $group = StudyGroup::create($data);

        // Add creator as leader
        $group->members()->create([
            'user_id' => auth()->id(),
            'role' => 'leader'
        ]);

        return redirect()->route('study-groups.index')
            ->with('success', 'Study group created successfully.');
    }

    public function join(StudyGroup $group)
    {
        if ($group->members()->count() >= $group->max_members) {
            return back()->with('error', 'Group is full.');
        }

        $group->members()->create([
            'user_id' => auth()->id(),
            'role' => 'member'
        ]);

        return back()->with('success', 'Joined group successfully.');
    }

    public function leave(StudyGroup $group)
    {
        $group->members()->where('user_id', auth()->id())->delete();
        return back()->with('success', 'Left group successfully.');
    }
}
