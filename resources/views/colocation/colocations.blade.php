 <x-app-layout>
     <!-- The best way to take care of the future is to take care of the present moment. - Thich Nhat Hanh -->
     <div x-data="{ open: false }">
         <x-slot name="header">
             <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                 {{ __('Dashboard') }}
             </h2>
         </x-slot>

         <div class="py-12">
             <div class="relative max-w-7xl mx-auto sm:px-6 lg:px-8">
                 <h1 class="text-2xl font-bold text-gray-800 dark:text-gray-200 mb-4">Colocations</h1>

                 <div
                     @click="open = true"
                     class="absolute right-3 top-1 cursor-pointer ml-4 bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                     Create Colocation
                 </div>
                 <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                     @if($colocations->isNotEmpty())
                     <div class="p-1">
                         @foreach($colocations as $colocation)
                         <div class="p-4 mb-4 bg-gray-100 dark:bg-gray-700 rounded-lg">
                             <h3 class="text-lg font-semibold text-gray-800 dark:text-gray-200">{{ $colocation->name }}</h3>
                             <p class="text-gray-600 dark:text-gray-400">{{ $colocation->description }}</p>
                             <a href="{{ route('colocations.show', $colocation->id) }}" class="text-blue-500 hover:underline mt-2 inline-block">View Details</a>
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

                 <form method="POST" action="{{ route('colocations.store') }}">
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