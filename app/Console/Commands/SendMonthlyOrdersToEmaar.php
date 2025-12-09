<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Order;
use App\Services\EmaarApiClient;
use App\Services\EmaarPayloadBuilder;
use App\Services\LogService;
use App\Services\OrderAggregationService;
use Carbon\Carbon;
use Illuminate\Console\Command;

class SendMonthlyOrdersToEmaar extends Command
{
    protected $signature = 'monthly:orders';

    protected $description = 'Send Monthly Orders To Emaar API';

    private EmaarApiClient $emaarApiClient;
    private OrderAggregationService $orderAggregationService;
    private EmaarPayloadBuilder $payloadBuilder;
    private LogService $logService;

    public function __construct(
        EmaarApiClient $emaarApiClient,
        OrderAggregationService $orderAggregationService,
        EmaarPayloadBuilder $payloadBuilder,
        LogService $logService
    ) {
        parent::__construct();
        $this->emaarApiClient = $emaarApiClient;
        $this->orderAggregationService = $orderAggregationService;
        $this->payloadBuilder = $payloadBuilder;
        $this->logService = $logService;
    }

    public function handle(): void
    {
        $firstDateOfLastMonth = Carbon::now()->subMonth()->startOfMonth()->toDateString();
        $lastDateOfLastMonth = Carbon::now()->subMonth()->endOfMonth()->toDateString();
        $branchId = config('emaar.branch_id');

        $branch = Branch::where('branch_id', $branchId)->first();

        if (!$branch) {
            $this->error("Branch not found for branch_id: {$branchId}");
            return;
        }

        $monthlyOrders = $this->orderAggregationService->getOrdersByDateRange(
            $firstDateOfLastMonth,
            $lastDateOfLastMonth
        );

        if ($monthlyOrders->isEmpty()) {
            $this->info("No orders found for period: {$firstDateOfLastMonth} to {$lastDateOfLastMonth}");
            return;
        }

        $aggregations = $this->orderAggregationService->getPaymentMethodAggregationsByDateRange(
            $firstDateOfLastMonth,
            $lastDateOfLastMonth
        );
        $channelData = $this->orderAggregationService->calculateChannelData($aggregations);

        $totalSales = round($monthlyOrders->sum('net_amount_without_tax'), 2);
        $transactionCount = $monthlyOrders->count();

        $payload = $this->payloadBuilder->buildMonthlyPayload(
            $branch->unit_no,
            $branch->lease_code,
            $firstDateOfLastMonth,
            $lastDateOfLastMonth,
            $transactionCount,
            $totalSales,
            $channelData
        );

        $response = $this->emaarApiClient->sendMonthlyOrders($payload);

        if ($response === null) {
            $this->error('Failed to send monthly orders to Emaar API');
            $this->logService->logApiRequest(
                'Emaar Monthly Orders',
                null,
                $payload,
                'Request failed'
            );
            return;
        }

        $statusCode = $response->Code ?? null;

        $this->logService->logApiRequest(
            'Emaar Monthly Orders',
            $statusCode,
            $payload,
            json_encode($response)
        );

        if ($statusCode === "200") {
            Order::whereIn('id', $monthlyOrders->pluck('id'))->update([
                'is_sent_to_emaar' => 1,
                'emaar_sent_date' => Carbon::now()
            ]);

            $this->info("Successfully sent {$transactionCount} orders to Emaar");
        } else {
            $this->warn("Emaar API returned status code: {$statusCode}");
        }
    }
}
