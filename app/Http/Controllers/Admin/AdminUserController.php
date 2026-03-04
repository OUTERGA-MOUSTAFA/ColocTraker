<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

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

        DB::transaction(function () use ($user) {
            // Khrej men jami3 les colocations
            $user->colocation()->wherePivotNull('left_at')->each(function ($colocation) use ($user) {
                $colocation->users()->updateExistingPivot($user->id, [
                    'left_at' => now()
                ]);
            });

            // Reputation -1
            $user->decrement('reputation_score');

            // Ban l'utilisateur
            $user->update(['is_banned' => true]);
        });

        return back()->with('success', 'Utilisateur banni et retiré de toutes les colocations.');
    }

    public function unban(User $user)
    {
        $user->update(['is_banned' => false]);

        return back()->with('success', 'Utilisateur débanni avec succès.');
    }
}
