<?php

namespace App\Policies;

use App\Models\Event;
use App\Models\User;
use Illuminate\Auth\Access\HandlesAuthorization;

class EventPolicy
{
    use HandlesAuthorization;

    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Event $event): bool
    {
        // Allow admin or creator to view
        return $user->is_admin || $user->id === $event->created_by;
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return true;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Event $event): bool
    {
        // Allow admin or creator to update
        return $user->is_admin || $user->id === $event->created_by;
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Event $event): bool
    {
        // Allow admin or creator to delete
        return $user->is_admin || $user->id === $event->created_by;
    }

    /**
     * Determine whether the user can register other users for the event.
     */
    public function registerOthers(User $user, Event $event): bool
    {
        // Allow admin or creator to register others
        return $user->is_admin || $user->id === $event->created_by;
    }
}
