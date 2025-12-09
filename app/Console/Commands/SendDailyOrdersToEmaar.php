<?php

namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use stdClass;

class SendDailyOrdersToEmaar extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'daily:orders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Daily Orders To Emaar API';

    /**
     * Execute the console command.
     */
    public function handle(): void
    {
        //
        $Ch_DineIncnt = 0;
        $Ch_DineIn = 0;
        $Ch_Talabat = 0;
        $Ch_Talabatcnt = 0;
        $Ch_Drivu = 0;
        $Ch_Drivucnt = 0;
        $branch_unit_no = '';
        $yesterday = Carbon::yesterday()->toDateString();
        $branch_lease_code = '';

        $yesterday_orders = Order::where('business_date', $yesterday)->get();
        $results = DB::table('orders')
            ->join('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.payment_id')
            ->groupBy('payment_methods.emmar_mapping')
            ->where('orders.business_date', Carbon::yesterday()->toDateString())
            ->where('payment_methods.share_with_emaar', 1)
            ->select('payment_methods.emmar_mapping', DB::raw('SUM(orders.net_amount_without_tax) as sum_orders') ,DB::raw('count(orders.net_amount_without_tax) as count_orders'))
            ->get();
        $branch = Branch::where('branch_id', '95687127-a5d3-47e9-9090-3316e6265f5c')->first();
        if($branch->count()>0){
            $branch_unit_no = $branch->unit_no;
            $branch_lease_code = $branch->lease_code;
        }
        if($results->count() > 0){
            foreach($results as $result){
                switch ($result->emmar_mapping) {
                    case 'Ch_DineIn':
                        $Ch_DineIn = round($result->sum_orders, 2);
                        $Ch_DineIncnt = $result->count_orders;
                        break;
                    case 'Ch_Talabat':
                        $Ch_Talabat = round($result->sum_orders, 2);
                        $Ch_Talabatcnt = $result->count_orders;
                        break;
                    case 'Ch_Drivu':
                        $Ch_Drivu = round($result->sum_orders, 2);
                        $Ch_Drivucnt = $result->count_orders;
                        break;
                }
            }
        }
        $curl = curl_init();
        $arr = array(
            CURLOPT_URL => config('emaar.daily_url'),
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => '{
        "SalesDataCollection": {
            "SalesInfo": [
            {
                "UnitNo": "' . $branch_unit_no . '",
                "LeaseCode": "' . $branch_lease_code . '",
                "SalesDate": "' . $yesterday . '",
                "TransactionCount": "' . $yesterday_orders->count() . '",
                "NetSales": "' . round($yesterday_orders->sum('net_amount_without_tax'), 2) . '",
                "FandBSplit": [
                {
                    "Ch_Zomato": 0,
                    "Ch_Deliveroo": 0,
                    "Ch_DineIn": "' . $Ch_DineIn . '",
                    "Ch_Talabat": "' . $Ch_Talabat . '",
                    "Ch_CleanEatMe": 0,
                    "Ch_Noon": 0,
                    "Ch_MunchOn": 0,
                    "Ch_CareemNOW": 0,
                    "Ch_EatEasy": 0,
                    "Ch_UberEat": 0,
                    "Ch_OwnDelivery": 0,
                    "Ch_NowNow": 0,
                    "Ch_Amazon": 0,
                    "Ch_CofeApp": 0,
                    "Ch_Instashop": 0,
                    "Ch_Tawseel": 0,
                    "Ch_Kitopi": 0,
                    "Ch_ChatFood": 0,
                    "Ch_EMAREAT": 0,
                    "Ch_Foodate": 0,
                    "Ch_CoffeePik": 0,
                    "Ch_Drivu": "' . $Ch_Drivu . '",
                    "Ch_Littlemees": 0,
                    "Ch_Swan": 0,
                    "Ch_JoiGifts": 0,
                    "Ch_Zomatocnt": 0,
                    "Ch_Deliveroocnt": 0,
                    "Ch_DineIncnt": "' . $Ch_DineIncnt . '",
                    "Ch_Talabatcnt":  "' . $Ch_Talabatcnt . '",
                    "Ch_CleanEatMecnt": 0,
                    "Ch_Nooncnt": 0,
                    "Ch_MunchOncnt": 0,
                    "Ch_CareemNOWcnt": 0,
                    "Ch_EatEasycnt": 0,
                    "Ch_UberEatcnt": 0,
                    "Ch_OwnDeliverycnt": 0,
                    "Ch_NowNowcnt": 0,
                    "Ch_Amazoncnt": 0,
                    "Ch_CofeAppcnt": 0,
                    "Ch_Instashopcnt": 0,
                    "Ch_Tawseelcnt": 0,
                    "Ch_Kitopicnt": 0,
                    "Ch_ChatFoodcnt": 0,
                    "Ch_EMAREATcnt": 0,
                    "Ch_Foodatecnt": 0,
                    "Ch_CoffeePikcnt": 0,
                    "Ch_Drivucnt": "' . $Ch_Drivucnt . '",
                    "Ch_Littlemeescnt": 0,
                    "Ch_Swancnt": 0,
                    "Ch_JoiGiftscnt": 0
                }
                ]
            }
            ]
        }
        }',
            CURLOPT_HTTPHEADER => array(
                'x-apikey: ' . config('emaar.x_api_key'),
                'Content-Type: application/json',
                'Cookie: EmaarCookie=!tAB8Pf0MVogZ81obZ8iAVHPu2zwut7wDvb73T7QDsF0aUCzEuhjUSUZvMZiwD0VeouOB5V0+Kv66YS0=; TS01fca733=01eddcdb62efcffc8d0c00e6f84b95c8e52b17e81cea2ca77195390615b40d588afa878e1d5303b9f77dea3f21bae0300a2f41b0b1'
            ),
        );
        curl_setopt_array($curl, $arr);
        
        $response = curl_exec($curl);
        if (json_decode($response)->Code == "200") {
            if ($yesterday_orders->count() > 0) {

                Order::whereIn('id', $yesterday_orders->pluck('id'))->update([
                    'is_sent_to_emaar' => 1,
                    'emaar_sent_date' => Carbon::now()
                ]);
            }
        }
        $obj = new stdClass();

        $obj->UnitNo = $branch_unit_no;
        $obj->LeaseCode = $branch_lease_code;
        $obj->SalesDate = $yesterday;
        $obj->TransactionCount = $yesterday_orders->count();
        $obj->NetSales = round($yesterday_orders->sum('net_amount_without_tax'), 2);
        $obj->Ch_DineIn = $Ch_DineIn;
        $obj->Ch_Talabat = $Ch_Talabat;
        $obj->Ch_Drivu = $Ch_Drivu;
        $obj->Ch_DineIncnt = $Ch_DineIncnt;
        $obj->Ch_Talabatcnt = $Ch_Talabatcnt;
        $obj->Ch_Drivucnt =$Ch_Drivucnt;

        $response = curl_exec($curl);
        DB::table('logs')->insert([
            ['api_name' => 'Emaar Daily Orders','status_code' => json_decode($response)->Code, 'request' => $arr[10015], 'response' => $response, 'created_at' => Carbon::now()]
        ]);
        curl_close($curl);
        echo $response;
    }
}
