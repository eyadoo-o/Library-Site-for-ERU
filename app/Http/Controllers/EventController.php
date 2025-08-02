<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\EventVisit;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Auth;

class EventController extends Controller
{
    public function index(Request $request)
    {
        $query = Event::with('registrations')->withCount('registrations');

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        if ($request->filled('event_type') && $request->event_type !== 'all') {
            $query->where('event_type', $request->event_type);
        }

        if ($request->filled('date_from')) {
            $query->whereDate('event_date', '>=', $request->date_from);
        }

        if ($request->filled('date_to')) {
            $query->whereDate('event_date', '<=', $request->date_to);
        }

        $events = $query->latest()->paginate(10)->withQueryString();
        return view('admin.events.index', compact('events'));
    }

    public function publicIndex(Request $request)
    {
        $now = now();
        // Define date boundaries
        $startOfThisWeek = $now->copy()->startOfWeek();
        $endOfThisWeek = $now->copy()->endOfWeek();
        $startOfNextWeek = $now->copy()->addWeek()->startOfWeek();
        $endOfNextWeek = $now->copy()->addWeek()->endOfWeek();
        $startOfThisMonth = $now->copy()->startOfMonth();
        $endOfThisMonth = $now->copy()->endOfMonth();
        $startOfNextMonth = $now->copy()->addMonth()->startOfMonth();
        $endOfNextMonth = $now->copy()->addMonth()->endOfMonth();

        // Initialize collections
        $thisWeekEvents = collect();
        $nextWeekEvents = collect();
        $upcomingEvents = collect();

        // Base query for event fetching
        $baseEventQuery = Event::query()->where('status', 'published')->orderBy('event_date', 'asc');
        if ($request->filled('event_type')) {
            $baseEventQuery->where('event_type', $request->event_type);
        }

        $dateRange = $request->input('date_range');

        if (!$dateRange) { // No specific date range filter, show all standard sections
            $thisWeekEvents = (clone $baseEventQuery)->whereBetween('event_date', [$startOfThisWeek, $endOfThisWeek])->get();
            $nextWeekEvents = (clone $baseEventQuery)->whereBetween('event_date', [$startOfNextWeek, $endOfNextWeek])->get();
            $upcomingEvents = (clone $baseEventQuery)->where('event_date', '>', $endOfNextWeek)->get();
        } elseif ($dateRange == 'this_week') {
            // Only "This Week" section is populated
            $thisWeekEvents = (clone $baseEventQuery)->whereBetween('event_date', [$startOfThisWeek, $endOfThisWeek])->get();
            // $nextWeekEvents and $upcomingEvents remain empty, so their sections will be hidden by the Blade view
        } elseif ($dateRange == 'this_month') {
            // Populate "This Week" events if they fall within This Month
            $effectiveStartTW = max($startOfThisWeek, $startOfThisMonth);
            $effectiveEndTW = min($endOfThisWeek, $endOfThisMonth);
            if ($effectiveStartTW <= $effectiveEndTW) {
                $thisWeekEvents = (clone $baseEventQuery)
                    ->whereBetween('event_date', [$effectiveStartTW, $effectiveEndTW])
                    ->get();
            }

            // Populate "Next Week" events if they fall within This Month
            $effectiveStartNW = max($startOfNextWeek, $startOfThisMonth);
            $effectiveEndNW = min($endOfNextWeek, $endOfThisMonth);
            if ($effectiveStartNW <= $effectiveEndNW) {
                $nextWeekEvents = (clone $baseEventQuery)
                    ->whereBetween('event_date', [$effectiveStartNW, $effectiveEndNW])
                    ->get();
            }

            // Populate "Upcoming" events for This Month (events after next week but still within this month)
            // Define the start for "upcoming in this month" as the day after next week ends.
            $startUpcomingInMonthRange = $endOfNextWeek->copy()->addDay()->startOfDay();
            // Ensure this start is not before the actual start of the month.
            $effectiveStartUpcoming = max($startUpcomingInMonthRange, $startOfThisMonth);
            $effectiveEndUpcoming = $endOfThisMonth; // Must end by the end of this month.

            if ($effectiveStartUpcoming <= $effectiveEndUpcoming) {
                $upcomingEvents = (clone $baseEventQuery)
                    ->whereBetween('event_date', [$effectiveStartUpcoming, $effectiveEndUpcoming])
                    ->get();
            }
        } elseif ($dateRange == 'next_month') {
            // If "Next Month" is selected, hide "This Week" and "Next Week" sections.
            // All events for "next_month" are considered "upcoming" for this filtered view.
            // $thisWeekEvents and $nextWeekEvents remain empty.
            $upcomingEvents = (clone $baseEventQuery)
                ->whereBetween('event_date', [$startOfNextMonth, $endOfNextMonth])
                ->get();
        }

        return view('events', compact('thisWeekEvents', 'nextWeekEvents', 'upcomingEvents'));
    }

