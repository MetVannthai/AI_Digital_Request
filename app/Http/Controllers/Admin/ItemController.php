<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\AddStockRequest;
use App\Http\Requests\ImportItemsRequest;
use App\Http\Requests\StoreItemRequest;
use App\Http\Requests\UpdateItemRequest;
use App\Imports\ItemsImport;
use App\Models\Category;
use App\Models\Item;
use App\Services\StockService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;
use Maatwebsite\Excel\Facades\Excel;

class ItemController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View { return view('admin.items.index', ['items' => Item::with('category')->orderBy('name')->get()]); }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View { return view('admin.items.create', ['categories' => Category::orderBy('name')->get()]); }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreItemRequest $request): RedirectResponse
    { Item::create($request->validated()); return to_route('admin.items.index')->with('success', 'Item created.'); }

    /**
     * Display the specified resource.
     */
    public function show(Item $item): View { return view('admin.items.show', compact('item')); }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Item $item): View { return view('admin.items.edit', ['item' => $item, 'categories' => Category::orderBy('name')->get()]); }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateItemRequest $request, Item $item): RedirectResponse
    { $item->update($request->validated()); return to_route('admin.items.index')->with('success', 'Item updated.'); }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Item $item): RedirectResponse
    {
        if ($item->requests()->exists() || $item->stockTransactions()->exists()) return back()->with('error', 'Cannot delete an item with history.');
        $item->delete(); return to_route('admin.items.index')->with('success', 'Item deleted.');
    }

    public function addStock(AddStockRequest $request, Item $item, StockService $stockService): RedirectResponse
    { $stockService->add($item, $request->integer('quantity'), $request->input('note'), Auth::id()); return back()->with('success', 'Stock added.'); }

    public function import(ImportItemsRequest $request): RedirectResponse
    { Excel::import(new ItemsImport, $request->file('file')); return back()->with('success', 'Items imported.'); }
}
