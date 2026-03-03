<?php

namespace App\Http\Controllers;

use App\Models\Depence;
use App\Models\Colocation;
use App\Models\Settlement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DepenceController extends Controller
{
    public function store(Request $request, Colocation $colocation)
    {
        $request->validate([
            'titre' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'montant' => 'required|numeric|min:1',
            'user_id' => 'required|exists:users,id',
            'category_id' => 'required|exists:categories,id',
        ]);

        // Check if current user is active in this colocation
        if (!$colocation->users()->where('user_id', auth()->id())->wherePivotNull('left_at')->exists()) {
            abort(403, 'Not active on this colocation');
        }

        // Check if the payer is a member
        if (!$colocation->users()->where('user_id', $request->user_id)->wherePivotNull('left_at')->exists()) {
            abort(403, 'User not in this colocation');
        }

        // Create the depense
        $depence = Depence::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'montant' => $request->montant,
            'user_id' => $request->user_id,
            'colocation_id' => $colocation->id,
            'category_id' => $request->category_id,
        ]);

        // Get active members
        $members = $colocation->users()->wherePivotNull('left_at')->get();
        $membersCount = $members->count();

        if ($membersCount > 1) {
            $share = round($depence->montant / $membersCount, 2);

            foreach ($members as $member) {
                // Skip the payer: they already payed everything
                if ($member->id == $request->user_id) continue;

                // Create or update settlements for the other members
                $settlement = Settlement::updateOrCreate(
                    [
                        'colocation_id' => $colocation->id,
                        'from_user_id'  => $member->id,      // who owes
                        'to_user_id'    => $request->user_id, // who is paid
                        'is_paid'       => false,
                    ],
                    [
                        'amount' => 0
                    ]
                );

                $settlement->increment('amount', $share);
            }
        }

        return back()->with('success', 'Dépense ajoutée.');
    }

    public function destroy(Depence $depence)
    {
        if ($depence->user_id !== auth()->id()) {
            abort(403);
        }

        $depence->delete();

        return back()->with('success', 'Dépense supprimée.');
    }
}
