<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Models\Branch;
use App\Services\BranchService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class BranchController extends Controller
{
    private BranchService $branchService;

    public function __construct(BranchService $branchService)
    {
        $this->branchService = $branchService;
    }

    public function index(): View
    {
        $branches = $this->branchService->getPaginated(5);
        return view('home', compact('branches'));
    }

    public function create(): View
    {
        return view('branches.create');
    }

    public function store(StoreBranchRequest $request): RedirectResponse
    {
        $this->branchService->create($request->validated());
        return redirect()->route('home')->with('success', 'Branch has been created successfully.');
    }

    public function show(Branch $branch): View
    {
        return view('branches.show', compact('branch'));
    }

    public function edit(Branch $branch): View
    {
        return view('branches.edit', compact('branch'));
    }

    public function update(UpdateBranchRequest $request, Branch $branch): RedirectResponse
    {
        $this->branchService->update($branch, $request->validated());
        return redirect()->route('home')->with('success', 'Branch Has Been updated successfully');
    }

    public function destroy(Branch $branch): RedirectResponse
    {
        $this->branchService->delete($branch);
        return redirect()->route('home')->with('success', 'Branch has been deleted successfully');
    }
}
