<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Item;
use App\Models\Request as StockRequest;
use App\Models\StockTransaction;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        return view('admin.dashboard', [
            'pendingCount' => StockRequest::where('status', 'PENDING')->count(),
            'lowStockItems' => Item::whereNotNull('min_stock')->whereColumn('current_balance', '<=', 'min_stock')->orderBy('current_balance')->get(),
            'itemCount' => Item::count(),
            'totalBalance' => Item::sum('current_balance'),
            'recentTransactions' => StockTransaction::with(['item', 'creator'])->latest()->limit(10)->get(),
        ]);
    }
}
