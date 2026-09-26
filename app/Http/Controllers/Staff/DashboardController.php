<?php

namespace App\Http\Controllers\Staff;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Request as StockRequest;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('staff.dashboard', [
            'pendingCount' => StockRequest::where('status', 'PENDING')->count(),
            'itemCount' => Item::count(),
            'recentRequests' => StockRequest::with('item')->latest()->limit(5)->get(),
        ]);
    }
}
