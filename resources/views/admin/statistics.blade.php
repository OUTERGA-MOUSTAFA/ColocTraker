<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with gradient -->
            <div class="mb-8">
                <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                    Statistiques Globales
                </h2>
                <p class="text-gray-600 mt-2">Aperçu complet de votre plateforme en temps réel</p>
            </div>

            <!-- Stats Cards with Icons and Gradients -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                <!-- Total Users Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Utilisateurs Total</p>
                            <p class="text-4xl font-bold text-gray-800 mt-2">{{ $totalUsers }}</p>
                            <p class="text-green-600 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                +12% ce mois
                            </p>
                        </div>
                        <div class="bg-indigo-100 p-4 rounded-2xl">
                            <svg class="w-8 h-8 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Active Colocations Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Colocations Actives</p>
                            <p class="text-4xl font-bold text-gray-800 mt-2">{{ $activeColocations }}</p>
                            <p class="text-green-600 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                </svg>
                                +5 nouvelles
                            </p>
                        </div>
                        <div class="bg-green-100 p-4 rounded-2xl">
                            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                            </svg>
                        </div>
                    </div>
                </div>

                <!-- Banned Users Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 transform hover:scale-105 transition-transform duration-300 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm font-medium">Utilisateurs Bannis</p>
                            <p class="text-4xl font-bold text-red-600 mt-2">{{ $bannedUsers }}</p>
                            <p class="text-red-600 text-sm mt-2 flex items-center">
                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                -2 cette semaine
                            </p>
                        </div>
                        <div class="bg-red-100 p-4 rounded-2xl">
                            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Expenses and Categories Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                <!-- Total Expenses Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <div class="flex items-center justify-between mb-4">
                        <h3 class="text-lg font-semibold text-gray-800">Total Dépenses</h3>
                        <span class="bg-blue-100 text-blue-800 text-xs font-medium px-3 py-1 rounded-full">Ce mois</span>
                    </div>
                    <div class="flex items-baseline">
                        <p class="text-4xl font-bold text-gray-800">{{ number_format($totalExpenses, 2) }}</p>
                        <span class="ml-2 text-gray-600">MAD</span>
                    </div>
                    <div class="flex items-center mt-4 text-sm text-gray-600">
                        <svg class="w-5 h-5 mr-2 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 14h.01M12 14h.01M15 11h.01M12 11h.01M9 11h.01M7 21h10a2 2 0 002-2V5a2 2 0 00-2-2H7a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        {{ $expensesCount }} dépenses enregistrées
                    </div>
                </div>

                <!-- Top Categories Card -->
                <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                    <h3 class="text-lg font-semibold text-gray-800 mb-4">Top 3 Catégories</h3>
                    @if($topCategories->isEmpty())
                        <div class="text-center py-8 text-gray-500">
                            <svg class="w-12 h-12 mx-auto mb-2 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                            </svg>
                            <p class="text-sm">Aucune catégorie avec dépenses</p>
                        </div>
                    @else
                    <div class="space-y-4">
                        @foreach($topCategories as $index => $category)
                            <div class="flex items-center">
                                <div class="flex-1">
                                    <div class="flex justify-between mb-1">
                                        <span class="text-sm font-medium text-gray-700">{{ $category->name }}</span>
                                        <span class="text-sm font-semibold text-gray-900">{{ number_format($category->depences_sum_montant ?? 0, 2) }} MAD</span>
                                    </div>
                                    <div class="w-full bg-gray-200 rounded-full h-2">
                                        @php
                                            $maxAmount = $topCategories->max('depences_sum_montant') ?? 1;
                                            $percentage = ($category->depences_sum_montant ?? 0) / $maxAmount * 100;
                                        @endphp
                                        <div class="bg-gradient-to-r from-indigo-500 to-purple-500 h-2 rounded-full" style="width: {{ $percentage }}%"></div>
                                    </div>
                                </div>
                                <div class="ml-4">
                                    <span class="inline-flex items-center justify-center w-8 h-8 rounded-full {{ $index == 0 ? 'bg-yellow-100 text-yellow-600' : ($index == 1 ? 'bg-gray-100 text-gray-600' : 'bg-orange-100 text-orange-600') }}">
                                        @if($index == 0)
                                            🥇
                                        @elseif($index == 1)
                                            🥈
                                        @else
                                            🥉
                                        @endif
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    @endif
                </div>
            </div>

            <!-- Monthly Expenses Table -->
            <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-semibold text-gray-800">Dépenses Mensuelles</h3>
                    <div class="flex space-x-2">
                        <button class="px-3 py-1 text-sm bg-gray-100 text-gray-600 rounded-lg hover:bg-gray-200 transition-colors">
                            Cette année
                        </button>
                        <button class="px-3 py-1 text-sm bg-indigo-50 text-indigo-600 rounded-lg hover:bg-indigo-100 transition-colors">
                            Exporter
                        </button>
                    </div>
                </div>
                
                <div class="overflow-x-auto">
                    @if($monthlyExpenses->isEmpty())
                        <div class="text-center py-12 text-gray-500">
                            <svg class="w-16 h-16 mx-auto mb-4 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                            </svg>
                            <p>Aucune dépense enregistrée</p>
                        </div>
                    @else
                    <table class="min-w-full">
                        <thead>
                            <tr class="border-b-2 border-gray-100">
                                <th class="text-left py-3 text-sm font-semibold text-gray-600">Mois</th>
                                <th class="text-right py-3 text-sm font-semibold text-gray-600">Nombre de dépenses</th>
                                <th class="text-right py-3 text-sm font-semibold text-gray-600">Total</th>
                                <th class="text-right py-3 text-sm font-semibold text-gray-600">Évolution</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($monthlyExpenses as $index => $expense)
                                <tr class="border-b border-gray-50 hover:bg-gray-50 transition-colors">
                                    <td class="py-4">
                                        <div class="flex items-center">
                                            <div class="w-2 h-2 rounded-full bg-indigo-500 mr-3"></div>
                                            <span class="font-medium text-gray-800">{{ $expense->month }}/{{ $expense->year }}</span>
                                        </div>
                                    </td>
                                    <td class="text-right py-4">
                                        <span class="px-3 py-1 bg-gray-100 text-gray-700 rounded-full text-sm">
                                            {{ $expense->count }}
                                        </span>
                                    </td>
                                    <td class="text-right py-4 font-semibold text-gray-800">
                                        {{ number_format($expense->total, 2) }} MAD
                                    </td>
                                    <td class="text-right py-4">
                                        @if($index > 0)
                                            @php
                                                $previousTotal = $monthlyExpenses[$index - 1]->total;
                                                $evolution = $previousTotal > 0 ? (($expense->total - $previousTotal) / $previousTotal * 100) : 0;
                                            @endphp
                                            @if($evolution > 0)
                                                <span class="text-green-600 text-sm flex items-center justify-end">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 10l7-7m0 0l7 7m-7-7v18"></path>
                                                    </svg>
                                                    +{{ number_format($evolution, 1) }}%
                                                </span>
                                            @elseif($evolution < 0)
                                                <span class="text-red-600 text-sm flex items-center justify-end">
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 14l-7 7m0 0l-7-7m7 7V3"></path>
                                                    </svg>
                                                    {{ number_format($evolution, 1) }}%
                                                </span>
                                            @else
                                                <span class="text-gray-400 text-sm">-</span>
                                            @endif
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>