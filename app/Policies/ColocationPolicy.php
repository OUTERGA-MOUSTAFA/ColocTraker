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

    public function manageCategories(User $user, Colocation $colocation)
    {
        // L'utilisateur doit être propriétaire de la colocation
        return $colocation->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'owner')
            ->exists();
    }
}
