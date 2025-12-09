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

class SendDailyOrdersToEmaar extends Command
{
    protected $signature = 'daily:orders';

    protected $description = 'Send Daily Orders To Emaar API';

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
        $yesterday = Carbon::yesterday()->toDateString();
        $branchId = config('emaar.branch_id');

        $branch = Branch::where('branch_id', $branchId)->first();

        if (!$branch) {
            $this->error("Branch not found for branch_id: {$branchId}");
            return;
        }

        $yesterdayOrders = $this->orderAggregationService->getOrdersByDate($yesterday);

        if ($yesterdayOrders->isEmpty()) {
            $this->info('No orders found for yesterday');
            return;
        }

        $aggregations = $this->orderAggregationService->getPaymentMethodAggregations($yesterday);
        $channelData = $this->orderAggregationService->calculateChannelData($aggregations);

        $netSales = round($yesterdayOrders->sum('net_amount_without_tax'), 2);
        $transactionCount = $yesterdayOrders->count();

        $payload = $this->payloadBuilder->buildDailyPayload(
            $branch->unit_no,
            $branch->lease_code,
            $yesterday,
            $transactionCount,
            $netSales,
            $channelData
        );

        $response = $this->emaarApiClient->sendDailyOrders($payload);

        if ($response === null) {
            $this->error('Failed to send daily orders to Emaar API');
            $this->logService->logApiRequest(
                'Emaar Daily Orders',
                null,
                $payload,
                'Request failed'
            );
            return;
        }

        $statusCode = $response->Code ?? null;

        $this->logService->logApiRequest(
            'Emaar Daily Orders',
            $statusCode,
            $payload,
            json_encode($response)
        );

        if ($statusCode === "200") {
            Order::whereIn('id', $yesterdayOrders->pluck('id'))->update([
                'is_sent_to_emaar' => 1,
                'emaar_sent_date' => Carbon::now()
            ]);

            $this->info("Successfully sent {$transactionCount} orders to Emaar");
        } else {
            $this->warn("Emaar API returned status code: {$statusCode}");
        }
    }
}
