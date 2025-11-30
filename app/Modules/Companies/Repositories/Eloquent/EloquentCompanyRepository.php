<?php
namespace App\Modules\Companies\Repositories\Eloquent;

use App\Modules\Companies\Repositories\CompanyRepositoryInterface;
use App\Modules\Companies\Models\Company;

class EloquentCompanyRepository implements CompanyRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15)
    {
        $query = Company::query();

        if (!empty($filters['q'])) {
            $q = $filters['q'];
            $query->where('name', 'ilike', "%{$q}%")
                  ->orWhere('domain', 'ilike', "%{$q}%");
        }

        return $query->paginate($perPage);
    }

    public function find(string $id)
    {
        return Company::findOrFail($id);
    }

    public function create(array $data)
    {
        return Company::create($data);
    }

    public function update(string $id, array $data)
    {
        $company = $this->find($id);
        $company->fill($data);
        $company->save();
        return $company;
    }

    public function delete(string $id): bool
    {
        $company = $this->find($id);
        return (bool) $company->delete();
    }
}
