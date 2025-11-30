<?php
namespace App\Modules\Companies\Actions;

use App\Modules\Companies\Services\CompanyService;
use App\Modules\Companies\Models\Company;

class UpdateCompanyAction
{
    public function __construct(protected CompanyService $service) {}

    public function execute(string $id, array $data): Company
    {
        return $this->service->update($id, $data);
    }
}
