<?php
namespace App\Modules\Invites\Repositories;

use App\Modules\Invites\Models\Invite;

interface InviteRepositoryInterface
{
    public function create(array $data): Invite;
    public function findByTokenHash(string $tokenHash): ?Invite;
    public function find(string $id): ?Invite;
    public function markRedeemed(Invite $invite): Invite;
    public function paginateForCompany(string $companyId, int $perPage = 15);
}
