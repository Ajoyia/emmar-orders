<?php

namespace App\Services;

use App\Jobs\ListOrders;
use App\Models\Branch;
use App\Models\PaymentMethod;
use App\Services\FoodicsApiClient;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class OrderService
{
    private FoodicsApiClient $foodicsApiClient;

    public function __construct(FoodicsApiClient $foodicsApiClient)
    {
        $this->foodicsApiClient = $foodicsApiClient;
    }

    public function getFilteredOrders(array $filters, int $perPage = 10): LengthAwarePaginator
    {
        $query = DB::table('orders')
            ->leftJoin('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.payment_id')
            ->leftJoin('branches', 'orders.branch_id', '=', 'branches.branch_id');

        if (isset($filters['branch_id']) && !empty($filters['branch_id'])) {
            $query->where('orders.branch_id', $filters['branch_id']);
        }

        if (isset($filters['business_date']) && !empty($filters['business_date'])) {
            $query->where('orders.business_date', $filters['business_date']);
        }

        if (isset($filters['payment_method']) && !empty($filters['payment_method'])) {
            $query->where('orders.payment_method_id', $filters['payment_method']);
        }

        if (isset($filters['send_to_emaar']) && $filters['send_to_emaar'] !== null) {
            $query->where('orders.is_sent_to_emaar', (int) $filters['send_to_emaar']);
        }

        return $query
            ->orderBy('orders.id', 'desc')
            ->select(
                'orders.price as price',
                'payment_methods.name as payment_method',
                'branches.name as branch_name',
                'orders.order_id as order_id',
                'orders.is_sent_to_emaar as is_sent_to_emaar',
                'orders.net_amount_without_tax as net_amount_without_tax',
                'orders.business_date as business_date'
            )
            ->paginate($perPage);
    }

    public function fetchOrdersFromFoodics(string $branchId): void
    {
        DB::table('jobs')->truncate();

        $businessDate = \Carbon\Carbon::yesterday()->toDateString();
        $orders = $this->foodicsApiClient->getOrders($branchId, $businessDate);

        if ($orders !== null && isset($orders->meta) && isset($orders->meta->last_page) && $orders->meta->last_page > 0) {
            for ($page = 1; $page <= $orders->meta->last_page; $page++) {
                dispatch(new ListOrders($page, $branchId))->delay(now()->addSeconds(5));
            }
        }
    }

    public function getBranches(): Collection
    {
        return Branch::get();
    }

    public function getPaymentMethods(): Collection
    {
        return PaymentMethod::get();
    }
}

