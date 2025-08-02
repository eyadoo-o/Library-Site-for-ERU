<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class UserSearchController extends Controller
{
    /**
     * Search for users based on name, email, or ID.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function search(Request $request)
    {
        $query = $request->get('q', '');

        // Make sure we have at least 2 characters to search
        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $users = User::where(function($q) use ($query) {
                $q->where('name', 'like', "%{$query}%")
                  ->orWhere('email', 'like', "%{$query}%")
                  ->orWhere('id', 'like', "%{$query}%");
            })
            ->select('id', 'name', 'email')
            ->limit(10)
            ->get();

        return response()->json($users);
    }

    /**
     * Get a specific user.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\JsonResponse
     */
    public function show(User $user)
    {
        return response()->json([
            'id' => $user->id,
            'name' => $user->name,
            'email' => $user->email,
        ]);
    }
}
