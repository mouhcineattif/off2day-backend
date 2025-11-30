<?php
namespace App\Modules\Companies\Services;

use App\Modules\Companies\Repositories\CompanyRepositoryInterface;

class CompanyService
{
    public function __construct(protected CompanyRepositoryInterface $repo) {}

    public function list(array $filters = [], int $perPage = 15)
    {
        return $this->repo->all($filters, $perPage);
    }

    public function get(string $id)
    {
        return $this->repo->find($id);
    }

    public function create(array $data)
    {
        // business rules could go here (e.g., domain normalization)
        if (!empty($data['domain'])) {
            $data['domain'] = strtolower(trim($data['domain']));
        }
        return $this->repo->create($data);
    }

    public function update(string $id, array $data)
    {
        if (!empty($data['domain'])) {
            $data['domain'] = strtolower(trim($data['domain']));
        }
        return $this->repo->update($id, $data);
    }

    public function delete(string $id)
    {
        return $this->repo->delete($id);
    }
}
