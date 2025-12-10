<?php

namespace App\Services;

use App\Models\PaymentMethod;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class PaymentMethodService
{
    public function getAll(): Collection
    {
        return PaymentMethod::orderBy('id', 'desc')->get();
    }

    public function getPaginated(int $perPage = 10): LengthAwarePaginator
    {
        return PaymentMethod::orderBy('id', 'desc')->paginate($perPage);
    }

    public function findById(int $id): ?PaymentMethod
    {
        return PaymentMethod::find($id);
    }

    public function create(array $data): PaymentMethod
    {
        return PaymentMethod::create($data);
    }

    public function update(PaymentMethod $paymentMethod, array $data): bool
    {
        return $paymentMethod->fill($data)->save();
    }

    public function delete(PaymentMethod $paymentMethod): bool
    {
        return $paymentMethod->delete();
    }
}

