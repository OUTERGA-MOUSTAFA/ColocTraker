<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\BalanceService;
use App\Services\ReputationService;

class ColocationController extends Controller
{

    protected $reputationService;

    public function __construct(ReputationService $reputationService)
    {
        $this->reputationService = $reputationService;
    }

    function colocations()
    {

    $colocations = Colocation::whereHas('users', function ($query) {
            $query->where('user_id', auth()->id());
        })
        ->with(['users' => function ($query) {
            $query->where('user_id', auth()->id());
        }])
        ->get();
      
        return view('colocation.colocations', compact('colocations'));
    }

    public function show($id, BalanceService $balanceService)
    {
        $colocation = Colocation::findOrFail($id);

        $data = $balanceService->getColocationBalances($colocation);

        return view('colocation.index', [
            'membersCount' => $data['membersCount'],
            'colocation' => $colocation,
            'total'      => $data['total'],
            'share'      => $data['share'],
            'balances'   => $data['balances'],
        ]);
    }


    function store(Request $request)
    {
        $this->authorize('create', Colocation::class);
        $data = $request->validate(
            [
                'name' => 'required|min:5|max:30',
                'description' => 'required|min:5|max:255'
            ]
        );

        $colocation = Colocation::create(
            [
                'name' => $data['name'],
                'description' => $data['description'],
                'created_by' => Auth()->id()
            ]
        );


        $colocation->users()->attach(auth()->id(), [
            'role' => 'owner'
        ]);

        // return view('colocation.index', compact('colocation'));
        return redirect()
            ->route('colocation.show', $colocation->id)
            ->with('success', 'Colocation créée avec succès.');
    }

    public function leaveColocation(
        Colocation $colocation,
        BalanceService $balanceService
    ) {
        $user = auth()->user();

        $isOwner = $colocation->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'owner')
            ->wherePivotNull('left_at')
            ->exists();

        if ($isOwner) {
            return back()->with('error', 'Owner must transfer ownership first.');
        }

        // reputation
        $this->reputationService->handleLeaving($user, $colocation);

        // update pivot
        $colocation->users()->updateExistingPivot($user->id, [
            'left_at' => now()
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Vous avez quitté la colocation.');
    }

    public function cancelColocation(Colocation $colocation)
    {
        $user = auth()->user();

        // owner
        $isOwner = $colocation->users()
            ->where('user_id', $user->id)
            ->wherePivot('role', 'owner')
            ->wherePivotNull('left_at')
            ->exists();

        if (!$isOwner) {
            abort(403);
        }

        // check if is a Member
        $otherMembers = $colocation->users()
            ->where('user_id', '!=', $user->id)
            ->wherePivotNull('left_at')
            ->get();

        //case no memeber
        if ($otherMembers->count() === 0) {

            $colocation->delete();

            return redirect()->route('dashboard')
                ->with('success', 'Colocation supprimée (no members left).');
        }

        // change role to an other memeber
        return back()->with(
            'error',
            'Vous devez transférer le rôle Owner avant de quitter.'
        );
    }

    public function transferOwnership(
        Colocation $colocation,
        $newOwnerId
    ) {
        $currentOwner = auth()->user();

        // check is owner
        if (!$currentOwner->isOwnerOf($colocation)) {
            abort(403);
        }

        // check member is active
        $newOwner = $colocation->users()
            ->where('user_id', $newOwnerId)
            ->wherePivotNull('left_at')
            ->first();

        if (!$newOwner) {
            return back()->with('error', 'User not valid.');
        }

        // downgrade current owner
        $colocation->users()->updateExistingPivot(
            $currentOwner->id,
            ['role' => 'member']
        );

        // upgrade new owner
        $colocation->users()->updateExistingPivot(
            $newOwnerId,
            ['role' => 'owner']
        );

        return back()->with('success', 'Ownership transferred.');
    }
}
