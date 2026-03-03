{{-- resources/views/colocations/show.blade.php --}}
<x-app-layout>
    <div x-data="{ 
        inviteModal: false, 
        deleteModal: false, 
        expenseModal: {{ 
            $errors->has('titre') || 
            $errors->has('description') || 
            $errors->has('montant') || 
            $errors->has('user_id') || 
            $errors->has('category_id') 
            ? 'true' : 'false' 
        }}, 
        categoryModal: false,
        paymentModal: false,
        selectedDebt: null
    }">
        <x-slot name="header">
            <div class="flex justify-between items-center bg-gradient-to-r from-purple-50 to-blue-50 dark:from-gray-800 dark:to-gray-900 px-6 py-4 rounded-lg">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200 transition-colors duration-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h2 class="font-bold text-2xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ $colocation->name }}
                    </h2>
                </div>

                @php
                $currentUser = auth()->user();
                $isOwner = $colocation->users->contains(function($user) {
                return $user->id === auth()->id() && $user->pivot->role === 'owner';
                });
                $currentUserBalance = $balances[$currentUser->id] ?? 0;
                $currentUserDebts = $debts->filter(function($debt) use ($currentUser) {
                return $debt->from_user_id === $currentUser->id;
                });
                $currentUserReceivables = $debts->filter(function($debt) use ($currentUser) {
                return $debt->to_user_id === $currentUser->id;
                });
                @endphp

                @if($isOwner)
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-lg">
                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                    </svg>
                    Propriétaire
                </span>
                @else
                <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-gradient-to-r from-blue-400 to-blue-500 text-white shadow-lg">
                    <svg class="h-4 w-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                    </svg>
                    Membre
                </span>
                @endif
            </div>
        </x-slot>

        @if(session('success'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="fixed top-5 right-5 bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('success') }}</span>
        </div>
        @endif

        @if(session('error'))
        <div
            x-data="{ show: true }"
            x-init="setTimeout(() => show = false, 5000)"
            x-show="show"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 transform scale-90"
            x-transition:enter-end="opacity-100 transform scale-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100 transform scale-100"
            x-transition:leave-end="opacity-0 transform scale-90"
            class="fixed top-5 right-5 bg-gradient-to-r from-red-500 to-red-600 text-white px-6 py-4 rounded-xl shadow-2xl z-50 flex items-center space-x-2">
            <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <span>{{ session('error') }}</span>
        </div>
        @endif

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                {{-- Description --}}
                @if($colocation->description)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl mb-6 transform hover:scale-[1.01] transition-all duration-300">
                    <div class="p-6 text-gray-700 dark:text-gray-300 border-l-4 border-blue-500 bg-gradient-to-r from-blue-50 to-transparent dark:from-gray-700">
                        <div class="flex items-start">
                            <svg class="h-5 w-5 text-blue-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <span>{{ $colocation->description }}</span>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    {{-- Total dépenses --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl transform hover:scale-105 transition-all duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-gradient-to-br from-blue-400 to-blue-600 rounded-xl p-3 shadow-lg">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Total dépenses</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ number_format($total, 2) }} Dh
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Membres --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl transform hover:scale-105 transition-all duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-gradient-to-br from-green-400 to-green-600 rounded-xl p-3 shadow-lg">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Membres</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $colocation->users->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mon solde --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl transform hover:scale-105 transition-all duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-gradient-to-br from-purple-400 to-purple-600 rounded-xl p-3 shadow-lg">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Mon solde</p>
                                    @php
                                    $currentBalance = $balances[auth()->id()] ?? 0;
                                    @endphp

                                    <p class="text-2xl font-bold {{ $currentBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                        {{ number_format($currentBalance, 2) }} Dh
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Réputation --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl transform hover:scale-105 transition-all duration-300">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-gradient-to-br from-yellow-400 to-yellow-600 rounded-xl p-3 shadow-lg">
                                    <svg class="h-8 w-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-500 dark:text-gray-400 uppercase tracking-wider">Ma réputation</p>
                                    <p class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                                        {{ $reputations[$currentUser->id]->reputation_score ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- My Debts Section --}}
                @if($currentUserDebts->count() > 0 || $currentUserReceivables->count() > 0)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl mb-6">
                    <div class="p-6">
                        <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                            <svg class="h-5 w-5 mr-2 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                            Mes transactions
                        </h3>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            {{-- What I owe --}}
                            @if($currentUserDebts->count() > 0)
                            <div class="bg-red-50 dark:bg-red-900/20 rounded-xl p-4">
                                <h4 class="text-sm font-semibold text-red-800 dark:text-red-300 mb-3 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 17h8m0 0V9m0 8l-8-8-4 4-6-6"></path>
                                    </svg>
                                    Je dois ({{ $currentUserDebts->count() }})
                                </h4>
                                <div class="space-y-2">
                                    @foreach($currentUserDebts as $debt)
                                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-3 rounded-lg">
                                        <div class="flex items-center space-x-2">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-red-400 to-red-500 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($debt->toUser->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    À {{ $debt->toUser->name }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($debt->created_at)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <p class="text-lg font-bold text-red-600">{{ number_format($debt->amount, 2) }} Dh</p>
                                            <button @click="selectedDebt = {{ $debt->id }}; paymentModal = true"
                                                class="text-xs bg-green-500 hover:bg-green-600 text-white px-2 py-1 rounded mt-1 transition-colors">
                                                Marquer payé
                                            </button>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif

                            {{-- What I'm owed --}}
                            @if($currentUserReceivables->count() > 0)
                            <div class="bg-green-50 dark:bg-green-900/20 rounded-xl p-4">
                                <h4 class="text-sm font-semibold text-green-800 dark:text-green-300 mb-3 flex items-center">
                                    <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                    </svg>
                                    On me doit ({{ $currentUserReceivables->count() }})
                                </h4>
                                <div class="space-y-2">
                                    @foreach($currentUserReceivables as $debt)
                                    <div class="flex items-center justify-between bg-white dark:bg-gray-800 p-3 rounded-lg">
                                        <div class="flex items-center space-x-2">
                                            <div class="h-8 w-8 rounded-full bg-gradient-to-r from-green-400 to-green-500 flex items-center justify-center text-white font-bold text-sm">
                                                {{ strtoupper(substr($debt->fromUser->name, 0, 1)) }}
                                            </div>
                                            <div>
                                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                    De {{ $debt->fromUser->name }}
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">
                                                    {{ \Carbon\Carbon::parse($debt->created_at)->format('d/m/Y') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div>
                                            <p class="text-lg font-bold text-green-600">{{ number_format($debt->amount, 2) }} Dh</p>
                                            <span class="text-xs text-gray-500">En attente</span>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endif

                {{-- Bouton Ajouter dépense et Invitation Link --}}
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                    <button @click="expenseModal = true"
                        class="bg-gradient-to-r from-green-500 to-green-600 hover:from-green-600 hover:to-green-700 text-white font-bold py-3 px-6 rounded-xl inline-flex items-center shadow-lg transform hover:scale-105 transition-all duration-200">
                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter une dépense
                    </button>

                    @if(session('invitation_url'))
                    <div
                        x-data="{ show: true }"
                        x-show="show"
                        x-transition:enter="transition ease-out duration-300"
                        x-transition:enter-start="opacity-0 transform translate-x-10"
                        x-transition:enter-end="opacity-100 transform translate-x-0"
                        class="bg-gradient-to-r from-blue-400 to-blue-400 text-white px-6 py-3 rounded-xl shadow-lg flex items-center space-x-3">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.828 10.172a4 4 0 00-5.656 0l-4 4a4 4 0 105.656 5.656l1.102-1.101m-.758-4.899a4 4 0 005.656 0l4-4a4 4 0 00-5.656-5.656l-1.1 1.1"></path>
                        </svg>
                        <span class="font-medium">Lien d'invitation:</span>
                        <code class="text-sm font-mono">
                            {{ session('invitation_url') }}
                        </code>
                        <button
                            onclick="navigator.clipboard.writeText('{{ session('invitation_url') }}')"
                            class="ml-2 hover:bg-yellow-500 p-1 rounded transition-colors duration-200"
                            title="Copier le lien">
                            <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path>
                            </svg>
                        </button>
                    </div>
                    @endif
                </div>

                {{-- Grille principale --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Colonne gauche : Membres --}}
                    <div class="lg:col-span-1 space-y-6">
                        {{-- Liste des membres --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="h-5 w-5 mr-2 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Members ({{ $colocation->users->count() }})
                                </h3>

                                {{-- Owner Section --}}
                                @if($owner)
                                <div class="mb-6">
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-yellow-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        Propriétaire
                                    </h4>
                                    <div class="flex items-center justify-between p-4 bg-gradient-to-r from-yellow-50 to-amber-50 dark:from-gray-700 dark:to-gray-800 rounded-xl border-l-4 border-yellow-500 shadow-sm">
                                        <div class="flex items-center space-x-3">
                                            <div class="flex-shrink-0">
                                                <div class="h-12 w-12 rounded-full bg-gradient-to-r from-yellow-500 to-amber-500 flex items-center justify-center text-white font-bold text-xl shadow-md">
                                                    {{ strtoupper(substr($owner->name, 0, 1)) }}
                                                </div>
                                            </div>
                                            <div>
                                                <div class="flex items-center flex-wrap gap-2">
                                                    <p class="text-base font-bold text-gray-900 dark:text-gray-100">
                                                        {{ $owner->name }}
                                                    </p>
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                        <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                        </svg>
                                                        Propriétaire
                                                    </span>
                                                    @if($owner->id === auth()->id())
                                                    <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300">Moi</span>
                                                    @endif
                                                </div>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                                    <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                    </svg>
                                                    Owner depuis {{ \Carbon\Carbon::parse($owner->pivot->joined_at ?? $owner->pivot->created_at)->format('d/m/Y') }}
                                                </p>
                                                @php
                                                $ownerBalance = $balances[$owner->id] ?? 0;
                                                @endphp

                                                <p class="text-2xl font-bold {{ $ownerBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($ownerBalance, 2) }} Dh
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                                @endif

                                {{-- Members Section --}}
                                @if($members->count() > 0)
                                <div>
                                    <h4 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-3 flex items-center">
                                        <svg class="h-4 w-4 mr-1 text-blue-500" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path>
                                        </svg>
                                        Membres ({{ $members->count() }})
                                    </h4>
                                    <div class="space-y-3">
                                        {{-- Members list with balances --}}
                                        @foreach($members as $user)
                                        @php
                                        // Get each user's balance from the balances array
                                        $userBalance = $balances[$user->id] ?? 0;
                                        $userReputation = $reputations[$user->id]->reputation_score ?? 0;
                                        @endphp
                                        <div class="flex items-center justify-between p-4 bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl hover:shadow-md transition-all duration-200">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-blue-400 to-blue-500 flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($user->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <div class="flex items-center flex-wrap gap-2">
                                                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                            {{ $user->name }}
                                                        </p>
                                                        @if($user->id === auth()->id())
                                                        <span class="text-xs px-2 py-0.5 rounded-full bg-gray-200 dark:bg-gray-600 text-gray-700 dark:text-gray-300">Moi</span>
                                                        @endif
                                                    </div>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 flex items-center">
                                                        <svg class="h-3 w-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                        </svg>
                                                        depuis {{ \Carbon\Carbon::parse($user->pivot->joined_at ?? $user->pivot->created_at)->format('d/m/Y') }}
                                                    </p>
                                                </div>
                                            </div>

                                            <div class="text-right">
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Solde</p>
                                                <p class="text-sm font-bold {{ $userBalance >= 0 ? 'text-green-600' : 'text-red-600' }}">
                                                    {{ number_format($userBalance, 2) }} Dh
                                                </p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">Réputation</p>
                                                <p class="text-sm font-bold {{ $userReputation >= 0 ? 'text-blue-600' : 'text-red-600' }}">
                                                    {{ $userReputation }}
                                                </p>
                                            </div>

                                            @if($isOwner && $user->id !== auth()->id())
                                            <div class="flex space-x-2 ml-4">
                                                {{-- Transfer Ownership Button --}}
                                                <form action="{{ route('colocation.transferOwner', [$colocation->id, $user->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir transférer la propriété à {{ $user->name }} ? Cette action est irréversible.');">
                                                    @csrf
                                                    @method('POST')
                                                    <button type="submit"
                                                        class="text-yellow-600 hover:text-yellow-900 bg-yellow-100 hover:bg-yellow-200 p-2 rounded-full transition-colors duration-200"
                                                        title="Transférer la propriété">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                                                        </svg>
                                                    </button>
                                                </form>

                                                {{-- Remove Member Button --}}
                                                <form action="{{ route('colocation.removeMember', [$colocation->id, $user->id]) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Êtes-vous sûr de vouloir retirer {{ $user->name }} de la colocation ?');">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-red-600 hover:text-red-900 bg-red-100 hover:bg-red-200 p-2 rounded-full transition-colors duration-200"
                                                        title="Retirer le membre">
                                                        <svg class="h-4 w-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                                        </svg>
                                                    </button>
                                                </form>
                                            </div>
                                            @endif
                                        </div>
                                        @endforeach
                                    </div>
                                </div>
                                @endif

                                {{-- If no members besides owner --}}
                                @if($members->count() === 0)
                                <div class="text-center py-6 bg-gray-50 dark:bg-gray-700 rounded-xl">
                                    <svg class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">Aucun autre membre pour le moment</p>
                                    @if($isOwner)
                                    <p class="text-sm text-gray-400 dark:text-gray-500 mt-2">Invitez des membres à rejoindre la colocation</p>
                                    @endif
                                </div>
                                @endif

                                @if($isOwner)
                                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button @click="inviteModal = true"
                                        class="w-full bg-gradient-to-r from-blue-500 to-blue-600 hover:from-blue-600 hover:to-blue-700 text-white font-bold py-3 px-4 rounded-xl inline-flex justify-center items-center shadow-md transform hover:scale-[1.02] transition-all duration-200">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                        </svg>
                                        Inviter un membre
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="h-5 w-5 mr-2 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Actions
                                </h3>

                                <div class="space-y-3">
                                    @if($isOwner)
                                    <button @click="categoryModal = true"
                                        class="w-full bg-gradient-to-r from-purple-500 to-purple-600 hover:from-purple-600 hover:to-purple-700 text-white font-bold py-3 px-4 rounded-xl inline-flex justify-center items-center shadow-md transform hover:scale-[1.02] transition-all duration-200">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 7h.01M7 3h5c.512 0 1.024.195 1.414.586l5 5a2 2 0 010 2.828l-7 7a2 2 0 01-2.828 0l-5-5A1.994 1.994 0 013 12V7a4 4 0 014-4z"></path>
                                        </svg>
                                        Gérer les catégories
                                    </button>
                                    @endif

                                    <button @click="deleteModal = true"
                                        class="w-full bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white font-bold py-3 px-4 rounded-xl inline-flex justify-center items-center shadow-md transform hover:scale-[1.02] transition-all duration-200">
                                        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            @if($isOwner)
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                            @else
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                            @endif
                                        </svg>
                                        {{ $isOwner ? 'Annuler la colocation' : 'Quitter la colocation' }}
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Colonne droite : Dépenses et dettes --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Qui doit à qui --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl">
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Dettes à régler
                                </h3>

                                @if($debts->count() > 0)
                                <div class="space-y-4">
                                    @foreach($debts as $debt)
                                    <div class="bg-gradient-to-r from-red-50 to-orange-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-4">
                                        <div class="flex items-center justify-between">
                                            <div class="flex items-center space-x-3">
                                                <div class="flex-shrink-0">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-red-400 to-red-500 flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($debt->fromUser->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div>
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $debt->fromUser->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">doit à</p>
                                                </div>
                                            </div>

                                            <div class="text-center">
                                                <p class="text-2xl font-bold text-red-600">{{ number_format($debt->amount, 2) }} Dh</p>
                                            </div>

                                            <div class="flex items-center space-x-3">
                                                <div class="text-right">
                                                    <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                                                        {{ $debt->toUser->name }}
                                                    </p>
                                                    <p class="text-xs text-gray-500 dark:text-gray-400">reçoit</p>
                                                </div>
                                                <div class="flex-shrink-0">
                                                    <div class="h-10 w-10 rounded-full bg-gradient-to-r from-green-400 to-green-500 flex items-center justify-center text-white font-bold">
                                                        {{ strtoupper(substr($debt->toUser->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                            </div>
                                        </div>
                                        @if($debt->from_user_id === auth()->id())
                                        <div class="mt-3 flex justify-end">
                                            <button @click="selectedDebt = {{ $debt->id }}; paymentModal = true"
                                                class="bg-green-500 hover:bg-green-600 text-white text-sm px-4 py-2 rounded-lg transition-colors">
                                                Marquer payé
                                            </button>
                                        </div>
                                        @endif
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="bg-gradient-to-r from-blue-50 to-indigo-50 dark:from-gray-700 dark:to-gray-800 rounded-xl p-8 text-center">
                                    <svg class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">Aucune dette en cours. Tout le monde est à jour !</p>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Dépenses récentes --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-lg sm:rounded-xl">
                            <div class="p-6">
                                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center gap-4 mb-6">
                                    <h3 class="text-lg font-bold text-gray-800 dark:text-gray-200 flex items-center">
                                        <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Dépenses récentes
                                    </h3>
                                </div>

                                @if($colocation->depences->count() > 0)
                                <div class="space-y-4">
                                    @foreach($colocation->depences->sortByDesc('created_at')->take(5) as $depence)
                                    @php
                                    $paidBy = $colocation->users->firstWhere('id', $depence->user_id);
                                    @endphp
                                    <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-4 hover:shadow-md transition-all duration-200">
                                        <div class="flex justify-between items-start">
                                            <div>
                                                <h4 class="font-semibold text-gray-900 dark:text-gray-100">{{ $depence->titre }}</h4>
                                                @if($depence->description)
                                                <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $depence->description }}</p>
                                                @endif
                                                <div class="flex items-center mt-2 text-xs text-gray-500 dark:text-gray-400">
                                                    <span>Payé par {{ $paidBy->name ?? 'Utilisateur inconnu' }}</span>
                                                    @if($depence->category)
                                                    <span class="mx-2">•</span>
                                                    <span class="px-2 py-0.5 bg-purple-100 dark:bg-purple-900 text-purple-800 dark:text-purple-300 rounded-full">
                                                        {{ $depence->category->name }}
                                                    </span>
                                                    @endif
                                                    <span class="mx-2">•</span>
                                                    <span>{{ \Carbon\Carbon::parse($depence->created_at)->format('d/m/Y') }}</span>
                                                </div>
                                            </div>
                                            <div class="text-right">
                                                <p class="text-xl font-bold text-blue-600 dark:text-blue-400">{{ number_format($depence->montant, 2) }} Dh</p>
                                                <p class="text-xs text-gray-500 dark:text-gray-400">Part: {{ number_format($depence->montant / $colocation->users->count(), 2) }} Dh</p>
                                                @if($depence->user_id === auth()->id())
                                                <form action="{{ route('depences.destroy', $depence) }}"
                                                    method="POST"
                                                    onsubmit="return confirm('Supprimer cette dépense ?')"
                                                    class="mt-2">
                                                    @csrf
                                                    @method('DELETE')
                                                    <button type="submit"
                                                        class="text-xs text-red-500 hover:text-red-700 font-semibold">
                                                        Supprimer
                                                    </button>
                                                </form>
                                                @endif
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                                @else
                                <div class="bg-gradient-to-r from-gray-50 to-gray-100 dark:from-gray-700 dark:to-gray-800 rounded-xl p-8 text-center">
                                    <svg class="h-12 w-12 mx-auto text-gray-400 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    <p class="text-gray-500 dark:text-gray-400">Aucune dépense pour le moment.</p>
                                    <button @click="expenseModal = true" class="mt-4 text-blue-500 hover:text-blue-700 text-sm font-medium">
                                        Ajouter une première dépense
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL INVITATION --}}
        <div x-show="inviteModal"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            x-cloak>
            <div @click.away="inviteModal = false"
                class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                    Inviter un membre
                </h2>

                <form method="POST" action="{{ route('invitation.send', $colocation->id) }}">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">
                            Email du membre
                        </label>
                        <input type="email"
                            name="email"
                            required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                            @click="inviteModal = false"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </button>
                        <button type="submit"
                            class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Envoyer l'invitation
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL DÉPENSE --}}
        <div x-show="expenseModal"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            x-cloak>
            <div @click.away="expenseModal = false"
                class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                    Ajouter une dépense
                </h2>

                <form action="{{ route('depences.store', $colocation->id) }}" method="POST">
                    @csrf
                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Titre</label>
                        <input type="text" name="titre"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ old('titre') }}">
                        @error('titre')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Description</label>
                        <textarea name="description" rows="3"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">{{ old('description') }}</textarea>
                        @error('description')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Payé par</label>
                        <select name="user_id"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Sélectionner la personne qui a payé</option>
                            @foreach($colocation->users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id', auth()->id()) == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} @if($user->pivot->role === 'owner')(Propriétaire)@endif
                            </option>
                            @endforeach
                        </select>
                        @error('user_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Montant (Dh)</label>
                        <input type="number" step="0.01" name="montant"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"
                            value="{{ old('montant') }}">
                        @error('montant')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Catégorie</label>
                        <select name="category_id"
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                            <option value="">Sélectionner une catégorie (optionnel)</option>
                            @if($colocation->categories && $colocation->categories->count() > 0)
                            @foreach($colocation->categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id') == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                            @endforeach
                            @else
                            <option value="" disabled>Aucune catégorie disponible</option>
                            @endif
                        </select>
                        @if((!$colocation->categories || $colocation->categories->count() === 0) && $isOwner)
                        <p class="text-xs text-yellow-600 mt-1">
                            Vous n'avez pas encore de catégorie.
                            <button type="button" @click="categoryModal = true; expenseModal = false" class="text-purple-600 underline">
                                Ajoutez-en une
                            </button>
                        </p>
                        @endif
                        @error('category_id')
                        <p class="text-red-500 text-xs mt-1">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                            @click="expenseModal = false"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </button>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Ajouter
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL PAIEMENT --}}
        <div x-show="paymentModal"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            x-cloak>
            <div @click.away="paymentModal = false"
                class="bg-white dark:bg-gray-800 w-full max-w-md rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                    Confirmer le paiement
                </h2>

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    Êtes-vous sûr d'avoir effectué ce paiement ?
                </p>

                <form action="{{ route('settlements.mark-paid', $colocation->id) }}" method="POST">
                    @csrf
                    <input type="hidden" name="debt_id" x-model="selectedDebt">

                    <div class="flex justify-end space-x-3">
                        <button type="button"
                            @click="paymentModal = false"
                            class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                            Annuler
                        </button>
                        <button type="submit"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Confirmer le paiement
                        </button>
                    </div>
                </form>
            </div>
        </div>

        {{-- MODAL CONFIRMATION SUPPRESSION --}}
        <div x-show="deleteModal"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            x-cloak>
            <div @click.away="deleteModal = false"
                class="bg-white dark:bg-gray-800 w-full max-w-md rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                    Confirmation
                </h2>

                <p class="text-gray-700 dark:text-gray-300 mb-6">
                    @if($isOwner)
                    Êtes-vous sûr de vouloir annuler cette colocation ? Cette action est irréversible.
                    @else
                    Êtes-vous sûr de vouloir quitter cette colocation ?
                    @endif
                </p>

                <div class="flex justify-end space-x-3">
                    <button type="button"
                        @click="deleteModal = false"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Non, annuler
                    </button>
                    <form action="{{ $isOwner ? route('colocation.cancel', $colocation) : route('colocation.leave', $colocation) }}"
                        method="POST">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            Oui, {{ $isOwner ? 'annuler' : 'quitter' }}
                        </button>
                    </form>
                </div>
            </div>
        </div>

        {{-- MODAL CATÉGORIE (Owner only) --}}
        @if($isOwner)
        <div x-show="categoryModal"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50"
            x-cloak>
            <div @click.away="categoryModal = false"
                class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow-lg p-6">

                <div class="flex justify-between items-center mb-4">
                    <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                        Gérer les catégories
                    </h2>
                    <button @click="categoryModal = false" class="text-gray-500 hover:text-gray-700">
                        <svg class="h-6 w-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <form action="{{ route('categories.store', $colocation->id) }}" method="POST">
                    @csrf
                    <div class="mb-6 flex items-center space-x-2">
                        <input type="text"
                            name="name"
                            placeholder="Nom de la catégorie"
                            required
                            class="flex-1 rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-purple-500 focus:ring-purple-500">
                        <button type="submit"
                            class="bg-purple-500 hover:bg-purple-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                            <svg class="h-4 w-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                            </svg>
                            Ajouter
                        </button>
                    </div>
                </form>

                <h3 class="text-md font-semibold text-gray-700 dark:text-gray-300 mb-3">
                    Catégories existantes
                </h3>

                @if($colocation->categories && $colocation->categories->count() > 0)
                <div class="space-y-2 max-h-60 overflow-y-auto">
                    @foreach($colocation->categories as $category)
                    <div class="flex items-center justify-between p-3 bg-gray-50 dark:bg-gray-700 rounded-lg">
                        <span class="text-sm font-medium text-gray-900 dark:text-gray-100">
                            {{ $category->name }}
                        </span>
                        <form action="{{ route('categories.destroy', [$colocation->id, $category->id]) }}"
                            method="POST"
                            onsubmit="return confirm('Supprimer cette catégorie ?');">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="text-red-600 hover:text-red-900">
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                </svg>
                            </button>
                        </form>
                    </div>
                    @endforeach
                </div>
                @else
                <p class="text-gray-500 dark:text-gray-400 text-center py-4">
                    Aucune catégorie pour le moment.
                </p>
                @endif

                <div class="mt-6 flex justify-end">
                    <button type="button"
                        @click="categoryModal = false"
                        class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded">
                        Fermer
                    </button>
                </div>
            </div>
        </div>
        @endif

    </div>
</x-app-layout>