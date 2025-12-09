<?php

namespace App\Services;

use App\Models\Order;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

class OrderAggregationService
{
    public function getOrdersByDate(string $date): Collection
    {
        return Order::where('business_date', $date)->get();
    }

    public function getOrdersByDateRange(string $startDate, string $endDate): Collection
    {
        return Order::whereBetween('business_date', [$startDate, $endDate])->get();
    }

    public function getPaymentMethodAggregations(string $date): Collection
    {
        return DB::table('orders')
            ->join('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.payment_id')
            ->groupBy('payment_methods.emmar_mapping')
            ->where('orders.business_date', $date)
            ->where('payment_methods.share_with_emaar', 1)
            ->select(
                'payment_methods.emmar_mapping',
                DB::raw('SUM(orders.net_amount_without_tax) as sum_orders'),
                DB::raw('count(orders.net_amount_without_tax) as count_orders')
            )
            ->get();
    }

    public function getPaymentMethodAggregationsByDateRange(string $startDate, string $endDate): Collection
    {
        return DB::table('orders')
            ->join('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.payment_id')
            ->whereBetween('orders.business_date', [$startDate, $endDate])
            ->where('payment_methods.share_with_emaar', 1)
            ->groupBy('payment_methods.emmar_mapping')
            ->select(
                'payment_methods.emmar_mapping',
                DB::raw('SUM(orders.net_amount_without_tax) as sum_orders'),
                DB::raw('count(orders.net_amount_without_tax) as count_orders')
            )
            ->get();
    }

    public function calculateChannelData(Collection $aggregations): array
    {
        $channels = [
            'Ch_DineIn' => ['amount' => 0, 'count' => 0],
            'Ch_Talabat' => ['amount' => 0, 'count' => 0],
            'Ch_Drivu' => ['amount' => 0, 'count' => 0],
        ];

        foreach ($aggregations as $result) {
            $mapping = $result->emmar_mapping;
            if (isset($channels[$mapping])) {
                $channels[$mapping]['amount'] = round($result->sum_orders, 2);
                $channels[$mapping]['count'] = $result->count_orders;
            }
        }

        return $channels;
    }
}

