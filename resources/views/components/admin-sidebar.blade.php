<div class="fixed left-0 top-16 h-full w-64 bg-white shadow-lg z-40">
    <div class="p-6">
        <h3 class="text-lg font-semibold text-gray-800 mb-4">Admin Panel</h3>
        <nav class="space-y-2">
            <a href="{{ route('dashboard') }}" 
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('dashboard') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                Dashboard
            </a>
            <a href="{{ route('admin.statistics') }}" 
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.statistics') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                Statistiques Globales
            </a>
            <a href="{{ route('admin.users') }}" 
               class="block px-4 py-2 rounded hover:bg-gray-100 {{ request()->routeIs('admin.users') ? 'bg-blue-50 text-blue-600' : 'text-gray-700' }}">
                Gestion des Utilisateurs
            </a>
        </nav>
    </div>
</div>
