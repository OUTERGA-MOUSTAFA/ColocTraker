<?php

namespace App\Http\Controllers;

use App\Models\Depence;
use App\Models\Colocation;
use Illuminate\Http\Request;

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

        // check if is active
        if (!$colocation->users()->where('user_id', auth()->id())->wherePivotNull('left_at')->exists()) {
            abort(403, 'Not avtive on this colocation');
        }

        // check if user will payer in coloc
        if (
            !$colocation->users()
                ->where('user_id', $request->user_id)
                ->wherePivotNull('left_at')
                ->exists()
        ) {
            abort(403, 'User not in this colocation');
        }
        Depence::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'montant' => $request->montant,
            'user_id' => $request->user_id,
            'colocation_id' => $colocation->id,
            'category_id' => $request->category_id,
        ]);

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
