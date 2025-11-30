<?php
namespace App\Modules\Invites\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\SerializesModels;
use App\Modules\Invites\Models\Invite;
use App\Modules\Invites\Mail\InviteMail;
use Illuminate\Support\Facades\Mail;

class SendInviteEmailJob implements ShouldQueue
{
    use Queueable, SerializesModels;

    public function __construct(protected Invite $invite, protected string $plainToken)
    {
    }

    public function handle()
    {
        $url = config('app.frontend_url') ?? env('FRONTEND_URL', null);
        $acceptUrl = rtrim($url, '/') . '/invite/accept?token=' . $this->plainToken;

        Mail::to($this->invite->email)
            ->send(new InviteMail($this->invite, $acceptUrl));
    }
}
