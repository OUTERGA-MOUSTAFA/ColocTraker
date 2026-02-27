<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Colocation Invitation</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div class="bg-white shadow-lg rounded-xl p-8 max-w-md w-full text-center">

        <h2 class="text-2xl font-bold text-gray-800 mb-4">
            You are invited to join a colocation <span class="text-blue-600">{{ $invitation->colocation->name }}</span>
        </h2>

        <p class="text-gray-600 mb-6">
            This invitation expires at:
            <span class="font-semibold text-gray-800"> {{ $invitation->expires_at }}</span>
        </p>

<a href="{{ $url }}"
           class="inline-block px-6 py-3 bg-blue-600 hover:bg-blue-700 text-white rounded-lg shadow transition">
            Voir l'invitation
        </a>

    </div>

</body>
</html>