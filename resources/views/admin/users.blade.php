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
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">Banni le</th>
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
                                <td class="px-6 py-4 text-sm">
                                    {{ $user->banned_at ? $user->banned_at->format('d/m/Y') : '-' }}
                                </td>
                                <td class="px-6 py-4">
                                    @if($user->is_banned)
                                        <form method="POST" action="{{ route('admin.unban', $user) }}" class="inline">
                                            @csrf
                                            <button type="submit" class="text-green-600 hover:text-green-900">Débannir</button>
                                        </form>
                                        @if($user->ban_reason)
                                            <button type="button" 
                                                onclick="alert('Raison: {{ addslashes($user->ban_reason) }}')"
                                                class="ml-2 text-blue-600 hover:text-blue-900 text-sm">
                                                Voir raison
                                            </button>
                                        @endif
                                    @else
                                        <button 
                                            onclick="showBanModal({{ $user->id }}, '{{ $user->name }}')"
                                            class="text-red-600 hover:text-red-900">
                                            Bannir
                                        </button>
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

    <!-- Modal Bannissement -->
    <div id="banModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
        <div class="bg-white rounded-lg p-6 max-w-md w-full">
            <h3 class="text-lg font-semibold mb-4">Bannir l'utilisateur</h3>
            <form id="banForm" method="POST">
                @csrf
                <p class="mb-4">Voulez-vous bannir <strong id="userName"></strong> ?</p>
                <div class="mb-4">
                    <label class="block text-sm font-medium mb-2">Raison (optionnelle)</label>
                    <textarea name="ban_reason" rows="3" 
                        class="w-full border-gray-300 rounded-md"
                        placeholder="Ex: Comportement inapproprié..."></textarea>
                </div>
                <div class="flex justify-end gap-2">
                    <button type="button" onclick="closeBanModal()" 
                        class="px-4 py-2 bg-gray-300 rounded hover:bg-gray-400">
                        Annuler
                    </button>
                    <button type="submit" 
                        class="px-4 py-2 bg-red-600 text-white rounded hover:bg-red-700">
                        Bannir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        function showBanModal(userId, userName) {
            document.getElementById('userName').textContent = userName;
            document.getElementById('banForm').action = `/admin/users/${userId}/ban`;
            document.getElementById('banModal').classList.remove('hidden');
        }

        function closeBanModal() {
            document.getElementById('banModal').classList.add('hidden');
        }
    </script>
</x-app-layout>
