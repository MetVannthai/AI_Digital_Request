<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Request as StockRequest;
use Illuminate\Http\Request;
use Illuminate\View\View;

class RequestController extends Controller
{
    public function index(Request $request): View
    {
        $user = auth()->user();

        $query = StockRequest::query()
            ->with('item')
            ->where(function ($q) use ($user) {
                if ($user?->phone) {
                    $q->where('employee_phone', $user->phone);
                }

                if ($user?->name) {
                    $q->orWhere('employee_name', $user->name);
                }
            });

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        return view('staff.requests.index', [
            'requests' => $query->latest()->paginate(20)->withQueryString(),
        ]);
    }
}
