<?php

namespace App\Services;

use App\Models\User;
use App\Models\Colocation;

class BalanceService
{
    public function hasDebt(User $user, Colocation $colocation): bool
    {
        return $this->calculateBalance($user, $colocation) < 0;
    }

    public function calculateBalance(User $user, Colocation $colocation): float
    {
        // relations خاصها تكون already loaded
        $depences = $colocation->depences;
        $activeUsers = $colocation->users;

        $totalPaid = $depences
            ->where('user_id', $user->id)
            ->sum('montant');

        $totalAmount = $depences->sum('montant');

        $membersCount = max($activeUsers->count(), 1);

        $individualShare = $totalAmount / $membersCount;

        return round($totalPaid - $individualShare, 2);
    }

    public function getColocationBalances(Colocation $colocation): array
    {
        // نفترض users + depences loaded من controller

        $depences = $colocation->depences;
        $activeUsers = $colocation->users;

        $total = $depences->sum('montant');

        $membersCount = max($activeUsers->count(), 1);

        $share = $membersCount > 0
            ? round($total / $membersCount, 2)
            : 0;

        $balances = $activeUsers->map(function ($user) use ($colocation) {

            $balance = $this->calculateBalance($user, $colocation);

            return [
                'user'    => $user,
                'paid'    => $colocation->depences
                                    ->where('user_id', $user->id)
                                    ->sum('montant'),
                'share'   => $colocation->depences->sum('montant') / 
                             max($colocation->users->count(), 1),
                'balance' => $balance,
            ];
        });

        return [
            'membersCount' => $membersCount,
            'total'        => $total,
            'share'        => $share,
            'balances'     => $balances,
        ];
    }
}