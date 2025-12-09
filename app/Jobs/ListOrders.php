<?php

namespace App\Jobs;

use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\DB;
use stdClass;

class ListOrders implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    public $page_no, $branch_id;
    public $tries = 3;
    public $backoff = [2, 10, 20];


    /**
     * Create a new job instance.
     */
    public function __construct($page_no, $branch_id)
    {
        $this->page_no = $page_no;
        $this->branch_id  = $branch_id;
        //
    }

    /**
     * Execute the job.
     */
    public function handle()
    {
        //
        $curl = curl_init();
        $url = 'https://api.foodics.com/v5/orders?include=branch,payments.payment_method&page=' . $this->page_no . '&filter%5Bbranch_id%5D=' . $this->branch_id . '&filter%5Bstatus%5D=4&filter%5Bbusiness_date%5D=' . Carbon::yesterday()->toDateString();
        curl_setopt_array($curl, array(
            CURLOPT_URL => $url,
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'GET',
            CURLOPT_POSTFIELDS => "<file contents here>",
            CURLOPT_HTTPHEADER => array(
                'Authorization: Bearer ' . config('foodics.list_orders_token'),
                'Accept: application/json',
                'Content-Type: application/json'
            ),
        ));
        // $obj = new stdClass();
        
        // $obj->page_no = $this->page_no;
        // $obj->date = Carbon::yesterday()->toDateString();
        // $obj->branch_id = $this->branch_id;
        
        $response = curl_exec($curl);
        DB::table('logs')->insert([
            ['api_name' => 'Foodics Get Orders' , 'status_code' =>'200' ,'request' => $url, 'response' => $response, 'created_at' => Carbon::now()]
        ]);
        
        $orders = json_decode($response);
        // curl_close($curl);
        if ($orders != null) {
            if (isset($orders->data) && $orders->data != null) {
                $tax_rate = config('variables.tax_rate');
                
                foreach ($orders->data as $data) {
                    $tax_amount = 0;
                    $net_without_tax = 0;
                    if($data->total_price > 0){
                        $tax_amount = $data->total_price * $tax_rate / (100 + $tax_rate);
                        $net_without_tax = $data->total_price - $tax_amount;
                    }
                    if (Order::where('order_id', $data->id)->doesntExist()) {
                        Order::updateOrCreate([
                            'order_id' => $data->id,
                            'branch_id' => $data->branch->id,
                            'price' => $data->total_price,
                            'business_date' => $data->business_date,
                            'branch_name' =>  $data->branch->name,
                            'status' => $data->status,
                            'payment_method_id' => !empty($data->payments) ? $data->payments[0]->payment_method->id : null,
                            'tax_rate' => $tax_rate,
                            'tax_amount' => $tax_amount,
                            'net_amount_without_tax' => $net_without_tax
                        ]);
                    }
                }
            }
        }
        
    }
}
