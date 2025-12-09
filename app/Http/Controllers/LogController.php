<?php

namespace App\Http\Controllers;

use App\Jobs\ListOrders;
use App\Jobs\SendDailyOrdersToEmaar;
use App\Models\Branch;
use App\Models\Order;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;

class LogController extends Controller
{
    public function index()
    {
        $logs = DB::table('logs')->orderBy('id','desc')->paginate(20);
        $branches = Branch::get();
        return view('logs', compact('logs','branches'));
    }
    
    public function runQueue(){
        try{

            Artisan::call('queue:work');
        }
        catch(\Exception $throw){
                dd($throw->getMessage());
        }
    }

    public function show(Request $request){
        
        $log = DB::table('logs')->find($request->id);
       
        return view('logs.show',compact('log'));
    }

    public function dailyOrders(Request $request){

        try {
            Artisan::call('daily:orders');

            // $request->validate([
            //     'branch_id' => 'required'
            // ]);
            // dispatch(new SendDailyOrdersToEmaar($request->branch_id));
            return redirect('/logs');
        } catch (\Exception $throw) {
            dd($throw->getMessage());
        }
    }

    public function monthlyOrders()
    {

        try {
            Artisan::call('monthly:orders');
            return redirect('/logs');
        } catch (\Exception $throw) {
            dd($throw->getMessage());
        }
    } 

   
}
