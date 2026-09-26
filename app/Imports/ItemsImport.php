<?php

namespace App\Imports;

use App\Models\Category;
use App\Models\Item;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ItemsImport implements ToModel, WithHeadingRow
{
    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $category = Category::firstOrCreate(['name' => $row['category']]);
        return new Item([
            'item_code' => $row['item_code'], 'name' => $row['name'], 'category_id' => $category->id,
            'unit' => $row['unit'], 'current_balance' => $row['current_balance'] ?? 0, 'min_stock' => $row['min_stock'] ?? null,
        ]);
    }
}
