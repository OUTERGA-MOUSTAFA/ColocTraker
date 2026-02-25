<?php

namespace App\Policies;

use App\Models\Colocation;
use App\Models\User;
use Illuminate\Auth\Access\Response;

class ColocationPolicy
{
    /**
     * Determine whether the user can view any models.
     */
    public function viewAny(User $user): bool
    {
        return false;
    }

    /**
     * Determine whether the user can view the model.
     */
    public function view(User $user, Colocation $colocation)
    {
        if ($user->is_banned) return false;

        if ($user->role === 'admin') return true;

        return $colocation->users()
            ->where('user_id', $user->id)
            ->exists();
    }

    /**
     * Determine whether the user can create models.
     */
    public function create(User $user)
    {
        return !$user->is_banned;
    }

    /**
     * Determine whether the user can update the model.
     */
    public function update(User $user, Colocation $colocation)
    {
        if ($user->is_banned) return false;

        if ($user->role === 'admin') return true;

        return $colocation->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }

    /**
     * Determine whether the user can delete the model.
     */
    public function delete(User $user, Colocation $colocation)
    {
        return $user->role === 'admin';
    }

    /**
     * Determine whether the user can restore the model.
     */
    public function restore(User $user, Colocation $colocation): bool
    {
        return false;
    }

    /**
     * Determine whether the user can permanently delete the model.
     */
    public function forceDelete(User $user, Colocation $colocation): bool
    {
        return false;
    }
}
