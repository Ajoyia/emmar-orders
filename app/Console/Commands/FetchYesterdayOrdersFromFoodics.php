<?php

namespace App\Console\Commands;

use App\Jobs\ListOrders;
use App\Services\FoodicsApiClient;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FetchYesterdayOrdersFromFoodics extends Command
{
    protected $signature = 'foodics:orders';

    protected $description = 'Fetch Yesterday Orders From Foodics API';

    private FoodicsApiClient $foodicsApiClient;

    public function __construct(FoodicsApiClient $foodicsApiClient)
    {
        parent::__construct();
        $this->foodicsApiClient = $foodicsApiClient;
    }

    public function handle(): void
    {
        $branchId = config('emaar.branch_id');
        $businessDate = Carbon::yesterday()->toDateString();

        $orders = $this->foodicsApiClient->getOrders($branchId, $businessDate);

        if ($orders === null) {
            $this->error('Failed to fetch orders from Foodics API');
            return;
        }

        if (!isset($orders->meta) || !isset($orders->meta->last_page) || $orders->meta->last_page <= 0) {
            $this->info('No orders found or invalid response');
            return;
        }

        $lastPage = $orders->meta->last_page;

        for ($page = 1; $page <= $lastPage; $page++) {
            dispatch(new ListOrders($page, $branchId))->delay(now()->addSeconds(5));
        }

        $this->info("Dispatched {$lastPage} job(s) to fetch orders");
    }
}
