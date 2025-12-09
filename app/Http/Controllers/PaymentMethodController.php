<?php

namespace App\Http\Controllers;

use App\Jobs\ListOrders;
use App\Models\Order;
use App\Models\PaymentMethod;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymentMethodController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $payment = PaymentMethod::orderBy('id', 'desc')->paginate(5);
        return view('home', compact('branches'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('payment_methods.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'payment_id' => 'required',
            'name' => 'required',
            'emmar_mapping' => 'required'
        ]);

        PaymentMethod::create([
            'payment_id' => $request->payment_id,
            'name' => $request->name,
            'payment_id' => $request->payment_id,
            'emmar_mapping' => $request->emmar_mapping,
            'share_with_emaar' =>  (int) $request->share_with_emaar,
        ]);
        return redirect()->route('payment.methods')->with('success', 'Payment Method has been created successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Branch  $Branch
     * @return \Illuminate\Http\Response
     */
    public function show(PaymentMethod $branch)
    {
        return view('payment_methods.show', compact('branch'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Branch  $branch
     * @return \Illuminate\Http\Response
     */
    public function edit(PaymentMethod $payment)
    {
        return view('payment_methods.edit', compact('payment'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\PaymentMethod  $Branch
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, PaymentMethod $branch)
    {
        $request->validate([
            'name' => 'required',
            'emmar_mapping' => 'required'
        ]);
        $model = PaymentMethod::find($request->id);

        $model->emmar_mapping = $request->emmar_mapping;
        $model->name = $request->name;
        $model->share_with_emaar = (int) $request->share_with_emaar;
        $model->save(); // 

        return redirect()->route('payment.methods')->with('success', 'Branch Has Been updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Branch  $Branch
     * @return \Illuminate\Http\Response
     */
    public function destroy(PaymentMethod $payment)
    {
        $payment->delete();
        return redirect()->route('payment.methods')->with('success', 'Payment has been deleted successfully');
    }
}
