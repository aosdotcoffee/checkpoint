<?php

namespace App\Policies;

use App\Models\Authority;
use App\Models\User;

class AuthorityPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return $user->can('view authorities');
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Authority $authority): bool
    {
        return $user->can('view authorities');
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user): bool
    {
        return $user->can('manage authorities');
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Authority $authority): bool
    {
        return $user->can('manage authorities');
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Authority $authority): bool
    {
        return $user->can('manage authorities');
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Authority $authority): bool
    {
        return $user->can('manage authorities');
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Authority $authority): bool
    {
        return $user->can('manage authorities');
    }
}
