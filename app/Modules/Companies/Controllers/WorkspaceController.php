<?php
namespace App\Modules\Companies\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Modules\Companies\Actions\CreateWorkspaceAction;
use App\Modules\Companies\Resources\CompanyResource;

class WorkspaceController extends Controller
{
    public function store(Request $request, CreateWorkspaceAction $action)
    {
        $data = $request->validate([
            'company.name' => 'required|string|max:255',
            'company.domain' => 'nullable|string|max:255',
            'owner.first_name' => 'required|string|max:100',
            'owner.last_name' => 'nullable|string|max:100',
            'owner.email' => 'required|email|max:255',
            'owner.password' => 'required|string|min:8|confirmed',
            'plan' => 'nullable|string' // optional plan selection
        ]);

        $result = $action->execute($data['company'], $data['owner'], $data['plan'] ?? null, $request->ip());

        // if SPA using sanctum session: login user and return session cookie,
        // else return token included in $result
        return response()->json($result, 201);
    }
}
