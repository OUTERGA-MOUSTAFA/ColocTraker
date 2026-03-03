<?php

namespace App\Http\Controllers;

use App\Models\Colocation;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Settlement;
use App\models\User;
use App\Services\BalanceService;
use App\Services\ReputationService;
use Illuminate\Support\Facades\DB;

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
        ])->where('id', $id)->first();

        if (!$colocation) {
        return redirect()->route('dashboard')
            ->with('error', 'You are not a member of this colocation.');
    }

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

    public function transferOwnership($colocationId, $newOwnerId)
    {
        $colocation = Colocation::findOrFail($colocationId);

        // check if current user is owner
        $currentOwner = $colocation->users()
            ->wherePivot('user_id', auth()->id())
            ->wherePivotNull('left_at')
            ->first();

        if (!$currentOwner || $currentOwner->pivot->role !== 'owner') {
            abort(403);
        }

        // check new owner is active member
        $newOwner = $colocation->users()
            ->wherePivot('user_id', $newOwnerId)
            ->wherePivotNull('left_at')
            ->first();

        if (!$newOwner) {
            return back()->with('error', 'User not valid.');
        }

        DB::transaction(function () use ($colocation, $newOwnerId,$currentOwner) {

            // reset all owners to member
            if ($currentOwner) {
                $colocation->users()->updateExistingPivot(
                    $currentOwner->id,
                    ['role' => 'member']
                );
            }

            // عيّن owner الجديد
            $colocation->users()->updateExistingPivot(
                $newOwnerId,
                ['role' => 'owner']
            );
        });

        return back()->with('success', 'Ownership transferred.');
    }

    public function removeMember($colocationId, $userId)
    {
        $colocation = Colocation::with(['users', 'depences'])->findOrFail($colocationId);
        $user = User::findOrFail($userId);

        // Policy already checks owner, safety
        abort_unless(
            $colocation->users()
                ->where('user_id', auth()->id())
                ->wherePivot('role', 'owner')
                ->exists(),
            403
        );


        $pivot = $colocation->users()->where('user_id', $userId)->first();
        if ($pivot && $pivot->pivot->role === 'owner') {
            return back()->with('error', 'Impossible de retirer le propriétaire.');
        }

        $balanceService = app(BalanceService::class);
        $data = $balanceService->getColocationBalances($colocation);
        $balances = $data['balances'] ?? [];

        $balance = $balances[$user->id] ?? 0;

        // check if member has dettes
        if ($balance < 0) {

            $debtAmount = abs($balance);
            $ownerId = auth()->id();

            // transfer dettes to owner
            Settlement::create([
                'colocation_id' => $colocation->id,
                'from_user_id'  => $ownerId,
                'to_user_id'    => $user->id,
                'amount'        => $debtAmount,
                'is_paid'       => false,
            ]);
        }

        // نحيد member (soft leave)
        $colocation->users()->updateExistingPivot($user->id, [
            'left_at' => now()
        ]);

        return redirect()
            ->route('colocation.show', $colocation->id)
            ->with('success', 'Le membre a été retiré de la colocation.');
    }
}
