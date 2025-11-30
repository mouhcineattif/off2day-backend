<?php
namespace App\Modules\Companies\Controllers;

use App\Http\Controllers\Controller;
use App\Modules\Companies\Requests\StoreCompanyRequest;
use App\Modules\Companies\Requests\UpdateCompanyRequest;
use Illuminate\Http\Request;
use App\Modules\Companies\Services\CompanyService;
use App\Modules\Companies\Actions\CreateCompanyAction;
use App\Modules\Companies\Actions\UpdateCompanyAction;
use App\Modules\Companies\Resources\CompanyResource;
use App\Modules\Companies\Models\Company;

class CompanyController extends Controller
{
    public function __construct(protected CompanyService $service)
    {
        // Authorization is handled by route middleware or policies
    }

    public function index(Request $request)
    {
        $perPage = (int) $request->get('per_page', 15);
        $filters = $request->only('q');
        $list = $this->service->list($filters, $perPage);
        return CompanyResource::collection($list);
    }

    public function store(StoreCompanyRequest $request, CreateCompanyAction $action)
    {
        $data = $request->validated();
        $company = $action->execute($data);
        return new CompanyResource($company);
    }

    // Laravel will resolve the Company model and apply the 'view' policy
    public function show(Company $company)
    {
        return new CompanyResource($company);
    }

    // Laravel will resolve the Company model and apply the 'update' policy
    public function update(Company $company, UpdateCompanyRequest $request, UpdateCompanyAction $action)
    {
        $data = $request->validated();
        $updated = $action->execute($company->id, $data);
        return new CompanyResource($updated);
    }

    // Laravel will resolve the Company model and apply the 'delete' policy
    public function destroy(Company $company)
    {
        $this->service->delete($company->id);
        return response()->noContent();
    }
}
