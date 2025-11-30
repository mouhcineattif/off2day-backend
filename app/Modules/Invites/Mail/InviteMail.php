<?php
namespace App\Modules\Invites\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Modules\Invites\Models\Invite;

class InviteMail extends Mailable implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(protected Invite $invite, protected string $acceptUrl)
    {
        //
    }

    public function build()
    {
        return $this->subject('You are invited to join ' . ($this->invite->company->name ?? 'our workspace'))
                    ->markdown('emails.invite')
                    ->with([
                        'company' => $this->invite->company,
                        'acceptUrl' => $this->acceptUrl,
                        'expiresAt' => $this->invite->expires_at,
                        ]);
    }
}
