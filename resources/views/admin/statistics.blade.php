<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">Statistiques Globales</h2>

            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm">Utilisateurs Total</h3>
                    <p class="text-3xl font-bold">{{ $totalUsers }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm">Colocations Actives</h3>
                    <p class="text-3xl font-bold">{{ $activeColocations }}</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm">Utilisateurs Bannis</h3>
                    <p class="text-3xl font-bold text-red-600">{{ $bannedUsers }}</p>
                </div>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-gray-500 text-sm">Total Dépenses</h3>
                    <p class="text-3xl font-bold">{{ number_format($totalExpenses, 2) }} MAD</p>
                    <p class="text-sm text-gray-500 mt-2">{{ $expensesCount }} dépenses</p>
                </div>
                <div class="bg-white rounded-lg shadow p-6">
                    <h3 class="text-lg font-semibold mb-4">Top 3 Catégories</h3>
                    @foreach($topCategories as $category)
                        <div class="flex justify-between mb-2">
                            <span>{{ $category->name }}</span>
                            <span class="font-semibold">{{ number_format($category->depences_sum_montant ?? 0, 2) }} MAD</span>
                        </div>
                    @endforeach
                </div>
            </div>

            <div class="bg-white rounded-lg shadow p-6">
                <h3 class="text-lg font-semibold mb-4">Dépenses Mensuelles</h3>
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left py-2">Mois</th>
                                <th class="text-right py-2">Nombre</th>
                                <th class="text-right py-2">Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyExpenses as $expense)
                                <tr class="border-b">
                                    <td class="py-2">{{ $expense->month }}/{{ $expense->year }}</td>
                                    <td class="text-right">{{ $expense->count }}</td>
                                    <td class="text-right">{{ number_format($expense->total, 2) }} MAD</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
