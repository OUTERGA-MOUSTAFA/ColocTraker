<?php

namespace App\Policies;

use App\Models\User;
use Illuminate\Auth\Access\Response;

class UserPolicy
{
    public function update(User $authUser, User $user)
    {
        return $authUser->id === $user->id
            || $authUser->role === 'admin';
    }

    public function ban(User $authUser, User $user)
    {
        return $authUser->role === 'admin';
    }
}
