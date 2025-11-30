<?php
namespace App\Modules\Companies\Repositories;

interface CompanyRepositoryInterface
{
    public function all(array $filters = [], int $perPage = 15);
    public function find(string $id);
    public function create(array $data);
    public function update(string $id, array $data);
    public function delete(string $id): bool;
}
