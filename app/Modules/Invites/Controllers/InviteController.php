<?php
namespace App\Modules\Invites\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Invites\Requests\CreateInviteRequest;
use App\Modules\Invites\Requests\AcceptInviteRequest;
use App\Modules\Invites\Actions\CreateInviteAction;
use App\Modules\Invites\Actions\AcceptInviteAction;
use App\Modules\Invites\Jobs\SendInviteEmailJob;
use App\Modules\Invites\Repositories\InviteRepositoryInterface;

class InviteController extends Controller
{
    public function __construct(protected InviteRepositoryInterface $repo)
    {
        $this->middleware('auth:sanctum')->only(['store', 'index', 'revoke']);
    }

    /**
     * Create and send invite
     */
    public function store(CreateInviteRequest $request, CreateInviteAction $action)
    {
        $company = $request->route('company'); // route-model bound Company
        $this->authorize('invite', $company); // ensure company_admin via policy

        $expires = $request->input('expires_days', 7);
        $role = $request->input('role', 'employee');

        $result = $action->execute($company->id, $request->input('email'), $role, $request->user()->id, $expires);

        // queue email with plain token
        $plain = $result['plain_token'];
        $invite = $result['invite'];
        dispatch(new SendInviteEmailJob($invite, $plain));

        return response()->json(['invite_id' => $invite->id], 201);
    }

    /**
     * Accept invite (public because token authorizes)
     */
    public function accept(AcceptInviteRequest $request, AcceptInviteAction $action)
    {
        $userData = [
            'full_name' => $request->input('full_name'),
            'password' => $request->input('password'),
            'job_title' => $request->input('job_title'),
            'hire_date' => $request->input('hire_date'),
        ];

        $user = $action->execute($request->input('token'), $userData);

        // Optionally auto-login user for SPA:
        auth()->login($user);
        $request->session()->regenerate();

        return response()->json(['user' => $user], 201);
    }

    /**
     * List invites for company
     */
    public function index(Request $request)
    {
        $company = $request->route('company');
        $this->authorize('view', $company); // only admins or super_admin per policy

        $perPage = (int) $request->get('per_page', 15);
        $list = $this->repo->paginateForCompany($company->id, $perPage);
        return response()->json($list);
    }

    /**
     * Revoke / delete invite
     */
    public function revoke(Request $request, $inviteId)
    {
        $invite = $this->repo->find($inviteId);
        if (! $invite) {
            return response()->json(['message' => 'Not found'], 404);
        }

        $company = $request->route('company');
        $this->authorize('invite', $company);

        $invite->delete();
        return response()->noContent();
    }
}
