<?php
namespace App\Modules\Invites\Services;

use App\Modules\Employees\Models\EmployeeProfile;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Modules\Invites\Repositories\InviteRepositoryInterface;
use App\Modules\Invites\Models\Invite;
use Carbon\Carbon;

class InviteService
{
    public function __construct(protected InviteRepositoryInterface $repo) {}

    /**
     * Create invite and return plain token (plain token must be emailed)
     *
     * @param string $companyId
     * @param string $email
     * @param string $role
     * @param string|null $createdBy
     * @param int $expiresDays
     * @return array ['invite' => Invite, 'plain_token' => string]
     */
    public function createInvite(string $companyId, string $email, string $role = 'employee', ?string $createdBy = null, int $expiresDays = 7): array
    {
        $plain = bin2hex(random_bytes(24));
        $hash = hash('sha256', $plain);

        $invite = $this->repo->create([
            'company_id' => $companyId,
            'email' => strtolower(trim($email)),
            'token_hash' => $hash,
            'role' => $role,
            'expires_at' => Carbon::now()->addDays($expiresDays),
            'redeemed' => false,
            'created_by' => $createdBy,
        ]);

        return ['invite' => $invite, 'plain_token' => $plain];
    }

    /**
     * Accept invite by plain token -> returns created/linked User
     */
    public function acceptInvite(string $plainToken, array $userData)
    {
        $hash = hash('sha256', $plainToken);
        $invite = $this->repo->findByTokenHash($hash);
        if (! $invite) {
            throw new \InvalidArgumentException('Invalid invite token.');
        }

        if ($invite->redeemed) {
            throw new \InvalidArgumentException('Invite already redeemed.');
        }

        if ($invite->isExpired()) {
            throw new \InvalidArgumentException('Invite expired.');
        }

        return DB::transaction(function() use ($invite, $userData) {
            // Check if user exists
            $userModel = \App\Models\User::where('email', $invite->email)->first();

            if (! $userModel) {
                // create user
                $userModel = \App\Models\User::create([
                    'id' => Str::uuid()->toString(),
                    'company_id' => $invite->company_id,
                    'email' => $invite->email,
                    'full_name' => $userData['full_name'] ?? null,
                    'password' => isset($userData['password']) ? bcrypt($userData['password']) : null,
                    'role' => $invite->role ?? 'employee',
                    'is_active' => true,
                ]);
            } else {
                // if user exists but not linked to company, link them
                if (!$userModel->company_id) {
                    $userModel->company_id = $invite->company_id;
                }
                $userModel->role = $invite->role ?? $userModel->role;
                $userModel->save();
            }

            // create employee profile if not exists
            if (! EmployeeProfile::where('user_id', $userModel->id)->exists()) {
                EmployeeProfile::create([
                    'id' => Str::uuid()->toString(),
                    'user_id' => $userModel->id,
                    'company_id' => $invite->company_id,
                    'job_title' => $userData['job_title'] ?? null,
                    'hire_date' => $userData['hire_date'] ?? now()->toDateString(),
                ]);
            }

            // mark invite redeemed
            $this->repo->markRedeemed($invite);

            return $userModel;
        });
    }
}
