<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="mb-6 flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold text-gray-900">Dépenses</h2>
                    <p class="text-gray-600 mt-1">{{ $colocation->name }}</p>
                </div>
                <a href="{{ route('colocation.show', $colocation) }}" 
                   class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                    Retour
                </a>
            </div>

            <!-- Filter Bar -->
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6">
                <form method="GET" class="flex gap-4">
                    <select name="month" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Tous les mois</option>
                        @for($m = 1; $m <= 12; $m++)
                            <option value="{{ $m }}" {{ request('month') == $m ? 'selected' : '' }}>
                                {{ DateTime::createFromFormat('!m', $m)->format('F') }}
                            </option>
                        @endfor
                    </select>
                    <select name="year" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500">
                        <option value="">Toutes les années</option>
                        @for($y = date('Y'); $y >= date('Y') - 5; $y--)
                            <option value="{{ $y }}" {{ request('year') == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                    <button type="submit" class="px-6 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700 transition-colors">
                        Filtrer
                    </button>
                    @if(request('month') || request('year'))
                        <a href="{{ route('depences.index', $colocation) }}" 
                           class="px-6 py-2 bg-gray-200 text-gray-700 rounded-lg hover:bg-gray-300 transition-colors">
                            Réinitialiser
                        </a>
                    @endif
                </form>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg">
                    <p class="text-green-700">{{ session('success') }}</p>
                </div>
            @endif

            <!-- Expenses List -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden">
                @if($depences->isEmpty())
                    <div class="text-center py-12">
                        <svg class="w-16 h-16 mx-auto text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500">Aucune dépense trouvée</p>
                    </div>
                @else
                    <div class="divide-y divide-gray-200">
                        @foreach($depences as $depence)
                            <div class="p-6 hover:bg-gray-50 transition-colors">
                                <div class="flex items-start justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center gap-3 mb-2">
                                            <h3 class="text-lg font-semibold text-gray-900">{{ $depence->titre }}</h3>
                                            <span class="px-3 py-1 bg-indigo-100 text-indigo-700 rounded-full text-sm">
                                                {{ $depence->category->name }}
                                            </span>
                                        </div>
                                        <p class="text-gray-600 mb-3">{{ $depence->description }}</p>
                                        <div class="flex items-center gap-6 text-sm text-gray-500">
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                </svg>
                                                <span>Payé par <strong>{{ $depence->user->name }}</strong></span>
                                            </div>
                                            <div class="flex items-center gap-2">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                                </svg>
                                                <span>{{ $depence->created_at->format('d/m/Y à H:i') }}</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="text-right ml-6">
                                        <p class="text-2xl font-bold text-gray-900">{{ number_format($depence->montant, 2) }} MAD</p>
                                        @if($depence->user_id === auth()->id())
                                            <form method="POST" action="{{ route('depences.destroy', $depence) }}" class="mt-2">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit" 
                                                    onclick="return confirm('Supprimer cette dépense ?')"
                                                    class="text-sm text-red-600 hover:text-red-800">
                                                    Supprimer
                                                </button>
                                            </form>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>

            <!-- Pagination -->
            @if($depences->hasPages())
                <div class="mt-6">
                    {{ $depences->links() }}
                </div>
            @endif
        </div>
    </div>
</x-app-layout>
