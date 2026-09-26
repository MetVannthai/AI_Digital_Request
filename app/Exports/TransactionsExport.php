<?php

namespace App\Exports;

use App\Models\StockTransaction;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class TransactionsExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return StockTransaction::with('item')->get()->map(fn (StockTransaction $transaction) => [
            $transaction->created_at->toDateTimeString(), $transaction->item->item_code, $transaction->type,
            $transaction->quantity, $transaction->balance_after, $transaction->reference_type, $transaction->reference_id, $transaction->note,
        ]);
    }

    public function headings(): array
    {
        return ['created_at', 'item_code', 'type', 'quantity', 'balance_after', 'reference_type', 'reference_id', 'note'];
    }
}
