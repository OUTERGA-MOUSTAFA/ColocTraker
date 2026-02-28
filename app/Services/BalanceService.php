<?php

namespace App\Services;

use App\Models\User;
use App\Models\Colocation;

class BalanceService
{
    public function hasDebt(User $user, Colocation $colocation): bool
    {
        $balance = $this->calculateBalance($user, $colocation);

        return $balance < 0;
    }

    public function calculateBalance(User $user, Colocation $colocation): float
    {
        $depences = $colocation->depences;

        $totalPaid = $depences
            ->where('user_id', $user->id)
            ->sum('montant');

        $totalAmount = $depences->sum('montant');

        // $membersCount = $colocation->users()
        //     ->wherePivotNull('left_at')
        //     ->count();
        //$membersCount = max($colocation->users->count(), 1);
        $membersCount = max( $colocation->users()->whereNull('colocation_user.left_at')->count(),1);// just for errors and 

        if ($membersCount === 0) return 0;

        $individualShare = $totalAmount / $membersCount;

        return $totalPaid - $individualShare;
    }

    public function getColocationBalances(Colocation $colocation): array
{
    $colocation->load([
        'depences',
        'users' => function ($query) {
            $query->wherePivotNull('left_at');
        }
    ]);

    $total = $colocation->depences->sum('montant');

    $membersCount = max($colocation->users->count(), 1);

    $share = $total / $membersCount;

    $balances = $colocation->users->map(function ($user) use ($colocation, $share) {

        $paid = $colocation->depences
            ->where('user_id', $user->id)
            ->sum('montant');

        return [
            'user'    => $user,
            'paid'    => $paid,
            'share'   => $share,
            'balance' => $paid - $share,
        ];
    });

    return [
        'membersCount' => $membersCount,
        'total'    => $total,
        'share'    => $share,
        'balances' => $balances,
    ];
}
}
