{{-- resources/views/layouts/navigation.blade.php --}}
<nav x-data="{ open: false, scrolled: false }" 
     @scroll.window="scrolled = window.scrollY > 10"
     class="fixed top-0 left-0 right-0 z-40 transition-all duration-300"
     :class="{
         'bg-white/90 dark:bg-gray-900/90 backdrop-blur-md shadow-md': scrolled,
         'bg-white dark:bg-gray-900': !scrolled
     }">
    
    <div class="px-6 h-16 flex items-center justify-between">
        {{-- Left Section with Logo and Title --}}
        <div class="flex items-center space-x-4">
            {{-- Mobile Menu Toggle --}}
            <button @click="open = !open" 
                    class="lg:hidden p-2 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                <svg class="w-5 h-5 text-gray-600 dark:text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                </svg>
            </button>

            {{-- Page Title --}}
            <h1 class="text-xl font-bold text-gray-800 dark:text-gray-200">
                Dashboard
            </h1>
        </div>

        {{-- Right Section with User Menu --}}
        <div class="flex items-center space-x-3">
            
            <div x-data="{ userMenuOpen: false }" class="relative">
                <button @click="userMenuOpen = !userMenuOpen" 
                        class="flex items-center space-x-3 p-1 pr-3 rounded-xl hover:bg-gray-100 dark:hover:bg-gray-800 transition-colors">
                    {{-- Avatar with Status --}}
                    <div class="relative">
                        <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-indigo-500 to-purple-600 p-[2px]">
                            <div class="w-full h-full rounded-lg bg-white dark:bg-gray-900 flex items-center justify-center">
                                @if(auth()->user()->profile_photo_path)
                                    <img src="{{ Storage::url(auth()->user()->profile_photo_path) }}" 
                                         alt="{{ auth()->user()->name }}"
                                         class="w-full h-full rounded-lg object-cover">
                                @else
                                    <span class="text-sm font-bold text-indigo-600 dark:text-indigo-400">
                                        {{ substr(auth()->user()->name, 0, 1) }}
                                    </span>
                                @endif
                            </div>
                        </div>
                        <div class="absolute -bottom-1 -right-1 w-3 h-3 bg-green-400 rounded-full border-2 border-white dark:border-gray-900"></div>
                    </div>
                    
                    {{-- User Info --}}
                    <div class="hidden sm:block text-left">
                        <p class="text-sm font-medium text-gray-800 dark:text-gray-200">{{ auth()->user()->name }}</p>
                        <p class="text-xs text-gray-500 dark:text-gray-400">{{ auth()->user()->email }}</p>
                    </div>
                    
                    <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" 
                         :class="{ 'rotate-180': userMenuOpen }"
                         fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                    </svg>
                </button>

                {{-- Dropdown Menu --}}
                <div x-show="userMenuOpen" 
                     @click.away="userMenuOpen = false"
                     x-transition:enter="transition ease-out duration-200"
                     x-transition:enter-start="opacity-0 scale-95"
                     x-transition:enter-end="opacity-100 scale-100"
                     class="absolute right-0 mt-2 w-64 bg-white dark:bg-gray-800 rounded-xl shadow-xl border border-gray-100 dark:border-gray-700 overflow-hidden z-50">
                    
                    
                    {{-- Menu Items --}}
                    <div class="p-2">
                        <a href="{{ route('profile.edit') }}" 
                           class="flex items-center space-x-3 px-4 py-2 text-sm text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-lg transition-colors">
                            <svg class="w-4 h-4 text-gray-500 dark:text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                            </svg>
                            <span>Profile</span>
                        </a>
                        <div class="border-t border-gray-100 dark:border-gray-700 my-2"></div>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" 
                                    class="w-full flex items-center space-x-3 px-4 py-2 text-sm text-red-600 dark:text-red-400 hover:bg-red-50 dark:hover:bg-red-900/20 rounded-lg transition-colors">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                </svg>
                                <span>Logout</span>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Mobile Menu --}}
    <div x-show="open" 
         x-transition:enter="transition ease-out duration-200"
         x-transition:enter-start="opacity-0 -translate-y-2"
         x-transition:enter-end="opacity-100 translate-y-0"
         x-transition:leave="transition ease-in duration-150"
         x-transition:leave-start="opacity-100 translate-y-0"
         x-transition:leave-end="opacity-0 -translate-y-2"
         class="lg:hidden border-t border-gray-100 dark:border-gray-800 bg-white dark:bg-gray-900">
        
        <div class="p-4 space-y-1">
            {{-- Mobile Navigation Links --}}
            <a href="{{ route('dashboard') }}" 
               class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-colors {{ request()->routeIs('dashboard') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                </svg>
                <span>Dashboard</span>
            </a>

            @if(auth()->user()?->isAdmin())
                <a href="{{ route('admin.statistics') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-colors {{ request()->routeIs('admin.statistics') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    <span>Statistics</span>
                </a>

                <a href="{{ route('admin.users') }}" 
                   class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-colors {{ request()->routeIs('admin.users') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                    </svg>
                    <span>Users</span>
                </a>
            @endif

            @if(isset($colocation) && $colocation)
                <div class="border-t border-gray-100 dark:border-gray-800 my-2 pt-2">
                    <p class="px-4 text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider mb-2">Colocation</p>
                    
                    <a href="{{ route('colocation.show', $colocation) }}" 
                       class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-colors {{ request()->routeIs('colocation.show') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        <span>Vue d'ensemble</span>
                    </a>

                    <a href="{{ route('depences.index', $colocation) }}" 
                       class="flex items-center space-x-3 px-4 py-3 text-gray-700 dark:text-gray-300 hover:bg-indigo-50 dark:hover:bg-indigo-900/20 rounded-xl transition-colors {{ request()->routeIs('depences.index') ? 'bg-indigo-50 dark:bg-indigo-900/20 text-indigo-600 dark:text-indigo-400' : '' }}">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-3 7h3m-3 4h3m-6-4h.01M9 16h.01"/>
                        </svg>
                        <span>Historique dépenses</span>
                        <span class="ml-auto px-2 py-0.5 bg-red-500/20 text-red-600 dark:text-red-400 text-xs font-medium rounded-full">3</span>
                    </a>
                </div>
            @endif
        </div>
    </div>
</nav>