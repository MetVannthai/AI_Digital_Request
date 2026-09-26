<?php

namespace App\Http\Controllers;

use App\Http\Requests\CheckRequestStatusRequest;
use App\Http\Requests\StoreRequestRequest;
use App\Models\Item;
use App\Models\Request as StockRequest;
use Illuminate\Support\Str;
use Illuminate\View\View;

class PublicRequestController extends Controller
{
    public function create(): View
    {
        return view('request.create', ['items' => Item::query()->orderBy('name')->get()]);
    }

    public function store(StoreRequestRequest $request): View
    {
        do { $trackingCode = Str::upper(Str::random(8)); }
        while (StockRequest::where('tracking_code', $trackingCode)->exists());

        $stockRequest = StockRequest::create([
            ...$request->validated(),
            'tracking_code' => $trackingCode,
            'status' => 'PENDING',
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
