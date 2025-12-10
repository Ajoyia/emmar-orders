<?php

namespace App\Http\Controllers;

use App\Models\Branch;
use App\Services\BranchService;
use App\Services\LogService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\View\View;

class LogController extends Controller
{
    private LogService $logService;
    private BranchService $branchService;

    public function __construct(LogService $logService, BranchService $branchService)
    {
        $this->logService = $logService;
        $this->branchService = $branchService;
    }

    public function index(): View
    {
        $logs = $this->logService->getPaginated(20);
        $branches = $this->branchService->getAll();
        return view('logs', compact('logs', 'branches'));
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

    public function show(Request $request): View
    {
        $log = $this->logService->findById($request->id);
        return view('logs.show', compact('log'));
    }

    public function dailyOrders(): RedirectResponse
    {
        try {
            Artisan::call('daily:orders');
            return redirect('/logs')->with('success', 'Daily orders command executed');
        } catch (\Exception $e) {
            return redirect('/logs')->with('error', $e->getMessage());
        }
    }

    public function monthlyOrders(): RedirectResponse
    {
        try {
            Artisan::call('monthly:orders');
            return redirect('/logs')->with('success', 'Monthly orders command executed');
        } catch (\Exception $e) {
            return redirect('/logs')->with('error', $e->getMessage());
        }
    }
}
