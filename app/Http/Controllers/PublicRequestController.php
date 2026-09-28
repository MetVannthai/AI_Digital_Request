<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckRequestStatusRequest;
use App\Http\Requests\StoreRequestRequest;
use App\Models\Item;
use App\Models\Request as StockRequest;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request as HttpRequest;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicRequestController extends Controller
{
    public function create(): View
    {
        return view('request.create', ['items' => Item::query()->orderBy('name')->get()]);
    }

    public function checkAccount(HttpRequest $request): JsonResponse
    {
        $validated = $request->validate([
            'phone' => ['required', 'string', 'max:50'],
        ]);

        $hasAccount = User::query()
            ->where('phone', $validated['phone'])
            ->where('status', 'active')
            ->exists();

        return response()->json(['has_account' => $hasAccount]);
    }

    public function store(StoreRequestRequest $request): View
    {
        do { $trackingCode = Str::upper(Str::random(8)); }
        while (StockRequest::where('tracking_code', $trackingCode)->exists());

        $stockRequest = StockRequest::create([
            ...$request->validated(),
            'tracking_code' => $trackingCode,
            'status' => SystemSetting::current()->require_request_approval ? 'PENDING' : 'APPROVED',
        ]);

        return view('request.confirmation', ['request' => $stockRequest->load('item')]);
    }

    public function status(): View { return view('request.status'); }

    public function showStatus(CheckRequestStatusRequest $request): View
    {
        $validated = $request->validated();
        $requests = StockRequest::with('item')
            ->when($validated['tracking_code'] ?? null, fn ($query, $value) => $query->where('tracking_code', $value))
            ->when($validated['employee_phone'] ?? null, fn ($query, $value) => $query->where('employee_phone', $value))
            ->latest()->get();

        return view('request.status', compact('requests'));
    }
}
