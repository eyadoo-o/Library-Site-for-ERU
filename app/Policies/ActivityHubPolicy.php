<?php

namespace App\Policies;

use App\Models\ActivityHub;
use App\Models\User;

class ActivityHubPolicy
{
    public function create(User $user)
    {
        return $user->type === 'admin' || $user->type === 'creator';
    }

    public function update(User $user, ActivityHub $activity)
    {
        return $user->id === $activity->created_by || $user->type === 'admin';
    }

    public function delete(User $user, ActivityHub $activity)
    {
        return $user->id === $activity->created_by || $user->type === 'admin';
    }
}
