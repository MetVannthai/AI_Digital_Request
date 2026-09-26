<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\StockTransaction;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TransactionController extends Controller
{
    public function index(Request $request): View
    {
        $transactions = StockTransaction::with(['item', 'creator'])
            ->when($request->type, fn ($q, $v) => $q->where('type', $v))
            ->when($request->item_id, fn ($q, $v) => $q->where('item_id', $v))
            ->latest()->paginate(30)->withQueryString();
        return view('admin.transactions.index', compact('transactions'));
    }
}