    public function create()
    {
        return view('admin.events.create');
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after:today',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:2048', // max 2MB
            'status' => 'nullable|string|in:draft,published,cancelled',
            'max_attendees' => 'nullable|integer|min:0',
            'event_type' => 'nullable|string|in:public,private,workshop,seminar,reading,discussion',
        ]);

        // Handle image upload
        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('event-images', 'public');
        }

        $data['created_by'] = auth()->id();
        $data['current_attendees'] = 0;
        $data['status'] = $data['status'] ?? 'draft';
        $data['event_type'] = $data['event_type'] ?? 'public';

        Event::create($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event created successfully.');
    }

    public function storeUserEvent(Request $request)
    {
        if (!Auth::check() || !in_array(Auth::user()->type, ['student_activity_coordinator', 'faculty_staff'])) {
            return redirect()->back()->with('error', 'You do not have permission to create events.');
        }

        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date|after_or_equal:today', // Ensure event_date can be today
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:10240', // max 10MB
            'status' => 'nullable|string|in:draft,published,cancelled',
            'max_attendees' => 'nullable|integer|min:0',
            'event_type' => 'nullable|string|in:public,private,workshop,seminar,reading,discussion',
        ]);

        if ($request->hasFile('image')) {
            $data['image'] = $request->file('image')->store('event-images', 'public');
        }

        $data['created_by'] = auth()->id();
        $data['current_attendees'] = 0;
        $data['status'] = $request->input('status', 'published');
        $data['event_type'] = $request->input('event_type', 'public');

        Event::create($data);

        return redirect()->route('events')
            ->with('success', 'Event created successfully. It may be subject to review.');
    }

    public function show(Event $event, Request $request)
    {
        // Track visit
        EventVisit::create([
            'event_id' => $event->id,
            'user_id' => auth()->id(),
            'ip_address' => $request->ip(),
            'user_agent' => $request->userAgent(),
            'visited_at' => now(),
        ]);

        // Check if the user is already registered
        $userRegistered = false;
        if (Auth::check()) {
            $userRegistered = $event->isRegistered(Auth::user());
        }

        // Use the public event show view
        return view('event.show', compact('event', 'userRegistered'));
    }

    public function edit(Event $event)
    {
        return view('admin.events.edit', compact('event'));
    }

    public function update(Request $request, Event $event)
    {
        $data = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'event_date' => 'required|date',
            'location' => 'nullable|string|max:255',
            'image' => 'nullable|image|max:10240', // max 10MB
            'remove_image' => 'nullable|boolean',
            'status' => 'nullable|string|in:draft,published,cancelled',
            'max_attendees' => 'nullable|integer|min:0',
            'event_type' => 'nullable|string|in:public,private,workshop,seminar,reading,discussion',
        ]);

        // Handle image upload or removal
        if ($request->hasFile('image')) {
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = $request->file('image')->store('event-images', 'public');
        } elseif ($request->boolean('remove_image')) {
            // Remove the existing image if the checkbox is checked
            if ($event->image && Storage::disk('public')->exists($event->image)) {
                Storage::disk('public')->delete($event->image);
            }
            $data['image'] = null;
        } else {
            // Keep the existing image
            unset($data['image']);
        }

        if (isset($data['remove_image'])) {
            unset($data['remove_image']);
        }

        $event->update($data);

        return redirect()->route('admin.events.index')
            ->with('success', 'Event updated successfully.');
    }

    public function destroy(Event $event)
    {
        // Delete the associated image if it exists
        if ($event->image && Storage::disk('public')->exists($event->image)) {
            Storage::disk('public')->delete($event->image);
        }

        $event->delete();
        return redirect()->route('admin.events.index')
            ->with('success', 'Event deleted successfully.');
    }

    public function register(Event $event)
    {
        // Check if the event is full
        if ($event->max_attendees > 0 && $event->current_attendees >= $event->max_attendees) {
            return back()->with('error', 'This event is already full.');
        }

        // Check if the user is already registered
        if ($event->isRegistered(auth()->user())) {
            return back()->with('error', 'You are already registered for this event.');
        }

        $event->registrations()->create([
            'user_id' => auth()->id()
        ]);

        // Increment current attendees count
        $event->increment('current_attendees');

        return back()->with('success', 'Successfully registered for the event.');
    }

    public function unregister(Event $event)
    {
        $registration = $event->registrations()
            ->where('user_id', auth()->id())
            ->first();

        if ($registration) {
            $registration->delete();

            // Decrement current attendees count
            $event->decrement('current_attendees');

            return back()->with('success', 'Successfully unregistered from the event.');
        }

        return back()->with('error', 'You are not registered for this event.');
    }

    public function registerUser(Request $request, Event $event)
    {
        $request->validate([
            'user_email' => 'required|email|exists:users,email',
        ]);

        // Check if the event is full
        if ($event->max_attendees > 0 && $event->current_attendees >= $event->max_attendees) {
            return back()->with('error', 'This event is already full.');
        }

        $user = User::where('email', $request->user_email)->first();

        if (!$user) {
            return back()->with('error', 'User not found.');
        }

        // Check if the user is already registered
        if ($event->isRegistered($user)) {
            return back()->with('error', 'This user is already registered for this event.');
        }

        $event->registrations()->create([
            'user_id' => $user->id
        ]);

        $event->increment('current_attendees');

        return back()->with('success', "Successfully registered {$user->name} for the event.");
    }

    public function registrations(Event $event)
    {
        $registrations = $event->registrations()->with('user')->get();

        return view('admin.events.registrations', compact('event', 'registrations'));
    }
}
