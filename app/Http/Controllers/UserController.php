<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use App\Http\Requests\UserRequest;
use Carbon\Carbon;

class UserController extends Controller
{
    public function index(Request $request)
    {
        $types = [
            'admin' => 'Admin',
            'student' => 'Student',
            'library_staff' => 'Library Staff',
            'faculty_staff' => 'Faculty Staff',
            'student_activity_coordinator' => 'Student Activity Coordinator'
        ];

        $query = User::query();

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        if ($request->filled('search')) {
            $query->where(function ($q) use ($request) {
                $q->where('name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%')
                  ->orWhere('faculty_id', 'like', '%' . $request->search . '%');
            });
        }

        $users = $query->latest()->paginate(10)->withQueryString();

        return view('admin.users.index', compact('users', 'types'));
    }

    public function create()
    {
        $types = [
            'admin' => 'Admin',
            'student' => 'Student',
            'library_staff' => 'Library Staff',
            'faculty_staff' => 'Faculty Staff',
            'student_activity_coordinator' => 'Student Activity Coordinator'
        ];
        return view('admin.users.create', compact('types'));
    }

    public function store(UserRequest $request)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $data['password'] = Hash::make($data['password']);

        if ($request->filled('faculty_id')) {
            $data['faculty_id'] = $request->faculty_id;
        }

        User::create($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User created successfully.');
    }

    public function edit(User $user)
    {
        $types = [
            'admin' => 'Admin',
            'student' => 'Student',
            'library_staff' => 'Library Staff',
            'faculty_staff' => 'Faculty Staff',
            'student_activity_coordinator' => 'Student Activity Coordinator'
        ];
        return view('admin.users.edit', compact('user', 'types'));
    }

    public function update(UserRequest $request, User $user)
    {
        $data = $request->validated();

        if ($request->hasFile('profile_photo')) {
            if ($user->profile_photo_path) {
                Storage::disk('public')->delete($user->profile_photo_path);
            }
            $data['profile_photo_path'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        if ($request->filled('password')) {
            $data['password'] = Hash::make($data['password']);
        } else {
            unset($data['password']);
        }

        if ($request->filled('faculty_id')) {
            $data['faculty_id'] = $request->faculty_id;
        } else {
            $data['faculty_id'] = null;
        }

        $user->update($data);

        return redirect()->route('admin.users.index')
            ->with('success', 'User updated successfully.');
    }

    public function destroy(User $user)
    {
        if ($user->profile_photo_path) {
            Storage::disk('public')->delete($user->profile_photo_path);
        }

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'User deleted successfully.');
    }

    public function confirm(User $user)
    {
        $user->confirmed = true;
        $user->email_verified_at = Carbon::now();
        $user->save();

        return redirect()->route('admin.users.index')
            ->with('success', 'User confirmed successfully.');
    }

    public function search(Request $request)
    {
        $query = $request->get('q');
        $users = User::where('type', '!=', 'admin')
            ->where(function ($q2) use ($query) {
                $q2->where('name', 'like', "%{$query}%")
                   ->orWhere('email', 'like', "%{$query}%")
                   ->orWhere('id', 'like', "%{$query}%")
                   ->orWhere('faculty_id', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'email', 'faculty_id')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    public function apiShow(User $user)
    {
        return response()->json([
            'id'    => $user->id,
            'name'  => $user->name,
            'email' => $user->email,
            'faculty_id' => $user->faculty_id,
        ]);
    }
}
