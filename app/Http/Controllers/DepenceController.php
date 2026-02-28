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
            'description' => 'nullable|string|max:500',
            'montant' => 'required|numeric|min:0.01',
            'category_id' => 'nullable|exists:categories,id'
        ]);

        // تأكد user عضو active
        if (!$colocation->users()
            ->where('user_id', auth()->id())
            ->wherePivotNull('left_at')
            ->exists()) {

            abort(403);
        }

        Depence::create([
            'titre' => $request->titre,
            'description' => $request->description,
            'montant' => $request->montant,
            'user_id' => auth()->id(),
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