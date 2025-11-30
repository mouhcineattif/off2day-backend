<?php
namespace App\Modules\Invites\Actions;

use App\Modules\Invites\Services\InviteService;

class CreateInviteAction
{
    public function __construct(protected InviteService $service) {}

    public function execute(string $companyId, string $email, string $role = 'employee', ?string $createdBy = null, int $expiresDays = 7): array
    {
        return $this->service->createInvite($companyId, $email, $role, $createdBy, $expiresDays);
    }
}
