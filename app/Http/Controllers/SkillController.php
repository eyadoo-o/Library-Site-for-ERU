<?php

namespace App\Http\Controllers;

use App\Models\Skill;
use App\Models\User;
use App\Models\SkillExchange;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;

class SkillController extends Controller
{
    public function index(Request $request)
    {
        $query = Skill::query();
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }
        if ($request->filled('mentor')) {
            $query->where('mentor_id', $request->mentor);
        }
        if ($request->filled('confirmed')) {
            $query->where('confirmed', $request->confirmed == 'true');
        }
        $skills = $query->with('mentor')->latest()->paginate(9);

        $SkillExchanges = SkillExchange::with(['skill', 'student', 'mentor'])
            ->latest()
            ->paginate(10);

        $categories = Skill::distinct('category')->pluck('category');
        $mentors = User::has('skills')->get();
        $statuses = ['pending', 'accepted', 'completed', 'cancelled'];

        $pendingSkills = Skill::where('confirmed', false)->with('mentor')->latest()->get();

        return view('admin.skills.index', compact(
            'skills',
            'SkillExchanges',
            'categories',
            'mentors',
            'statuses',
            'pendingSkills'
        ));
    }

    public function create()
    {
        $mentors = User::all();
        return view('admin.skills.create', compact('mentors'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'mentor_id' => 'required|exists:users,id'
        ]);

        Skill::create($validated);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill created successfully.');
    }

    public function edit(Skill $skill)
    {
        $mentors = User::all();
        return view('admin.skills.edit', compact('skill', 'mentors'));
    }

    public function update(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
            'mentor_id' => 'required|exists:users,id'
        ]);

        $skill->update($validated);

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill updated successfully.');
    }

    public function destroy(Skill $skill)
    {
        $skill->delete();

        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill deleted successfully.');
    }

    /**
     * Confirm a skill (admin only)
     */
    public function confirmSkill(Skill $skill)
    {
        $skill->update(['confirmed' => true]);
        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill confirmed successfully.');
    }

    /**
     * Reject a skill (admin only)
     */
    public function rejectSkill(Skill $skill)
    {
        $skill->delete();
        return redirect()->route('admin.skills.index')
            ->with('success', 'Skill rejected and removed successfully.');
    }

    // SKILL EXCHANGES MANAGEMENT

    public function exchangesIndex(Request $request)
    {
        $query = SkillExchange::query();

        if ($request->filled('student_id')) {
            $query->where('student_id', $request->student_id);
        }

        if ($request->filled('mentor_id')) {
            $query->orWhere('mentor_id', $request->mentor_id);
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        $exchanges = $query->with(['mentor', 'student', 'skill'])
            ->latest()
            ->paginate(10);

        $users = User::has('skills')->orderBy('name')->get();
        $statuses = ['pending', 'accepted', 'completed', 'cancelled'];

        return view('admin.skills.exchanges.index', compact('exchanges', 'users', 'statuses'));
    }

    public function exchangesCreate(Request $request)
    {
        $users = User::has('skills')->orderBy('name')->get();
        $user1Skills = [];
        $user2Skills = [];

        // If user IDs are provided in the request, load their skills
        if ($request->filled('user1_id')) {
            $user = User::find($request->user1_id);
            if ($user) {
                $user1Skills = $user->skills;
            }
        }

        if ($request->filled('user2_id')) {
            $user = User::find($request->user2_id);
            if ($user) {
                $user2Skills = $user->skills;
            }
        }

        return view('admin.skills.exchanges.create', compact(
            'users',
            'user1Skills',
            'user2Skills'
        ));
    }

    public function exchangesStore(Request $request)
    {
        try {
            $data = $request->validate([
                'user1_id' => 'required|exists:users,id',
                'user2_id' => 'required|exists:users,id|different:user1_id',
                'skill1_id' => [
                    'required',
                    'exists:skills,id',
                    function ($attribute, $value, $fail) use ($request) {
                        $skill = Skill::where('id', $value)
                            ->where('mentor_id', $request->user1_id)
                            ->exists();

                        if (!$skill) {
                            $fail('The selected skill does not belong to the first user.');
                        }
                    },
                ],
                'skill2_id' => [
                    'required',
                    'exists:skills,id',
                    function ($attribute, $value, $fail) use ($request) {
                        $skill = Skill::where('id', $value)
                            ->where('mentor_id', $request->user2_id)
                            ->exists();

                        if (!$skill) {
                            $fail('The selected skill does not belong to the second user.');
                        }
                    },
                ],
                'description' => 'required|string|max:1000'
            ]);

            // Check for existing exchanges between these users and skills
            $existing = SkillExchange::where(function($query) use ($data) {
                    $query->where('student_id', $data['user1_id'])
                          ->where('mentor_id', $data['user2_id'])
                          ->where('skill_id', $data['skill2_id']);
                })
                ->orWhere(function($query) use ($data) {
                    $query->where('student_id', $data['user2_id'])
                          ->where('mentor_id', $data['user1_id'])
                          ->where('skill_id', $data['skill1_id']);
                })
                ->whereIn('status', ['pending', 'accepted'])
                ->exists();

            if ($existing) {
                return back()->withErrors(['general' => 'An active exchange already exists between these users for these skills.'])->withInput();
            }

            // Create first exchange (user1 learns from user2)
            SkillExchange::create([
                'student_id' => $data['user1_id'],
                'mentor_id' => $data['user2_id'],
                'skill_id' => $data['skill2_id'],
                'exchange_with_id' => $data['skill1_id'],
                'status' => 'accepted', // Auto-accept since it's a mutual exchange
                'description' => $data['description']
            ]);

            // Create second exchange (user2 learns from user1)
            SkillExchange::create([
                'student_id' => $data['user2_id'],
                'mentor_id' => $data['user1_id'],
                'skill_id' => $data['skill1_id'],
                'exchange_with_id' => $data['skill2_id'],
                'status' => 'accepted', // Auto-accept since it's a mutual exchange
                'description' => $data['description']
            ]);

            return redirect()->route('admin.skills.index')
                ->with('success', 'Skill exchange created successfully.');

        } catch (\Exception $e) {
            \Log::error('Error creating skill exchange: ' . $e->getMessage());
            return back()->withErrors(['error' => 'Failed to create skill exchange. Error: ' . $e->getMessage()])->withInput();
        }
    }

    public function exchangesShow(SkillExchange $exchange, Request $request)
    {
        $exchange->load(['mentor', 'student', 'skill']);
        return view('admin.skills.exchanges.show', compact('exchange'));
    }

    public function exchangesAccept(SkillExchange $exchange)
    {
        if ($exchange->status !== 'pending') {
             return back()->with('error', 'This exchange request is not pending.');
        }

        $exchange->update(['status' => 'accepted']);

        return back()->with('success', 'Exchange accepted successfully.');
    }

    public function exchangesComplete(SkillExchange $exchange)
    {
        if ($exchange->status !== 'accepted') {
             return back()->with('error', 'This exchange is not currently accepted.');
        }

        $exchange->update(['status' => 'completed']);

        return back()->with('success', 'Exchange marked as completed.');
    }

    public function exchangesCancel(SkillExchange $exchange, Request $request)
    {
        if (in_array($exchange->status, ['completed', 'cancelled'])) {
            return back()->with('error', 'This exchange cannot be cancelled.');
        }

        $exchange->update(['status' => 'cancelled']);

        return redirect()->route('admin.skills.index', ['tab' => 'exchanges'])->with('success', 'Exchange cancelled successfully.');
    }

    public function exchangesRate(Request $request, SkillExchange $exchange)
    {
        if ($exchange->status !== 'completed') {
            return back()->with('error', 'This exchange is not completed yet.');
        }

        if ($exchange->rating !== null) {
             return back()->with('error', 'This exchange has already been rated.');
        }

        $data = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:1000'
        ]);

        $exchange->update($data);

        $skill = $exchange->skill;
        $newAvgRating = SkillExchange::where('skill_id', $skill->id)
                                    ->where('status', 'completed')
                                    ->whereNotNull('rating')
                                    ->avg('rating');
        $skill->update(['rating' => $newAvgRating]);

        return back()->with('success', 'Rating submitted successfully.');
    }

    public function exchangesDestroy(SkillExchange $exchange)
    {
        if (!in_array($exchange->status, ['pending', 'cancelled'])) {
             return back()->with('error', 'Only pending or cancelled exchanges can be deleted.');
        }

        $exchange->delete();

        return redirect()->route('admin.skills.index', ['tab' => 'exchanges'])->with('success', 'Exchange request deleted.');
    }

    /**
     * Get skills for a specific user
     *
     * @param int $id
     * @return \Illuminate\Http\JsonResponse
     */
    public function getUserSkills($id)
    {
        $user = User::findOrFail($id);
        $skills = Skill::where('mentor_id', $id)->get();

        return response()->json($skills);
    }

    /**
     * Display a public listing of all skills
     */
    public function publicIndex(Request $request)
    {
        $query = Skill::with('mentor')->where('confirmed', true);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }


        $skills = $query->latest()->paginate(9);
        $categories = Skill::where('confirmed', true)->distinct()->pluck('category');

        // Get data for authenticated user including unconfirmed skills
        $userSkills = collect();
        $pendingSkills = collect();
        $sentExchanges = collect();
        $receivedExchanges = collect();

        if (Auth::check()) {
            $user = Auth::user();
            $userSkills = Skill::where('mentor_id', $user->id)
                              ->where('confirmed', true)
                              ->get();

            $pendingSkills = Skill::where('mentor_id', $user->id)
                                 ->where('confirmed', false)
                                 ->get();

            $sentExchanges = SkillExchange::where('student_id', $user->id)
                ->with(['mentor', 'skill', 'exchangeWith'])
                ->latest()
                ->get();

            $receivedExchanges = SkillExchange::where('mentor_id', $user->id)
                ->with(['student', 'skill', 'exchangeWith'])
                ->latest()
                ->get();
        }

        return view('skills', compact(
            'skills',
            'categories',
            'userSkills',
            'pendingSkills',
            'sentExchanges',
            'receivedExchanges'
        ));
    }

    /**
     * Handle skill request submission
     */
    public function submitSkillRequest(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'required|string',
        ]);

        // Create skill with current user as mentor (unconfirmed)
        Skill::create([
            'name' => $validated['name'],
            'category' => $validated['category'],
            'description' => $validated['description'],
            'mentor_id' => Auth::id(),
            'confirmed' => false, // Skills need admin approval
        ]);

        return redirect()->route('skills.index')
            ->with('success', __('messages.skill_submitted_for_review'));
    }

    /**
     * Handle submission of skill exchange request
     */
    public function submitExchangeRequest(Request $request, Skill $skill)
    {
        $validated = $request->validate([
            'offered_skill_id' => 'required|exists:skills,id',
            'message' => 'nullable|string|max:500',
        ]);

        // Verify the offered skill belongs to the current user
        $offeredSkill = Skill::findOrFail($validated['offered_skill_id']);
        if ($offeredSkill->mentor_id != Auth::id()) {
            return back()->with('error', __('messages.not_your_skill'));
        }

        // Verify user is not trying to exchange with their own skill
        if ($skill->mentor_id == Auth::id()) {
            return back()->with('error', __('messages.cannot_exchange_own_skill'));
        }

        // Check for existing pending exchange
        $existingExchange = SkillExchange::where('student_id', Auth::id())
            ->where('skill_id', $skill->id)
            ->whereIn('status', ['pending', 'accepted'])
            ->exists();

        if ($existingExchange) {
            return back()->with('error', __('messages.exchange_request_already_exists'));
        }

        // Create exchange request
        SkillExchange::create([
            'student_id' => Auth::id(),
            'mentor_id' => $skill->mentor_id,
            'skill_id' => $skill->id,
            'exchange_with_id' => $validated['offered_skill_id'],
            'description' => $validated['message'] ?? null,
            'status' => 'pending'
        ]);

        return back()->with('success', __('messages.exchange_request_sent'));
    }

    /**
     * Cancel a skill exchange request
     */
    public function cancelExchange(SkillExchange $exchange)
    {
        if ($exchange->student_id != Auth::id() || $exchange->status != 'pending') {
            return back()->with('error', __('messages.cannot_cancel_exchange'));
        }

        $exchange->update(['status' => 'cancelled']);
        return back()->with('success', __('messages.exchange_cancelled'));
    }

    /**
     * Accept a skill exchange request
     */
    public function acceptExchange(SkillExchange $exchange)
    {
        if ($exchange->mentor_id != Auth::id() || $exchange->status != 'pending') {
            return back()->with('error', __('messages.cannot_accept_exchange'));
        }

        $exchange->update(['status' => 'accepted']);
        return back()->with('success', __('messages.exchange_accepted'));
    }

    /**
     * Reject a skill exchange request
     */
    public function rejectExchange(SkillExchange $exchange)
    {
        if ($exchange->mentor_id != Auth::id() || $exchange->status != 'pending') {
            return back()->with('error', __('messages.cannot_reject_exchange'));
        }

        $exchange->update(['status' => 'rejected']);
        return back()->with('success', __('messages.exchange_rejected'));
    }

    /**
     * Mark a skill exchange as complete
     */
    public function completeExchange(SkillExchange $exchange)
    {
        // Allow either student or mentor to mark as complete
        if (($exchange->student_id != Auth::id() && $exchange->mentor_id != Auth::id()) ||
            $exchange->status != 'accepted') {
            return back()->with('error', __('messages.cannot_complete_exchange'));
        }

        $exchange->update(['status' => 'completed']);
        return back()->with('success', __('messages.exchange_completed'));
    }

    /**
     * Rate a completed skill exchange
     */
    public function rateExchange(Request $request, SkillExchange $exchange)
    {
        if ($exchange->student_id != Auth::id() || $exchange->status != 'completed') {
            return back()->with('error', __('messages.cannot_rate_exchange'));
        }

        if ($exchange->rating !== null) {
            return back()->with('error', __('messages.already_rated'));
        }

        $validated = $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'feedback' => 'nullable|string|max:500',
        ]);

        $exchange->update([
            'rating' => $validated['rating'],
            'feedback' => $validated['feedback'] ?? null
        ]);

        // Update the skill's average rating
        $skillId = $exchange->skill_id;
        $avgRating = SkillExchange::where('skill_id', $skillId)
            ->where('status', 'completed')
            ->whereNotNull('rating')
            ->avg('rating');

        Skill::where('id', $skillId)->update(['rating' => $avgRating]);

        return back()->with('success', __('messages.exchange_rated'));
    }
}
