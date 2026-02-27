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

        if (now()->greaterThan($invitation->expires_at)) {
            abort(403, 'Invitation expired');
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

        $url = route('invitation.show', ['invitation' => $token]);

        SendInvitationEmail::dispatch($invitation, $url);

        return redirect()
            ->route('colocation.show', $id)
            ->with([
                'success' => 'Invitation sent successfully!',
                'invitation_url' => $url
            ]);
    }

    public function accept($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();


        if (!auth()->check()) {
            return redirect()->route('login');
        }
        // expired link
        if ($invitation->expires_at && $invitation->expires_at < now()) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('dashboard')
                ->with('error', 'L’invitation a expiré.');
        }
        // same email
        if (auth()->user()->email !== $invitation->email) {
            abort(403, 'Cette invitation ne vous appartient pas.');
        }

        if ($invitation->status === 'accepted') {
            return redirect()->route('dashboard')
                ->with('info', 'Invitation déjà acceptée.');
        }

        $colocation = $invitation->colocation;

        if (!$colocation->users()->where('user_id', auth()->id())->exists()) {
            $colocation->users()->attach(auth()->id(), [
                'role' => 'member'
            ]);
        }

        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);
        $colocationName = $colocation->name;
        return redirect()
            ->route('colocation.show', $colocation->id)
            ->with('success', 'Bienvenue dans ' . $colocationName);
    }


    public function refuse($token)
    {
        $invitation = Invitation::where('token', $token)->firstOrFail();

        // auth
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        // token expired
        if ($invitation->expires_at && $invitation->expires_at < now()) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('dashboard')
                ->with('error', 'L’invitation a expiré.');
        }

        // same email
        if (auth()->user()->email !== $invitation->email) {
            abort(403, 'Cette invitation ne vous appartient pas.');
        }

        // tester already accepted ola refused
        if ($invitation->status !== 'pending') {
            return redirect()->route('dashboard')
                ->with('info', 'Invitation déjà traitée.');
        }

        $invitation->update([
            'status' => 'refused'
        ]);

        return redirect()->route('dashboard')
            ->with('success', 'Invitation refusée.');
    }
}
