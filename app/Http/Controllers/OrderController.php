<?php

namespace App\Http\Controllers;

use App\Http\Requests\FetchOrdersRequest;
use App\Services\OrderService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class OrderController extends Controller
{
    private OrderService $orderService;

    public function __construct(OrderService $orderService)
    {
        $this->orderService = $orderService;
    }

    public function index(Request $request): View
    {
        $filters = [
            'branch_id' => $request->get('branch_id'),
            'business_date' => $request->get('business_date'),
            'payment_method' => $request->get('payment_method'),
            'send_to_emaar' => $request->get('send_to_emaar'),
        ];

        $orders = $this->orderService->getFilteredOrders($filters);
        $branches = $this->orderService->getBranches();
        $payment_methods = $this->orderService->getPaymentMethods();

        return view('orders', compact('orders', 'branches', 'payment_methods'));
    }

    public function jobsSuccess(): View
    {
        return view('success');
    }

    public function runQueue(): RedirectResponse
    {
        try {
            Artisan::call('queue:work');
            return redirect()->back()->with('success', 'Queue worker started');
        } catch (\Exception $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
    }

    public function getOrders(FetchOrdersRequest $request): RedirectResponse
    {
        $this->orderService->fetchOrdersFromFoodics($request->validated()['branch_id']);
        return redirect('/logs');
    }
}
