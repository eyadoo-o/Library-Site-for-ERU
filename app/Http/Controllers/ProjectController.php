<?php

namespace App\Http\Controllers;

use App\Models\Project;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProjectController extends Controller
{
    public function index(Request $request)
    {
        $query = Project::query();

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('year')) {
            $query->where('academic_year', $request->year);
        }

        $projects = $query->with(['creator', 'collaborators'])
            ->withCount('collaborators')
            ->latest()
            ->paginate(12);

        return view('projects.index', compact('projects'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string',
            'category' => 'required|string',
            'academic_year' => 'required|integer',
            'file' => 'nullable|file|max:10240',
            'thumbnail' => 'nullable|image|max:2048'
        ]);

        if ($request->hasFile('file')) {
            $data['file_path'] = $request->file('file')->store('projects', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            $data['thumbnail_path'] = $request->file('thumbnail')->store('projects/thumbnails', 'public');
        }

        $data['created_by'] = auth()->id();
        $project = Project::create($data);

        return redirect()->route('projects.index')
            ->with('success', 'Project created successfully.');
    }

    public function addCollaborator(Request $request, Project $project)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'role' => 'required|string'
        ]);

        $project->collaborators()->create($data);

        return back()->with('success', 'Collaborator added successfully.');
    }

    public function toggleFeature(Project $project)
    {
        $project->update(['is_featured' => !$project->is_featured]);
        return back()->with('success', 'Project featured status updated.');
    }
}
