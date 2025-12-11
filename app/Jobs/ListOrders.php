<?php

namespace App\Jobs;

use App\Models\Order;
use App\Services\FoodicsApiClient;
use App\Services\LogService;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Throwable;

class ListOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public int $tries = 3;
    public array $backoff = [2, 10, 20];

    public function __construct(
        public int $page_no,
        public string $branch_id
    ) {}

    public function handle(
        FoodicsApiClient $apiClient,
        LogService $logService
    ): void {
        $businessDate = Carbon::yesterday()->toDateString();
        $requestUrl = $this->buildRequestUrl($businessDate);

        try {
            $response = $apiClient->getOrders($this->branch_id, $businessDate, $this->page_no);

            if ($response === null) {
                $logService->logApiRequest(
                    'Foodics Get Orders',
                    '500',
                    $requestUrl,
                    'API request failed or returned null'
                );
                throw new \RuntimeException('Failed to fetch orders from Foodics API');
            }

            $logService->logApiRequest(
                'Foodics Get Orders',
                '200',
                $requestUrl,
                json_encode($response)
            );

            if (isset($response->data) && is_array($response->data) && count($response->data) > 0) {
                $this->processOrders($response->data);
            }
        } catch (Throwable $e) {
            $logService->logApiRequest(
                'Foodics Get Orders',
                '500',
                $requestUrl,
                $e->getMessage()
            );
            Log::error('ListOrders job failed', [
                'page_no' => $this->page_no,
                'branch_id' => $this->branch_id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            throw $e;
        }
    }

    private function buildRequestUrl(string $businessDate): string
    {
        $baseUrl = 'https://api.foodics.com/v5/orders';
        $params = http_build_query([
            'include' => 'branch,payments.payment_method',
            'page' => $this->page_no,
            'filter[branch_id]' => $this->branch_id,
            'filter[status]' => 4,
            'filter[business_date]' => $businessDate,
        ]);
        return $baseUrl . '?' . $params;
    }

    private function processOrders(array $ordersData): void
    {
        $taxRate = config('variables.tax_rate');

        DB::beginTransaction();
        try {
            foreach ($ordersData as $orderData) {
                if (!isset($orderData->id) || !isset($orderData->branch)) {
                    continue;
                }

                $orderId = $orderData->id;
                $totalPrice = $orderData->total_price ?? 0;

                if (Order::where('order_id', $orderId)->exists()) {
                    continue;
                }

                [$taxAmount, $netWithoutTax] = $this->calculateTax($totalPrice, $taxRate);

                Order::create([
                    'order_id' => $orderId,
                    'branch_id' => $orderData->branch->id,
                    'price' => $totalPrice,
                    'business_date' => $orderData->business_date ?? null,
                    'branch_name' => $orderData->branch->name ?? null,
                    'status' => $orderData->status ?? null,
                    'payment_method_id' => $this->extractPaymentMethodId($orderData),
                    'tax_rate' => $taxRate,
                    'tax_amount' => $taxAmount,
                    'net_amount_without_tax' => $netWithoutTax,
                ]);
            }

            DB::commit();
        } catch (Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }

    private function calculateTax(float $totalPrice, float $taxRate): array
    {
        if ($totalPrice <= 0) {
            return [0, 0];
        }

        $taxAmount = $totalPrice * $taxRate / (100 + $taxRate);
        $netWithoutTax = $totalPrice - $taxAmount;

        return [$taxAmount, $netWithoutTax];
    }

    private function extractPaymentMethodId(object $orderData): ?int
    {
        if (empty($orderData->payments) || !is_array($orderData->payments)) {
            return null;
        }

        $firstPayment = $orderData->payments[0] ?? null;
        if ($firstPayment === null || !isset($firstPayment->payment_method)) {
            return null;
        }

        return $firstPayment->payment_method->id ?? null;
    }
}
