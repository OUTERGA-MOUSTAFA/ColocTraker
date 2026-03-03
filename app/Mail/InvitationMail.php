<?php

namespace App\Mail;

use App\Models\Invitation;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class InvitationMail extends Mailable
{
    use Queueable, SerializesModels;

    private Invitation $invitation;
    private $url;

    public function __construct(Invitation $invitation, $url)
    {
        $this->invitation = $invitation;
        $this->url = $url;
    }

    public function build()
    {
        $invitation = $this->invitation;
        $url = $this->url;

        return $this->subject('You are invited!')
            ->view('email', compact('invitation', 'url'));
    }
}