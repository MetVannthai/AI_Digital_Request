<?php

namespace App\Exports;

use App\Models\Item;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class ItemsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return Item::with('category')->get()->map(fn (Item $item) => [
            $item->item_code, $item->name, $item->category->name, $item->unit, $item->current_balance, $item->min_stock,
        ]);
    }

    public function headings(): array
    {
        return ['item_code', 'name', 'category', 'unit', 'current_balance', 'min_stock'];
    }
}
