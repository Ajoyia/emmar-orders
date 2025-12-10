<?php

namespace App\Http\Controllers;

use App\Services\BranchService;
use App\Services\PaymentMethodService;
use Illuminate\View\View;

class HomeController extends Controller
{
    private BranchService $branchService;
    private PaymentMethodService $paymentMethodService;

    public function __construct(BranchService $branchService, PaymentMethodService $paymentMethodService)
    {
        $this->middleware('auth');
        $this->branchService = $branchService;
        $this->paymentMethodService = $paymentMethodService;
    }

    public function index(): View
    {
        $branches = $this->branchService->getPaginated(10);
        return view('home', compact('branches'));
    }

    public function paymentMethods(): View
    {
        $payment_methods = $this->paymentMethodService->getPaginated(10);
        return view('payment-methods', compact('payment_methods'));
    }
}
