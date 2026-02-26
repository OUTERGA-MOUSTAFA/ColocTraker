<?php

namespace App\Http\Controllers;

use App\Jobs\SendInvitationEmail;
use App\Mail\InvitationMail;
use App\Models\Invitation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class InvitationController extends Controller
{
    public function show(Invitation $invitation)
    {
        if ($invitation->status !== 'pending') {
            abort(403, 'Invitation not valid');
        }
        return view('colocation.invitation', compact('invitation'));
    }

    public function inviter(Request $request, $id)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $token = Str::random(40);
        $invitation = Invitation::create([
            'colocation_id' => $id,
            'email' => $request->email,
            'token' => $token,
            'status' => 'pending',
            'expires_at' => now()->addDays(2),
            'created_by' => auth()->id(),
        ]);

        $url = route('invitation.show', ['token' => $token]);

        SendInvitationEmail::dispatch($invitation, $url);

        return back()->with([
            'success' => 'Invitation sent successfully!',
            'invitation_url' => $url
        ]);
    }
}
