<h2>You are invited to join a colocation</h2>

<div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg p-6">
                @if($invitations->isNotEmpty())
                    <ul class="space-y-4">
                        @foreach($invitations as $invitation)
                            <li class="p-4 bg-gray-100 dark:bg-gray-700 rounded-lg flex justify-between items-center">
                                <div>
                                    <p class="font-semibold text-gray-800 dark:text-gray-200">
                                        {{ $invitation->title ?? 'No title' }}
                                    </p>
                                    <p class="text-gray-600 dark:text-gray-400">
                                        {{ $invitation->status ?? 'progress...' }}
                                    </p>
                                </div>
                                <div class="flex gap-2">
                                    <a href="{{ route('invitations.accept', $invitation->token) }}">
    Accept Invitation
</a>
                                </div>
                            </li>
                        @endforeach
                    </ul>
                @else
                    <p class="text-gray-600 dark:text-gray-400">No invitations found.</p>
                @endif
            </div>
        </div>
    </div>