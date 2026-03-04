<x-app-layout>
    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <h2 class="text-2xl font-bold mb-6">Gestion des Utilisateurs</h2>

            @if(session('success'))
                <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                    {{ session('success') }}
                </div>
            @endif

            @if(session('error'))
                <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                    {{ session('error') }}
                </div>
            @endif

            <div class="bg-white rounded-lg shadow overflow-hidden">
                <table class="min-w-full">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Réputation</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Colocations</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-200">
                        @foreach($users as $user)
                            <tr>
                                <td class="px-6 py-4">{{ $user->name }}</td>
                                <td class="px-6 py-4">{{ $user->email }}</td>
                                <td class="px-6 py-4">{{ $user->reputation_score ?? 0 }}</td>
                                <td class="px-6 py-4">{{ $user->colocation_count }}</td>
                                <td class="px-6 py-4">
                                    @if($user->is_banned)
                                        <span class="px-2 py-1 text-xs bg-red-100 text-red-800 rounded">Banni</span>
                                    @else
                                        <span class="px-2 py-1 text-xs bg-green-100 text-green-800 rounded">Actif</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_banned)
                                        <form method="POST" action="{{ route('admin.unban', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">Débannir</button>
                                        </form>
                                    @else
                                        <form method="POST" action="{{ route('admin.ban', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-red-600 hover:text-red-900" 
                                                onclick="return confirm('Êtes-vous sûr de vouloir bannir cet utilisateur ?')">
                                                Bannir
                                            </button>
                                        </form>
                                    @endif
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <div class="mt-4">
                {{ $users->links() }}
            </div>
        </div>
    </div>
</x-app-layout>
