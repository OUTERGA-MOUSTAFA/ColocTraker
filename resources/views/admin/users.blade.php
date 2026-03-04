<x-app-layout>
    <div class="py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <!-- Header with gradient and stats -->
            <div class="mb-8 flex justify-between items-center">
                <div>
                    <h2 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent">
                        Gestion des Utilisateurs
                    </h2>
                    <p class="text-gray-600 mt-2">Gérez les utilisateurs et leurs permissions</p>
                </div>
                <div class="flex space-x-3">
                    <button class="px-4 py-2 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors flex items-center gap-2 shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                        </svg>
                        Exporter
                    </button>
                    <button class="px-4 py-2 bg-gradient-to-r from-indigo-600 to-purple-600 text-white rounded-xl hover:from-indigo-700 hover:to-purple-700 transition-all shadow-md flex items-center gap-2">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                        </svg>
                        Ajouter
                    </button>
                </div>
            </div>

            <!-- Quick Stats -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Total</p>
                            <p class="text-2xl font-bold text-gray-800">{{ $users->total() }}</p>
                        </div>
                        <div class="bg-indigo-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Actifs</p>
                            <p class="text-2xl font-bold text-green-600">{{ $users->where('is_banned', false)->count() }}</p>
                        </div>
                        <div class="bg-green-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Bannis</p>
                            <p class="text-2xl font-bold text-red-600">{{ $users->where('is_banned', true)->count() }}</p>
                        </div>
                        <div class="bg-red-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="bg-white rounded-xl shadow-sm p-4 border border-gray-100">
                    <div class="flex items-center justify-between">
                        <div>
                            <p class="text-gray-500 text-sm">Réputation moyenne</p>
                            <p class="text-2xl font-bold text-yellow-600">{{ round($users->avg('reputation_score') ?? 0, 1) }}</p>
                        </div>
                        <div class="bg-yellow-100 p-3 rounded-lg">
                            <svg class="w-6 h-6 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Search and Filter Bar -->
            <div class="bg-white rounded-xl shadow-sm p-4 mb-6 border border-gray-100">
                <div class="flex flex-col md:flex-row gap-4">
                    <div class="flex-1 relative">
                        <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        <input type="text" 
                            placeholder="Rechercher un utilisateur..." 
                            class="w-full pl-10 pr-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                    </div>
                    <div class="flex gap-2">
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option>Tous les statuts</option>
                            <option>Actifs</option>
                            <option>Bannis</option>
                        </select>
                        <select class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500">
                            <option>Réputation</option>
                            <option>Croissante</option>
                            <option>Décroissante</option>
                        </select>
                    </div>
                </div>
            </div>

            @if(session('success'))
                <div class="mb-4 bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-green-700">{{ session('success') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            @if(session('error'))
                <div class="mb-4 bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg shadow-sm" role="alert">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <svg class="h-5 w-5 text-red-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div class="ml-3">
                            <p class="text-sm font-medium text-red-700">{{ session('error') }}</p>
                        </div>
                    </div>
                </div>
            @endif

            <!-- Users Table -->
            <div class="bg-white rounded-xl shadow-lg overflow-hidden border border-gray-100">
                <div class="overflow-x-auto">
                    <table class="min-w-full">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Utilisateur</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Contact</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Réputation</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Colocations</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Statut</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Banni le</th>
                                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            @foreach($users as $user)
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex-shrink-0 h-10 w-10">
                                                <div class="h-10 w-10 rounded-full bg-gradient-to-r from-indigo-500 to-purple-500 flex items-center justify-center text-white font-semibold">
                                                    {{ strtoupper(substr($user->name, 0, 2)) }}
                                                </div>
                                            </div>
                                            <div class="ml-4">
                                                <div class="text-sm font-medium text-gray-900">{{ $user->name }}</div>
                                                <div class="text-sm text-gray-500">ID: #{{ $user->id }}</div>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="text-sm text-gray-900">{{ $user->email }}</div>
                                        <div class="text-sm text-gray-500">Vérifié</div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center">
                                            <div class="flex items-center">
                                                @for($i = 1; $i <= 5; $i++)
                                                    <svg class="w-4 h-4 {{ $i <= round($user->reputation_score / 20) ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                    </svg>
                                                @endfor
                                            </div>
                                            <span class="ml-2 text-sm text-gray-600">{{ $user->reputation_score ?? 0 }}</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4">
                                        <span class="px-3 py-1 bg-indigo-50 text-indigo-700 rounded-full text-sm font-medium">
                                            {{ $user->colocation_count }} colocs
                                        </span>
                                    </td>
                                    <td class="px-6 py-4">
                                        @if($user->is_banned)
                                            <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-medium rounded-full bg-red-100 text-red-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-red-600"></span>
                                                Banni
                                            </span>
                                        @else
                                            <span class="px-3 py-1 inline-flex items-center gap-1 text-xs font-medium rounded-full bg-green-100 text-green-800">
                                                <span class="w-1.5 h-1.5 rounded-full bg-green-600"></span>
                                                Actif
                                            </span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-600">
                                        {{ $user->banned_at ? $user->banned_at->format('d/m/Y H:i') : '-' }}
                                    </td>
                                    <td class="px-6 py-4">
                                        <div class="flex items-center gap-2">
                                            <button class="p-2 text-gray-500 hover:text-indigo-600 transition-colors rounded-lg hover:bg-indigo-50" title="Voir détails">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                                </svg>
                                            </button>
                                            
                                            @if($user->is_banned)
                                                <button 
                                                    onclick="showUnbanModal({{ $user->id }}, '{{ $user->name }}')"
                                                    class="p-2 text-green-600 hover:text-green-800 transition-colors rounded-lg hover:bg-green-50" 
                                                    title="Débannir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                                    </svg>
                                                </button>
                                                @if($user->ban_reason)
                                                    <button type="button" 
                                                        onclick="showReasonModal('{{ addslashes($user->ban_reason) }}')"
                                                        class="p-2 text-blue-600 hover:text-blue-800 transition-colors rounded-lg hover:bg-blue-50" 
                                                        title="Voir raison">
                                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8.228 9c.549-1.165 2.03-2 3.772-2 2.21 0 4 1.343 4 3 0 1.4-1.278 2.575-3.006 2.907-.542.104-.994.54-.994 1.093m0 3h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                    </button>
                                                @endif
                                            @else
                                                <button 
                                                    onclick="showBanModal({{ $user->id }}, '{{ $user->name }}')"
                                                    class="p-2 text-red-600 hover:text-red-800 transition-colors rounded-lg hover:bg-red-50" 
                                                    title="Bannir">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                                                    </svg>
                                                </button>
                                            @endif
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Pagination -->
            <div class="mt-6">
                {{ $users->links() }}
            </div>
        </div>
    </div>

    <!-- Ban Modal -->
    <div id="banModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 transform transition-all">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-100 mx-auto mb-4">
                <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-center mb-2">Bannir l'utilisateur</h3>
            <p class="text-gray-600 text-center mb-6">
                Êtes-vous sûr de vouloir bannir <span id="userName" class="font-semibold"></span> ?
            </p>
            
            <form id="banForm" method="POST">
                @csrf
                <div class="mb-6">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Raison du bannissement</label>
                    <textarea name="ban_reason" rows="3" 
                        class="w-full border-gray-300 rounded-xl focus:ring-2 focus:ring-red-500 focus:border-red-500"
                        placeholder="Expliquez la raison du bannissement..."></textarea>
                </div>
                
                <div class="flex gap-3">
                    <button type="button" onclick="closeBanModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl hover:from-red-700 hover:to-red-800 transition-all shadow-md">
                        Bannir
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Reason Modal -->
    <div id="reasonModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-bold text-gray-900">Raison du bannissement</h3>
                <button onclick="closeReasonModal()" class="text-gray-400 hover:text-gray-600">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            <p id="banReason" class="text-gray-700 bg-gray-50 p-4 rounded-xl"></p>
        </div>
    </div>

    <!-- Unban Modal -->
    <div id="unbanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 transition-opacity">
        <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4 transform transition-all">
            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-100 mx-auto mb-4">
                <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
            </div>
            
            <h3 class="text-xl font-bold text-center mb-2">Débannir l'utilisateur</h3>
            <p class="text-gray-600 text-center mb-6">
                Êtes-vous sûr de vouloir débannir <span id="unbanUserName" class="font-semibold"></span> ?
            </p>
            
            <form id="unbanForm" method="POST">
                @csrf
                <div class="flex gap-3">
                    <button type="button" onclick="closeUnbanModal()" 
                        class="flex-1 px-4 py-2 border border-gray-300 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors">
                        Annuler
                    </button>
                    <button type="submit" 
                        class="flex-1 px-4 py-2 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-xl hover:from-green-700 hover:to-green-800 transition-all shadow-md">
                        Débannir
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
            document.querySelector('textarea[name="ban_reason"]').value = '';
        }

        function showReasonModal(reason) {
            document.getElementById('banReason').textContent = reason;
            document.getElementById('reasonModal').classList.remove('hidden');
        }

        function closeReasonModal() {
            document.getElementById('reasonModal').classList.add('hidden');
        }

        function showUnbanModal(userId, userName) {
            document.getElementById('unbanUserName').textContent = userName;
            document.getElementById('unbanForm').action = `/admin/users/${userId}/unban`;
            document.getElementById('unbanModal').classList.remove('hidden');
        }

        function closeUnbanModal() {
            document.getElementById('unbanModal').classList.add('hidden');
        }

        // Close modals when clicking outside
        window.onclick = function(event) {
            const banModal = document.getElementById('banModal');
            const reasonModal = document.getElementById('reasonModal');
            const unbanModal = document.getElementById('unbanModal');
            if (event.target === banModal) {
                closeBanModal();
            }
            if (event.target === reasonModal) {
                closeReasonModal();
            }
            if (event.target === unbanModal) {
                closeUnbanModal();
            }
        }
    </script>
</x-app-layout>