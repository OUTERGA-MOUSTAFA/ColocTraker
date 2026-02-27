<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Invitation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 dark:bg-gray-900 min-h-screen flex items-center justify-center py-12">

    <div class="max-w-xl w-full bg-white dark:bg-gray-800 shadow-xl rounded-2xl p-8">

        <!-- Header -->
        <h2 class="text-2xl font-bold text-gray-800 dark:text-white mb-4">
            You are invited to join colocation: <span class="text-blue-600">{{ $invitation->colocation->name }}</span>
        </h2>

        <!-- Invitation Info -->
        <div class="mb-6 space-y-2">
            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Status:</span>
                <span class="capitalize">{{ $invitation->status }}</span>
            </p>

            <p class="text-gray-600 dark:text-gray-300">
                <span class="font-semibold">Expires at:</span>
                <span class="text-gray-800 dark:text-gray-200">{{ $invitation->expires_at }}</span>
            </p>
        </div>

        <!-- Accept Buttons -->
        <div class="flex gap-4">
            <form action="{{route('invitation.accept',$invitation->token)}}" method="post">
                @csrf
                <button type="submit"
               class="px-5 py-2 bg-green-600 hover:bg-green-700 text-white rounded-lg shadow transition">
                Accept Invitation
                </button>
            </form>
            

            <!-- Refuse -->
             <form action="{{route('invitation.refuse',$invitation->token)}}" method="post">
                @csrf
                <button type="submit"
               class="px-5 py-2 bg-red-600 hover:bg-red-700 text-white rounded-lg shadow transition">
                Refuse Invitation
                </button>
            </form>

    </div>

</body>
</html>