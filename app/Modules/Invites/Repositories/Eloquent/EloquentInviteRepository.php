<?php
namespace App\Modules\Invites\Repositories\Eloquent;

use App\Modules\Invites\Repositories\InviteRepositoryInterface;
use App\Modules\Invites\Models\Invite;

class EloquentInviteRepository implements InviteRepositoryInterface
{
    public function create(array $data): Invite
    {
        return Invite::create($data);
    }

    public function findByTokenHash(string $tokenHash): ?Invite
    {
        return Invite::where('token_hash', $tokenHash)->first();
    }

    public function find(string $id): ?Invite
    {
        return Invite::find($id);
    }

    public function markRedeemed(Invite $invite): Invite
    {
        $invite->redeemed = true;
        $invite->save();
        return $invite;
    }

    public function paginateForCompany(string $companyId, int $perPage = 15)
    {
        return Invite::where('company_id', $companyId)->orderBy('created_at', 'desc')->paginate($perPage);
    }
}
