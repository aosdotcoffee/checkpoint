<?php

namespace App\Policies;

use App\Models\Remote;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class RemotePolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view remotes');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Remote $remote): bool
    {
        return $user->can('view remotes');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage remotes');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Remote $remote): bool
    {
        return $user->can('manage remotes');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Remote $remote): bool
    {
        return $user->can('manage remotes');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Remote $remote): bool
    {
        return $user->can('manage remotes');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Remote $remote): bool
    {
        return $user->can('manage remotes');
    }
}
