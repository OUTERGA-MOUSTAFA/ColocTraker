<?php

namespace App\Services;

use App\Models\User;
use App\Models\Colocation;

class ReputationService
{
    public function handleLeaving(User $user, Colocation $colocation)
    {
        $hasDebt = app(BalanceService::class)
                    ->hasDebt($user, $colocation);

        if ($hasDebt) {
            $user->decrement('reputation_score');
        } else {
            $user->increment('reputation_score');
        }
    }
}