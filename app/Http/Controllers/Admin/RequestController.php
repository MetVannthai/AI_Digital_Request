<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\RejectRequest;
use App\Models\Request as StockRequest;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function index(Request $request): View
    {
        $requests = StockRequest::with('item')->when($request->status, fn ($q, $v) => $q->where('status', $v))
            ->when($request->department, fn ($q, $v) => $q->where('department', 'like', "%{$v}%"))
            ->when($request->date, fn ($q, $v) => $q->whereDate('created_at', $v))->latest()->paginate(20)->withQueryString();
        return view('admin.requests.index', compact('requests'));
    }

    public function show(StockRequest $request): View { return view('admin.requests.show', ['request' => $request->load('item')]); }

    public function approve(StockRequest $request): RedirectResponse
    {
        if ($request->status !== 'PENDING') return back()->with('error', 'Only pending requests can be approved.');
        $request->update(['status' => 'APPROVED', 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
        return back()->with('success', 'Request approved.');
    }

    public function reject(RejectRequest $form, StockRequest $request): RedirectResponse
    {
        if ($request->status !== 'PENDING') return back()->with('error', 'Only pending requests can be rejected.');
        $request->update(['status' => 'REJECTED', 'rejected_reason' => $form->validated('rejected_reason'), 'reviewed_by' => Auth::id(), 'reviewed_at' => now()]);
        return back()->with('success', 'Request rejected.');
    }

    public function issue(StockRequest $request, StockService $stockService): RedirectResponse
    {
        $stockService->issue($request, Auth::id());
        return back()->with('success', 'Stock issued and request completed.');
    }
}
