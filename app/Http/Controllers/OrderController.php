<?php

namespace App\Http\Controllers;

use App\Jobs\ListOrders;
use App\Models\Branch;
use App\Models\Order;
use App\Models\PaymentMethod;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $branches = Branch::get();
        $payment_methods = PaymentMethod::get();
        $orders = DB::table('orders')
            ->leftJoin('payment_methods', 'orders.payment_method_id', '=', 'payment_methods.payment_id')
            ->leftJoin('branches', 'orders.branch_id', '=', 'branches.branch_id');

        if ($request->has('branch_id') && $request->filled('branch_id')) {
            $orders = $orders->where('orders.branch_id', $request->branch_id);
        }
        if ($request->has('business_date') && $request->filled('business_date')) {
            $orders = $orders->where('orders.business_date', $request->business_date);
        }
        if ($request->has('payment_method') && $request->filled('payment_method')) {
            $orders = $orders->where('orders.payment_method_id', $request->payment_method);
        }
        if ($request->has('send_to_emaar') && $request->filled('send_to_emaar')) {
            $orders = $orders->where('orders.is_sent_to_emaar', (int) $request->send_to_emaar);
        }
        $orders = $orders
            ->orderBy('orders.id', 'desc')
            ->select('orders.price as price', 'payment_methods.name as payment_method', 'branches.name as branch_name', 'orders.order_id as order_id', 'orders.is_sent_to_emaar as is_sent_to_emaar', 'orders.net_amount_without_tax as net_amount_without_tax', 'orders.business_date as business_date')
            ->paginate(10);
        return view('orders', compact('orders', 'branches', 'payment_methods'));
    }
    public function jobsSuccess()
    {
        return view('success');
    }
    public function runQueue()
    {
        try {

            Artisan::call('queue:work');
        } catch (\Exception $throw) {
            dd($throw->getMessage());
        }
    }

    public function getOrders(Request $request)
    {
        $request->validate([
            'branch_id' => 'required'
        ]);
        DB::table('jobs')->truncate();
        $curl = curl_init();

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.foodics.com/v5/orders?include=branch,payments.payment_method&filter%5Bbranch_id%5D=' . $request->branch_id . '&filter%5Bstatus%5D=4&filter%5Bbusiness_date%5D=' . Carbon::yesterday()->toDateString(),
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
                    dispatch(new ListOrders($i, $request->branch_id))->delay(now()->addSeconds(5));
                }
            }
        }
        return redirect('/logs');
    }
}
