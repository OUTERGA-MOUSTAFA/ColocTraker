<?php

namespace App\Http\Controllers;

use App\Models\Settlement;
use App\Models\Colocation;
use Illuminate\Http\Request;

class SettlementController extends Controller
{
    public function markPaid(Request $request, Colocation $colocation)
    {
        $request->validate([
            'debt_id' => 'required|exists:settlements,id',
        ]);

        $settlement = Settlement::where('id', $request->debt_id)
            ->where('colocation_id', $colocation->id)
            ->where('is_paid', false)
            ->firstOrFail();

        // Security: payer your depence only
        if ($settlement->from_user_id !== auth()->id()) {
            abort(403, 'Unauthorized action.');
        }

        $settlement->update([
            'is_paid' => true,
        ]);

        return back()->with('success', 'Dette marquée comme payée.');
    }
}