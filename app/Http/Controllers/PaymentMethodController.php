<?php

namespace App\Http\Controllers;

use App\Http\Requests\StorePaymentMethodRequest;
use App\Http\Requests\UpdatePaymentMethodRequest;
use App\Models\PaymentMethod;
use App\Services\PaymentMethodService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class PaymentMethodController extends Controller
{
    private PaymentMethodService $paymentMethodService;

    public function __construct(PaymentMethodService $paymentMethodService)
    {
        $this->paymentMethodService = $paymentMethodService;
    }

    public function index(): View
    {
        $payment_methods = $this->paymentMethodService->getPaginated(5);
        return view('payment-methods', compact('payment_methods'));
    }

    public function create(): View
    {
        return view('payment_methods.create');
    }

    public function store(StorePaymentMethodRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['share_with_emaar'] = (int) ($request->share_with_emaar ?? 0);
        
        $this->paymentMethodService->create($data);
        return redirect()->route('payment.methods')->with('success', 'Payment Method has been created successfully.');
    }

    public function show(PaymentMethod $payment): View
    {
        return view('payment_methods.show', compact('payment'));
    }

    public function edit(PaymentMethod $payment): View
    {
        return view('payment_methods.edit', compact('payment'));
    }

    public function update(UpdatePaymentMethodRequest $request, PaymentMethod $payment): RedirectResponse
    {
        $data = $request->validated();
        $data['share_with_emaar'] = (int) ($request->share_with_emaar ?? 0);
        
        $this->paymentMethodService->update($payment, $data);
        return redirect()->route('payment.methods')->with('success', 'Payment Method Has Been updated successfully');
    }

    public function destroy(PaymentMethod $payment): RedirectResponse
    {
        $this->paymentMethodService->delete($payment);
        return redirect()->route('payment.methods')->with('success', 'Payment has been deleted successfully');
    }
}
