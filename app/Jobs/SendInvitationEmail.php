<?php

namespace App\Jobs;

use App\Mail\InvitationMail;
use App\Models\Invitation;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Mail;

class SendInvitationEmail implements ShouldQueue
{
    use Queueable;

    public $invitation;
    public $url;

    public function __construct(Invitation $invitation, $url)
    {
        $this->invitation = $invitation;
        $this->url = $url;
    }

    public function handle(): void
    {
        Mail::to($this->invitation->email)
            ->send(new InvitationMail($this->invitation, $this->url));
    }
}