<?php

namespace App\Services;

use App\Models\User;
use App\Models\Colocation;

class BalanceService
{
    public function calculateBalance(User $user, Colocation $colocation): float
    {
        $depences = $colocation->depences;

        $activeUsers = $colocation->users()
            ->wherePivotNull('left_at')
            ->get();

        $totalPaid = $depences
            ->where('user_id', $user->id)
            ->sum('montant');

        $totalAmount = $depences->sum('montant');

        $membersCount = max($activeUsers->count(), 1);

        $individualShare = $totalAmount / $membersCount;

        return round($totalPaid - $individualShare, 2);
    }

    public function getColocationBalances(Colocation $colocation)
    {
        $users = $colocation->users;
        $depences = $colocation->depences;

        $membersCount = $users->count();
        $total = $depences->sum('montant');
        $share = $membersCount > 0 ? $total / $membersCount : 0;

        $balances = [];

        foreach ($users as $user) {

            $paidAmount = $depences
                ->where('user_id', $user->id)
                ->sum('montant');

            $balance = round($paidAmount - $share, 2);

            $balances[$user->id] = $balance;
        }

        // correct calcule from settelments
        $settlements = $colocation->settlements()
            ->where('is_paid', false)
            ->get();

        foreach ($settlements as $settlement) {

            if (!isset($balances[$settlement->from_user_id])) {
                $balances[$settlement->from_user_id] = 0;
            }

            if (!isset($balances[$settlement->to_user_id])) {
                $balances[$settlement->to_user_id] = 0;
            }

            // i will give your money
            $balances[$settlement->from_user_id] += $settlement->amount;

            // give me my money
            $balances[$settlement->to_user_id] -= $settlement->amount;
        }
        
        $transactions = $this->simplifydettes($balances);
        return [
            'membersCount' => $membersCount,
            'total' => $total,
            'share' => $share,
            'balances' => $balances,
            'transactions' => $transactions,
        ];
    }
    private function simplifydettes(array $balances)
    {
        $creditors = [];
        $debtors = [];
        $transactions = [];

        foreach ($balances as $userId => $balance) {
            if ($balance > 0) {
                $creditors[] = ['id' => $userId, 'amount' => $balance];
            } elseif ($balance < 0) {
                $debtors[] = ['id' => $userId, 'amount' => abs($balance)];
            }
        }

        $i = 0;
        $j = 0;

        while ($i < count($debtors) && $j < count($creditors)) {

            $debtAmount = $debtors[$i]['amount'];
            $creditAmount = $creditors[$j]['amount'];

            $amount = min($debtAmount, $creditAmount);

            $transactions[] = [
                'from' => $debtors[$i]['id'],
                'to' => $creditors[$j]['id'],
                'amount' => round($amount, 2),
            ];

            $debtors[$i]['amount'] -= $amount;
            $creditors[$j]['amount'] -= $amount;

            if ($debtors[$i]['amount'] == 0) $i++;
            if ($creditors[$j]['amount'] == 0) $j++;
        }

        return $transactions;
    }
}
