<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\models\User;
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

    public function show($id, BalanceService $balanceService, ReputationService $reputation)
    {
        $colocation = Colocation::whereHas('users', function ($q) {
            $q->where('user_id', auth()->id())
                ->whereNull('colocation_user.left_at');
        })->with([
            'depences',
            'users' => function ($q) {
                $q->wherePivotNull('left_at');
            }
        ])->findOrFail($id);

        $data = $balanceService->getColocationBalances($colocation);

        $total = $data['total'] ?? 0;
        $share = $data['share'] ?? 0;
        $balances = $data['balances'] ?? [];


        $transactions = $data['transactions'];

        $reputations = $reputation
            ->getReputationUsers($colocation)
            ->keyBy('user_id');

        $owner  = $colocation->users->where('pivot.role', 'owner')->first();
        $members = $colocation->users->where('pivot.role', 'member');

        $activeUserIds = $colocation->users->pluck('id');

        $debts = Settlement::where('colocation_id', $colocation->id)
            ->where('is_paid', false)
            ->whereIn('from_user_id', $activeUserIds)
            ->whereIn('to_user_id', $activeUserIds)
            ->with(['fromUser', 'toUser'])
            ->get();

        return view('colocation.index', [
            'colocation' => $colocation,
            'owner' => $owner,
            'members' => $members,
            'balances' => $balances,
            'reputations' => $reputations,
            'total' => $total,
            'share' => $share,
            'debts' => $debts,
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

    public function removeMember($colocationId, $userId)
    {
        $colocation = Colocation::findOrFail($colocationId);
        $user = User::findOrFail($userId);

        // Check if current user is owner
        if (auth()->user()->id !== $colocation->users()->wherePivot('role', 'owner')->first()->id) {
            return redirect()->back()->with('error', 'Seul le propriétaire peut retirer des membres.');
        }

        // Check if user is not the owner
        $pivot = $colocation->users()->where('user_id', $userId)->first();
        if ($pivot && $pivot->pivot->role === 'owner') {
            return redirect()->back()->with('error', 'Impossible de retirer le propriétaire.');
        }

        // Check reputation before removing
        $balanceService = app(BalanceService::class);
        $hasDebt = $balanceService->hasDebt($user, $colocation);

        $balance = $balanceService->calculateBalance($user, $colocation);

        if ($balance < 0) {

            $debtAmount = abs($balance);

            // نجيب owner
            $idOwner = auth()->id();

            // نخلق depence جديدة باسم owner
            $colocation->depences()->create([
                'user_id' => $idOwner,
                'titre' => 'Debt adjustment for removed member',
                'description' => 'Debt transferred from ' . $user->name,
                'montant' => $debtAmount,
            ]);
        }
        // Remove user from colocation
        //$colocation->users()->detach($userId);
        // update pivot
        $colocation->users()->updateExistingPivot($user->id, [
            'left_at' => now()
        ]);

        // Update reputation if they had debt
        if ($hasDebt) {
            $user->decrement('reputation_score');
        }

        return redirect()->route('colocation.show', $colocation->id)
            ->with('success', 'Le membre a été retiré de la colocation.');
    }
}
