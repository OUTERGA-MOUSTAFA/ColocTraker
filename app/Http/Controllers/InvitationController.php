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

        // expired
        if ($invitation->expires_at && $invitation->expires_at < now()) {
            $invitation->update(['status' => 'expired']);
            return redirect()->route('dashboard')
                ->with('error', 'L’invitation a expiré.');
        }

        // email check
        if (auth()->user()->email !== $invitation->email) {
            abort(403, 'Cette invitation ne vous appartient pas.');
        }

        if ($invitation->status === 'accepted') {
            return redirect()->route('dashboard')
                ->with('info', 'Invitation déjà acceptée.');
        }

        $colocation = $invitation->colocation;
        $userId = auth()->id();

        // check if was already member on this coloc
        $existing = $colocation->users()
            ->where('user_id', $userId)
            ->first();

        if ($existing) {

            // change left_at to null and role member, to be active on coloc
            $colocation->users()->updateExistingPivot($userId, [
                'left_at' => null,
                'role' => 'member'
            ]);
        } else {

            // return to colocation again
            $colocation->users()->attach($userId, [
                'role' => 'member',
                'created_at' => now()
            ]);
        }

        $invitation->update([
            'status' => 'accepted',
            'accepted_at' => now()
        ]);

        return redirect()->route('colocation.show', $colocation->id)
                        ->with('success', 'Bienvenue dans ' . $colocation->name);
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
