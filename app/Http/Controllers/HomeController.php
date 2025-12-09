<?php

namespace App\Http\Controllers;
use App\Models\Branch;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $branches = Branch::orderBy('id','desc')->paginate(10);
        return view('home', compact('branches'));
    }
    public function paymentMethods()
    {
         $payment_methods = PaymentMethod::orderBy('id', 'desc')->paginate(10);
        return view('payment-methods', compact('payment_methods'));
    }
}
