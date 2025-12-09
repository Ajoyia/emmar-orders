<?php

namespace App\Console\Commands;

use App\Jobs\ListOrders;
use Carbon\Carbon;
use Illuminate\Console\Command;

class FetchYesterdayOrdersFromFoodics extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'foodics:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch Yesteday Orders From Foodics API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        $branch_id = '95687127-a5d3-47e9-9090-3316e6265f5c';
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.foodics.com/v5/orders?include=branch,payments.payment_method&filter%5Bbranch_id%5D='. $branch_id .'&filter%5Bstatus%5D=4&filter%5Bbusiness_date%5D=' . Carbon::yesterday()->toDateString(),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => "<file contents here>",
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . env('LIST_ORDERS_BEARER_TOKEN'),
                'Accept: application/json',
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        $orders = json_decode($response);

        if ($orders->meta != null) {
            if ($orders->meta->last_page > 0) {

                for ($i = 1; $i < $orders->meta->last_page + 1; $i++) {
                    dispatch(new ListOrders($i, $branch_id))->delay(now()->addSeconds(5));
                }
            }
        }
    }
}
