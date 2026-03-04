 <x-app-layout>
     <!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->
     <div x-data="{ open: {{ $errors->any() ? 'true' : 'false' }} }">
         <x-slot name="header">
             <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                 {{ __('Dashboard') }}
             </h2>
         </x-slot>

         <div class="py-12">
             <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8">
                 <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Colocations</h1>

                 @php
                     $isBanned = auth()->user()->is_banned;
                     $isMemberInAnyColocation = $colocations->contains(function($colocation) {
                         $user = $colocation->users->first();
                         return $user && is_null($user->pivot->left_at) && $user->pivot->role === 'member';
                     });
                     $canCreateColocation = !$isBanned && !$isMemberInAnyColocation;
                 @endphp

                 @if($canCreateColocation)
                 <div
                     @click="open = true"
                     class="absolute right-3 top-1 cursor-pointer ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                     Create Colocation
                 </div>
                 @else
                 <div
                     class="absolute right-3 top-1 ml-4 bg-gray-400 text-white font-bold py-2 px-4 rounded cursor-not-allowed opacity-50"
                     title="{{ $isBanned ? 'Compte banni' : 'Vous êtes déjà membre d\'une colocation' }}">
                     Create Colocation
                 </div>
                 @endif
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                     @if($colocations->isNotEmpty())
                     <div class="p-1">
                            
                         @foreach($colocations as $colocation)
                         <div class="p-4 mb-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                            @php
                $isOwner = $colocation->users->contains(function($user) {
                return $user->id === auth()->id() && $user->pivot->role === 'owner';
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
                             <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $colocation->name }}</h3>
                             <p class="text-gray-600 dark:text-gray-400">{{ $colocation->description }}</p>
                             @php
                             $user = $colocation->users->first();
                             $isBanned = auth()->user()->is_banned;
                             $hasLeftColocation = $user && !is_null($user->pivot->left_at);
                             @endphp
                             
                             @if($isBanned)
                             <span class="text-red-500 mt-2 inline-block cursor-not-allowed">
                                 Accès refusé (Compte banni)
                             </span>
                             @elseif($hasLeftColocation)
                             <span class="text-gray-400 mt-2 inline-block cursor-not-allowed">
                                 View Details (Quitté)
                             </span>
                             @else
                             <a href="{{ route('colocation.show', $colocation->id) }}" class="text-blue-500 hover:underline mt-2 inline-block">View Details</a>
                             @endif

                         </div>
                         @endforeach
                     </div>

                     @else
                     <div class="p-4">
                         <p class="text-gray-600 dark:text-gray-400">Pas des colocations.</p>
                     </div>
                     @endif

                 </div>
             </div>
         </div>
         <div
             x-show="open"
             x-transition
             class="fixed inset-0 flex items-center justify-center bg-black bg-opacity-50 z-50">
             <div
                 @click.away="open = false"
                 class="bg-white dark:bg-gray-800 w-full max-w-lg rounded-lg shadow-lg p-6">
                 <h2 class="text-xl font-bold text-gray-800 dark:text-gray-200 mb-4">
                     Create Colocation
                 </h2>
                 @if ($errors->any())
                 <div class="mb-4 rounded bg-red-100 border border-red-400 text-red-700 p-3">
                     <ul class="list-disc pl-5">
                         @foreach ($errors->all() as $error)
                         <li>{{ $error }}</li>
                         @endforeach
                     </ul>
                 </div>
                 @endif
                 <form method="POST" action="{{ route('colocation.store') }}">
                     @csrf

                     <!-- Name -->
                     <div class="mb-4">
                         <label class="block text-gray-700 dark:text-gray-300 mb-1">
                             Name
                         </label>
                         <input
                             type="text"
                             name="name"
                             required
                             class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white">
                     </div>

                     <!-- Description -->
                     <div class="mb-4">
                         <label class="block text-gray-700 dark:text-gray-300 mb-1">
                             Description
                         </label>
                         <textarea
                             name="description"
                             rows="3"
                             class="w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white"></textarea>
                     </div>

                     <!-- Buttons -->
                     <div class="flex justify-end gap-2">
                         <button
                             type="button"
                             @click="open = false"
                             class="px-4 py-2 bg-gray-400 text-white rounded">
                             Cancel
                         </button>

                         <button
                             type="submit"
                             class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded">
                             Save
                         </button>
                     </div>
                 </form>
             </div>
         </div>
     </div>
 </x-app-layout>