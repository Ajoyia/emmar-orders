<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class FoodicsApiClient
{
    private string $baseUrl = 'https://api.foodics.com/v5';
    private string $token;

    public function __construct()
    {
        $this->token = config('foodics.list_orders_token');
    }

    public function getOrders(string $branchId, string $businessDate, int $page = 1): ?object
    {
        try {
            $url = $this->baseUrl . '/orders';
            $response = Http::withHeaders([
                'Authorization' => 'Bearer ' . $this->token,
                'Accept' => 'application/json',
                'Content-Type' => 'application/json',
            ])->get($url, [
                'include' => 'branch,payments.payment_method',
                'filter[branch_id]' => $branchId,
                'filter[status]' => 4,
                'filter[business_date]' => $businessDate,
                'page' => $page,
            ]);

            if ($response->successful()) {
                return json_decode($response->body());
            }

            Log::error('Foodics API request failed', [
                'status' => $response->status(),
                'body' => $response->body(),
            ]);

            return null;
        } catch (\Exception $e) {
            Log::error('Foodics API exception', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return null;
        }
    }
}

