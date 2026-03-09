{{-- resources/views/components/beautiful-sidebar.blade.php --}}
@props(['colocation' => null])

@php
$isAdmin = auth()->user()?->isAdmin() ?? false;
@endphp

<div x-data="{ expanded: null }" 
     class="fixed left-0 top-0 mt-16 h-screen w-72 bg-gradient-to-br from-green-600 via-green-500 to-green-400 shadow-2xl z-30 overflow-y-auto overflow-x-hidden">
    


    
    {{-- Navigation --}}
    <nav class="relative px-4 py-2">
        @if($isAdmin)
        {{-- Admin Section --}}
        <div class="mb-4">
            <p class="px-4 text-xs font-semibold text-indigo-200/60 uppercase tracking-wider mb-2">Administration</p>
            
            {{-- Dashboard Link --}}
            <a href="{{ route('dashboard') }}" 
               class="group relative flex items-center px-4 py-2.5 mb-1 rounded-xl transition-all duration-300 {{ request()->routeIs('dashboard') ? 'bg-white/15 text-white shadow-lg' : 'text-indigo-200/80 hover:bg-white/10 hover:text-white' }}">
                @if(request()->routeIs('dashboard'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-gradient-to-b from-indigo-400 to-purple-400 rounded-r-full"></span>
                @endif
                <span class="relative mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span class="flex-1 text-sm font-medium">Dashboard</span>
            </a>

            {{-- Statistics Link --}}
            <a href="{{ route('admin.statistics') }}" 
               class="group relative flex items-center px-4 py-2.5 mb-1 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.statistics') ? 'bg-white/15 text-white shadow-lg' : 'text-indigo-200/80 hover:bg-white/10 hover:text-white' }}">
                @if(request()->routeIs('admin.statistics'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-gradient-to-b from-indigo-400 to-purple-400 rounded-r-full"></span>
                @endif
                <span class="relative mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                </span>
                <span class="flex-1 text-sm font-medium">Statistiques Globales</span>
            </a>

            {{-- Users Link --}}
            <a href="{{ route('admin.users') }}" 
               class="group relative flex items-center px-4 py-2.5 mb-1 rounded-xl transition-all duration-300 {{ request()->routeIs('admin.users') ? 'bg-white/15 text-white shadow-lg' : 'text-indigo-200/80 hover:bg-white/10 hover:text-white' }}">
                @if(request()->routeIs('admin.users'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-gradient-to-b from-indigo-400 to-purple-400 rounded-r-full"></span>
                @endif
                <span class="relative mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                </span>
                <span class="flex-1 text-sm font-medium">Gestion des Utilisateurs</span>
            </a>
        </div>
        @endif

        @if(isset($colocation) && $colocation && !is_array($colocation))
        {{-- Colocation Section --}}
        <div>
            <p class="px-4 text-xs font-semibold text-indigo-200/60 uppercase tracking-wider mb-2">Colocation</p>
            
            {{-- Vue d'ensemble Link --}}
            <a href="{{ route('colocation.show', $colocation) }}" 
               class="group relative flex items-center px-4 py-2.5 mb-1 rounded-xl transition-all duration-300 {{ request()->routeIs('colocation.show') ? 'bg-white/15 text-white shadow-lg' : 'text-indigo-200/80 hover:bg-white/10 hover:text-white' }}">
                @if(request()->routeIs('colocation.show'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-gradient-to-b from-indigo-400 to-purple-400 rounded-r-full"></span>
                @endif
                <span class="relative mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                    </svg>
                </span>
                <span class="flex-1 text-sm font-medium">Vue d'ensemble</span>
            </a>

            {{-- Historique dépenses Link with Badge --}}
            <a href="{{ route('depences.index', $colocation) }}" 
               class="group relative flex items-center px-4 py-2.5 mb-1 rounded-xl transition-all duration-300 {{ request()->routeIs('depences.index') ? 'bg-white/15 text-white shadow-lg' : 'text-indigo-200/80 hover:bg-white/10 hover:text-white' }}">
                @if(request()->routeIs('depences.index'))
                <span class="absolute left-0 top-1/2 -translate-y-1/2 w-1 h-6 bg-gradient-to-b from-indigo-400 to-purple-400 rounded-r-full"></span>
                @endif
                <span class="relative mr-3">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                    </svg>
                </span>
                <span class="flex-1 text-sm font-medium">Historique dépenses</span>
            </a>
        </div>
        @endif
    </nav>
</div>

@push('styles')
<style>
    @keyframes blob {
        0% { transform: translate(0px, 0px) scale(1); }
        33% { transform: translate(30px, -50px) scale(1.1); }
        66% { transform: translate(-20px, 20px) scale(0.9); }
        100% { transform: translate(0px, 0px) scale(1); }
    }
    .animate-blob {
        animation: blob 7s infinite;
    }
    .animation-delay-2000 {
        animation-delay: 2s;
    }
</style>
@endpush