{{-- resources/views/dashboard.blade.php --}}
<x-app-layout>
    <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }" class="relative mt-16">
        <x-slot name="header">
            <div class="flex items-center justify-between">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    {{ __('Dashboard') }}
                </h2>
                <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-500 dark:text-gray-400">
                        {{ now()->format('l, F j, Y') }}
                    </span>
                </div>
            </div>
        </x-slot>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{-- Welcome Banner --}}
                <div class="mb-8 bg-gradient-to-r from-indigo-500 via-purple-500 to-pink-500 rounded-2xl shadow-lg overflow-hidden">
                    <div class="relative px-6 py-8 sm:px-8 sm:py-10">
                        <div class="absolute inset-0 bg-grid-pattern opacity-10"></div>
                        <div class="relative flex items-center justify-between">
                            <div>
                                <h1 class="text-3xl font-bold text-white mb-2">
                                    Welcome back, {{ auth()->user()->name }}! 👋
                                </h1>
                                <p class="text-indigo-100 text-lg">
                                    @if($colocations->isNotEmpty())
                                        You have {{ $colocations->count() }} colocation{{ $colocations->count() > 1 ? 's' : '' }} to manage.
                                    @else
                                        Ready to start your colocation journey?
                                    @endif
                                </p>
                            </div>
                            <div class="hidden sm:block">
                                <svg class="w-32 h-32 text-white/20" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                    <path d="M17 21v-4H7v4M16 3v5h5"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Stats Cards --}}
                @if($colocations->isNotEmpty())
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Colocations</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-gray-200 mt-1">{{ $colocations->count() }}</p>
                            </div>
                            <div class="w-12 h-12 bg-indigo-100 dark:bg-indigo-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Total Members</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-gray-200 mt-1">
                                    {{ $colocations->sum(function($colocation) { return $colocation->users->count(); }) }}
                                </p>
                            </div>
                            <div class="w-12 h-12 bg-purple-100 dark:bg-purple-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-purple-600 dark:text-purple-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                </svg>
                            </div>
                        </div>
                    </div>

                    <div class="bg-white dark:bg-gray-800 rounded-2xl shadow-sm p-6 border border-gray-100 dark:border-gray-700">
                        <div class="flex items-center justify-between">
                            <div>
                                <p class="text-sm text-gray-600 dark:text-gray-400">Pending Tasks</p>
                                <p class="text-2xl font-bold text-gray-800 dark:text-gray-200 mt-1">12</p>
                            </div>
                            <div class="w-12 h-12 bg-yellow-100 dark:bg-yellow-900/30 rounded-xl flex items-center justify-center">
                                <svg class="w-6 h-6 text-yellow-600 dark:text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"/>
                                </svg>
                            </div>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Main Content Card --}}
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-2xl border border-gray-100 dark:border-gray-700">
                    <div class="p-6 lg:p-8">
                        {{-- Header with Create Button --}}
                        <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between mb-8">
                            <div>
                                <h3 class="text-2xl font-bold text-gray-900 dark:text-gray-100">Your Colocations</h3>
                                <p class="text-gray-600 dark:text-gray-400 mt-1">Manage and monitor all your colocations in one place</p>
                            </div>
                            
                            @php
                                $isBanned = auth()->user()->is_banned;
                                $isMemberInAnyColocation = $colocations->contains(function($colocation) {
                                    $user = $colocation->users->first();
                                    return $user && is_null($user->pivot->left_at) && $user->pivot->role === 'member';
                                });
                                $canCreateColocation = !$isBanned && !$isMemberInAnyColocation;
                            @endphp

                            @if($canCreateColocation)
                                <button 
                                    @click="open = true"
                                    class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                    <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                    </svg>
                                    Create New Colocation
                                </button>
                            @else
                                <div class="relative group">
                                    <button
                                        disabled
                                        class="mt-4 sm:mt-0 inline-flex items-center px-6 py-3 bg-gray-400 dark:bg-gray-600 text-white font-medium rounded-xl cursor-not-allowed opacity-50">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Create New Colocation
                                    </button>
                                    <div class="absolute bottom-full left-1/2 transform -translate-x-1/2 mb-2 px-3 py-2 bg-gray-900 text-white text-sm rounded-lg opacity-0 group-hover:opacity-100 transition-opacity duration-200 whitespace-nowrap pointer-events-none">
                                        {{ $isBanned ? 'Your account has been banned' : 'You are already a member of a colocation' }}
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Colocations Grid --}}
                        @if($colocations->isNotEmpty())
                            <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
                                @foreach($colocations as $colocation)
                                    @php
                                        $currentUser = $colocation->users->where('id', auth()->id())->first();
                                        $isOwner = $currentUser && $currentUser->pivot->role === 'owner';
                                        $hasLeft = $currentUser && !is_null($currentUser->pivot->left_at);
                                        $memberCount = $colocation->users->count();
                                        $pendingExpenses = rand(0, 5); // Replace with actual data
                                    @endphp

                                    <div class="group relative bg-gradient-to-br from-gray-50 to-gray-100 dark:from-gray-700/50 dark:to-gray-800 rounded-2xl p-6 hover:shadow-xl transition-all duration-300 {{ $hasLeft ? 'opacity-75' : '' }}">
                                        {{-- Role Badge --}}
                                        <div class="absolute top-4 right-4">
                                            @if($isOwner)
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-yellow-400 to-yellow-500 text-white shadow-lg">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                                                    </svg>
                                                    Owner
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gradient-to-r from-blue-400 to-blue-500 text-white shadow-lg">
                                                    <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                                        <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"/>
                                                    </svg>
                                                    Member
                                                </span>
                                            @endif
                                        </div>

                                        {{-- Colocation Icon --}}
                                        <div class="w-14 h-14 bg-gradient-to-br from-indigo-500 to-purple-600 rounded-2xl flex items-center justify-center mb-4 shadow-lg">
                                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                                            </svg>
                                        </div>

                                        {{-- Colocation Info --}}
                                        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">
                                            {{ $colocation->name }}
                                        </h3>
                                        
                                        <p class="text-gray-600 dark:text-gray-400 text-sm mb-4 line-clamp-2">
                                            {{ $colocation->description ?: 'No description provided.' }}
                                        </p>

                                        {{-- Stats Row --}}
                                        <div class="flex items-center space-x-4 mb-4">
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"/>
                                                </svg>
                                                {{ $memberCount }} {{ Str::plural('member', $memberCount) }}
                                            </div>
                                            <div class="flex items-center text-sm text-gray-600 dark:text-gray-400">
                                                <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                                €{{ rand(100, 1000) }}
                                            </div>
                                        </div>

                                        {{-- Action Button --}}
                                        @php
                                            $isBanned = auth()->user()->is_banned;
                                        @endphp
                                        
                                        @if($isBanned)
                                            <span class="inline-flex items-center px-4 py-2 bg-red-100 dark:bg-red-900/30 text-red-600 dark:text-red-400 rounded-xl text-sm font-medium cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"/>
                                                </svg>
                                                Account Banned
                                            </span>
                                        @elseif($hasLeft)
                                            <span class="inline-flex items-center px-4 py-2 bg-gray-300 dark:bg-gray-600 text-gray-600 dark:text-gray-400 rounded-xl text-sm font-medium cursor-not-allowed">
                                                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"/>
                                                </svg>
                                                Left Colocation
                                            </span>
                                        @else
                                            <a href="{{ route('colocation.show', $colocation->id) }}" 
                                               class="inline-flex items-center justify-center w-full px-4 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transform hover:scale-[1.02] transition-all duration-200 group">
                                                <span>View Dashboard</span>
                                                <svg class="w-4 h-4 ml-2 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"/>
                                                </svg>
                                            </a>
                                        @endif

                                        {{-- Pending Badge --}}
                                        @if($pendingExpenses > 0)
                                            <div class="absolute -top-2 -right-2">
                                                <span class="flex h-6 w-6 items-center justify-center rounded-full bg-red-500 text-xs font-bold text-white shadow-lg">
                                                    {{ $pendingExpenses }}
                                                </span>
                                            </div>
                                        @endif
                                    </div>
                                @endforeach
                            </div>
                        @else
                            {{-- Empty State --}}
                            <div class="text-center py-16">
                                <div class="w-24 h-24 mx-auto bg-gradient-to-br from-indigo-100 to-purple-100 dark:from-indigo-900/30 dark:to-purple-900/30 rounded-3xl flex items-center justify-center mb-6">
                                    <svg class="w-12 h-12 text-indigo-600 dark:text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100 mb-2">No colocations yet</h3>
                                <p class="text-gray-600 dark:text-gray-400 mb-6 max-w-md mx-auto">
                                    Get started by creating your first colocation and invite your roommates to join!
                                </p>
                                @if($canCreateColocation)
                                    <button 
                                        @click="open = true"
                                        class="inline-flex items-center px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white font-medium rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200">
                                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                        </svg>
                                        Create Your First Colocation
                                    </button>
                                @endif
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        {{-- Create Colocation Modal --}}
        <div
            x-show="open"
            x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-200"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50 p-4">
            
            <div
                @click.away="open = false"
                x-transition:enter="transition ease-out duration-300"
                x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100"
                x-transition:leave-end="opacity-0 scale-95"
                class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-2xl shadow-2xl overflow-hidden">
                
                {{-- Modal Header --}}
                <div class="px-6 py-4 bg-gradient-to-r from-indigo-600 to-purple-600">
                    <div class="flex items-center justify-between">
                        <h2 class="text-xl font-bold text-white">
                            Create New Colocation
                        </h2>
                        <button @click="open = false" class="text-white/80 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Modal Body --}}
                <div class="p-6">
                    @if ($errors->any())
                        <div class="mb-6 rounded-xl bg-red-50 dark:bg-red-900/30 border border-red-200 dark:border-red-800 p-4">
                            <div class="flex">
                                <svg class="w-5 h-5 text-red-600 dark:text-red-400 mr-2 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                </svg>
                                <div>
                                    <h3 class="text-sm font-medium text-red-800 dark:text-red-300 mb-1">Please fix the following errors:</h3>
                                    <ul class="list-disc pl-5 text-sm text-red-700 dark:text-red-400">
                                        @foreach ($errors->all() as $error)
                                            <li>{{ $error }}</li>
                                        @endforeach
                                    </ul>
                                </div>
                            </div>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('colocation.store') }}" class="space-y-6">
                        @csrf

                        {{-- Name Field --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Colocation Name
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21H5a2 2 0 01-2-2V5a2 2 0 012-2h11l5 5v11a2 2 0 01-2 2z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="name"
                                    value="{{ old('name') }}"
                                    required
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                                    placeholder="e.g., Sunset Apartments">
                            </div>
                        </div>

                        {{-- Description Field --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Description
                            </label>
                            <div class="relative">
                                <div class="absolute top-3 left-3 pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h7"/>
                                    </svg>
                                </div>
                                <textarea
                                    name="description"
                                    rows="4"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                                    placeholder="Describe your colocation...">{{ old('description') }}</textarea>
                            </div>
                        </div>

                        {{-- Address Field (Optional) --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Address <span class="text-gray-400">(optional)</span>
                            </label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    </svg>
                                </div>
                                <input
                                    type="text"
                                    name="address"
                                    value="{{ old('address') }}"
                                    class="w-full pl-10 pr-4 py-3 rounded-xl border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white focus:border-indigo-500 focus:ring-indigo-500 transition-colors"
                                    placeholder="123 Main St, City">
                            </div>
                        </div>

                        {{-- Form Actions --}}
                        <div class="flex justify-end space-x-3 pt-4">
                            <button
                                type="button"
                                @click="open = false"
                                class="px-6 py-3 border border-gray-300 dark:border-gray-600 rounded-xl text-gray-700 dark:text-gray-300 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors font-medium">
                                Cancel
                            </button>
                            <button
                                type="submit"
                                class="px-6 py-3 bg-gradient-to-r from-indigo-600 to-purple-600 hover:from-indigo-700 hover:to-purple-700 text-white rounded-xl shadow-lg hover:shadow-xl transform hover:scale-105 transition-all duration-200 font-medium">
                                Create Colocation
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    {{-- Add custom styles --}}
    @push('styles')
    <style>
        .bg-grid-pattern {
            background-image: 
                linear-gradient(rgba(255, 255, 255, 0.1) 1px, transparent 1px),
                linear-gradient(90deg, rgba(255, 255, 255, 0.1) 1px, transparent 1px);
            background-size: 20px 20px;
        }
        
        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>
    @endpush
</x-app-layout>