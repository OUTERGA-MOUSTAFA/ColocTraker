{{-- resources/views/colocations/show.blade.php --}}
<x-app-layout>
    <div x-data="{ inviteModal: false, deleteModal: false, expenseModal: false }">
        <x-slot name="header">
            <div class="flex justify-between items-center">
                <div class="flex items-center">
                    <a href="{{ route('dashboard') }}" class="mr-4 text-gray-600 hover:text-gray-900 dark:text-gray-400 dark:hover:text-gray-200">
                        <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                    </a>
                    <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                        {{ $colocation->name }}
                    </h2>
                    @if(session('invitation_url'))
                    <div class="bg-green-100 p-3 rounded">
                        <p>Invitation Link:</p>
                        <input type="text" value="{{ session('invitation_url') }}" readonly class="w-full border p-2">
                    </div>
                    @endif
                </div>

                @php
                $isOwner = $colocation->users->contains(function($user) {
                return $user->id === auth()->id() && $user->pivot->role === 'owner';
                });
                @endphp

                @if($isOwner)
                <span class="bg-yellow-100 text-yellow-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-yellow-900 dark:text-yellow-300">
                    Propriétaire
                </span>
                @else
                <span class="bg-blue-100 text-blue-800 text-xs font-medium px-2.5 py-0.5 rounded dark:bg-blue-900 dark:text-blue-300">
                    Membre
                </span>
                @endif
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

                {{-- Description --}}
                @if($colocation->description)
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg mb-6">
                    <div class="p-4 text-gray-700 dark:text-gray-300">
                        {{ $colocation->description }}
                    </div>
                </div>
                @endif

                {{-- Stats Cards --}}
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-6">
                    {{-- Total dépenses --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-100 rounded-lg p-3">
                                    <svg class="h-8 w-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Total dépenses</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                        2$
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Membres --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-100 rounded-lg p-3">
                                    <svg class="h-8 w-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Membres</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                        {{ $colocation->users->where('pivot.role', 'member')->count() }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Mon solde --}}

                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0  rounded-lg p-3">
                                    <svg class="h-8 w-8 " fill="none" stroke="currentColor" viewBox="0 0 24 24">

                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Mon solde</p>
                                    <p class="text-2xl font-semibold">

                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Réputation --}}
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                        <div class="p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-yellow-100 rounded-lg p-3">
                                    <svg class="h-8 w-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                                    </svg>
                                </div>
                                <div class="ml-4">
                                    <p class="text-sm font-medium text-gray-600 dark:text-gray-400">Ma réputation</p>
                                    <p class="text-2xl font-semibold text-gray-900 dark:text-gray-100">
                                        {{ auth()->user()->reputation_score ?? 0 }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Bouton Ajouter dépense --}}
                <div class="flex justify-end mb-6">
                    <button @click="expenseModal = true"
                        class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded inline-flex items-center">
                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Ajouter une dépense
                    </button>
                </div>

                {{-- Grille principale --}}
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    {{-- Colonne gauche : Membres --}}
                    <div class="lg:col-span-1 space-y-6">
                        {{-- Liste des membres --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">

                                <div class="bg-gray-200 dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                                    <div class="p-6">
                                        {{-- En-tête avec le nombre total de membres --}}
                                        <div class="flex items-center justify-between mb-4">

                                            {{-- Badge Propriétaire --}}
                                            @php
                                            $owner = $colocation->users->firstWhere('pivot.role', 'owner');
                                            @endphp

                                        </div>

                                        {{-- Liste des membres avec distinction --}}
                                        <div class="mt-4 space-y-3">
                                            @foreach($colocation->users as $user)
                                            <div class="flex items-center justify-between p-3 bg-gray-{{ $user->pivot->role === 'owner' ? '100' : '50' }} dark:bg-gray-700 rounded-lg">
                                                <div class="flex items-center">
                                                    {{-- Informations utilisateur --}}
                                                    <div class="ml-3">
                                                        <div class="flex items-center">
                                                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">
                                                                {{ $user->name }}
                                                            </p>

                                                            {{-- Badge rôle --}}
                                                            @if($user->pivot->role === 'owner')
                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-300">
                                                                <svg class="h-3 w-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                                Propriétaire
                                                            </span>
                                                            @else
                                                            <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-300">
                                                                Membre
                                                            </span>
                                                            @endif

                                                            {{-- Indicateur "Vous" --}}
                                                            @if($user->id === auth()->id())
                                                            <span class="ml-2 text-xs text-gray-500 dark:text-gray-400">(Moi)</span>
                                                            @endif
                                                        </div>

                                                        {{-- Date d'arrivée --}}
                                                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1">
                                                            Membre depuis {{ \Carbon\Carbon::parse($user->pivot->joined_at ?? $user->pivot->created_at)->format('d/m/Y') }}
                                                        </p>
                                                    </div>
                                                </div>
                                            </div>
                                            @endforeach
                                        </div>
                                    </div>
                                </div> @if($isOwner)
                                <div class="mt-4 pt-4 border-t border-gray-200 dark:border-gray-700">
                                    <button @click="inviteModal = true"
                                        class="w-full bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded inline-flex justify-center items-center">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Inviter un membre
                                    </button>
                                </div>
                                @endif
                            </div>
                        </div>

                        {{-- Actions --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="h-5 w-5 mr-2 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                                    </svg>
                                    Actions
                                </h3>

                                <div class="space-y-3">
                                    @if(!$isOwner)
                                    {{-- Quitter --}}
                                    <button @click="deleteModal = true"
                                        class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex justify-center items-center">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        Quitter la colocation
                                    </button>
                                    @else
                                    {{-- Annuler --}}
                                    <button @click="deleteModal = true"
                                        class="w-full bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded inline-flex justify-center items-center">
                                        <svg class="h-4 w-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Annuler la colocation
                                    </button>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Colonne droite : Dépenses et dettes --}}
                    <div class="lg:col-span-2 space-y-6">
                        {{-- Qui doit à qui --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 mb-4 flex items-center">
                                    <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                    </svg>
                                    Qui doit à qui ?
                                </h3>



                            </div>
                        </div>

                        {{-- Dépenses récentes --}}
                        <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                            <div class="p-6">
                                <div class="flex justify-between items-center mb-4">
                                    <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200 flex items-center">
                                        <svg class="h-5 w-5 mr-2 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                        </svg>
                                        Dépenses récentes
                                    </h3>

                                    {{-- Filtre mois --}}
                                    <form method="GET" class="flex items-center">
                                        <select name="month" onchange="this.form.submit()" class="rounded-md border-gray-300 dark:border-gray-700 dark:bg-gray-900 dark:text-gray-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm text-sm">
                                            <option value="">Tous les mois</option>

                                        </select>
                                    </form>
                                </div>


                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- MODAL INVITATION --}}
        <div x-show="inviteModal"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
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
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
            <div @click.away="expenseModal = false"
                class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow-lg p-6">
                <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                    Ajouter une dépense
                </h2>

                <form method="POST" action="{{ route('expenses.store', $colocation->id) }}">
                    @csrf

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Titre</label>
                        <input type="text" name="title" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Montant (€)</label>
                        <input type="number" step="0.01" name="amount" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Date</label>
                        <input type="date" name="date" value="{{ now()->format('Y-m-d') }}" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 dark:text-gray-300 mb-1">Catégorie</label>
                        <select name="category_id" required
                            class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">

                        </select>
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

        {{-- MODAL CONFIRMATION SUPPRESSION --}}
        <div x-show="deleteModal"
            x-transition
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
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

                    <form action="{{ $isOwner ? route('colocation.cancel', $colocation->id) : route('colocation.leave', $colocation->id) }}"
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
    </div>
</x-app-layout>