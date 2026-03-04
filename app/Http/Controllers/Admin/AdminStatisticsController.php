<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Colocation;
use App\Models\Depence;
use App\Models\User;
use App\Models\Categories;
use Illuminate\Support\Facades\DB;

class AdminStatisticsController extends Controller
{
    public function index()
    {
        $totalUsers = User::count();
        $bannedUsers = User::where('is_banned', true)->count();

        $activeColocations = Colocation::whereHas('users', function ($q) {
            $q->whereNull('left_at');
        })->count();

        $totalExpenses = Depence::sum('montant');
        $expensesCount = Depence::count();

        $topCategories = Categories::withSum('depences', 'montant')
            ->has('depences')
            ->orderByDesc('depences_sum_montant')
            ->take(3)
            ->get();

        $monthlyExpenses = Depence::select(
            DB::raw('EXTRACT(YEAR FROM created_at) as year'),
            DB::raw('EXTRACT(MONTH FROM created_at) as month'),
            DB::raw('COUNT(*) as count'),
            DB::raw('SUM(montant) as total')
        )
            ->groupBy('year', 'month')
            ->orderBy('year', 'asc')
            ->orderBy('month', 'asc')
            ->take(12)
            ->get()
            ->reverse()
            ->values();

        return view('admin.statistics', compact(
            'totalUsers',
            'bannedUsers',
            'activeColocations',
            'totalExpenses',
            'expensesCount',
            'topCategories',
            'monthlyExpenses'
        ));
    }
}
