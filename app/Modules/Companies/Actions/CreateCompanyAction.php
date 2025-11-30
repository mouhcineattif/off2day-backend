<?php
namespace App\Modules\Companies\Actions;

use App\Modules\Companies\Services\CompanyService;
use App\Modules\Companies\Models\Company;

class CreateCompanyAction
{
    public function __construct(protected CompanyService $service) {}

    public function execute(array $data): Company
    {
        return $this->service->create($data);
    }
}
