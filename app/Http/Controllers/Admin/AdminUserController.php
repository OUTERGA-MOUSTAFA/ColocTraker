<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AdminUserController extends Controller
{
    public function index()
    {
        $users = User::withCount(['colocation' => function($q) {
            $q->whereNull('left_at');
        }])
        ->paginate(15);

        return view('admin.users', compact('users'));
    }

    public function ban(User $user)
    {
        if ($user->id === auth()->id()) {
            return back()->with('error', 'Vous ne pouvez pas vous bannir vous-même.');
        }

        $user->update(['is_banned' => true]);

        return back()->with('success', 'Utilisateur banni avec succès.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);

        return back()->with('success', 'Utilisateur débanni avec succès.');
    }
}
