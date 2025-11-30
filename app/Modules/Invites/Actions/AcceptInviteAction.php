<?php
namespace App\Modules\Invites\Actions;

use App\Modules\Invites\Services\InviteService;

class AcceptInviteAction
{
    public function __construct(protected InviteService $service) {}

    /**
     * @param string $plainToken
     * @param array $userData ['full_name','password','job_title',...]
     * @return \App\Models\User
     */
    public function execute(string $plainToken, array $userData)
    {
        return $this->service->acceptInvite($plainToken, $userData);
    }
}
