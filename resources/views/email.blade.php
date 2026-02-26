<h2>You are invited to join a colocation   {{ $invitation->colocation->name }}</h2>

<p>This invitation expires at: {{ $invitation->expires_at }}</p>

<a href="{{ $url }}">
    Voir l'invitation
</a>