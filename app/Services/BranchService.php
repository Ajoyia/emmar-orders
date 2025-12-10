<?php

namespace App\Services;

use App\Models\Branch;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class BranchService
{
    public function getAll(): Collection
    {
        return Branch::orderBy('id', 'desc')->get();
    }

    public function getPaginated(int $perPage = 5): LengthAwarePaginator
    {
        return Branch::orderBy('id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?Branch
    {
        return Branch::find($id);
    }

    public function create(array $data): Branch
    {
        return Branch::create($data);
    }

    public function update(Branch $branch, array $data): bool
    {
        return $branch->fill($data)->save();
    }

    public function delete(Branch $branch): bool
    {
        return $branch->delete();
    }
}

