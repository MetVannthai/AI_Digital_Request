<?php

namespace App\Services;

use App\Models\Item;
use App\Models\Request as StockRequest;
use App\Models\StockTransaction;
use Illuminate\Database\DatabaseManager;
use Illuminate\Validation\ValidationException;

class StockService
{
    public function __construct(private readonly DatabaseManager $database) {}

    public function add(Item $item, int $quantity, ?string $note, ?int $createdBy): StockTransaction
    {
        return $this->database->transaction(function () use ($item, $quantity, $note, $createdBy) {
            $lockedItem = Item::query()->lockForUpdate()->findOrFail($item->id);
            $lockedItem->increment('current_balance', $quantity);
            $lockedItem->refresh();

            return StockTransaction::create([
                'item_id' => $lockedItem->id,
                'type' => 'IN',
                'quantity' => $quantity,
                'balance_after' => $lockedItem->current_balance,
                'created_by' => $createdBy,
                'note' => $note,
            ]);
        });
    }

    public function issue(StockRequest $request, ?int $createdBy): StockTransaction
    {
        return $this->database->transaction(function () use ($request, $createdBy) {
            $lockedRequest = StockRequest::query()->lockForUpdate()->findOrFail($request->id);
            $item = Item::query()->lockForUpdate()->findOrFail($lockedRequest->item_id);

            if ($lockedRequest->status !== 'APPROVED') {
                throw ValidationException::withMessages(['status' => 'Only approved requests can be issued.']);
            }

            if ($item->current_balance < $lockedRequest->quantity) {
                throw ValidationException::withMessages(['quantity' => 'Insufficient stock for this request.']);
            }

            $item->decrement('current_balance', $lockedRequest->quantity);
            $item->refresh();
            $transaction = StockTransaction::create([
                'item_id' => $item->id,
                'type' => 'OUT',
                'quantity' => $lockedRequest->quantity,
                'balance_after' => $item->current_balance,
                'reference_type' => 'request',
                'reference_id' => $lockedRequest->id,
                'created_by' => $createdBy,
            ]);

            $lockedRequest->update(['status' => 'ISSUED']);

            return $transaction;
        });
    }
}
