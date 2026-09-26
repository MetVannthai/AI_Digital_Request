<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Item;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([RolePermissionSeeder::class, SuperAdminSeeder::class]);

        $stationery = Category::create(['name' => 'Stationery']);
        $pantry = Category::create(['name' => 'Pantry']);

        Item::create(['item_code' => 'PEN-BLU', 'name' => 'Blue ballpoint pen', 'category_id' => $stationery->id, 'unit' => 'pcs', 'current_balance' => 120, 'min_stock' => 20]);
        Item::create(['item_code' => 'PPR-A4', 'name' => 'A4 copy paper', 'category_id' => $stationery->id, 'unit' => 'box', 'current_balance' => 15, 'min_stock' => 5]);
        Item::create(['item_code' => 'COF-001', 'name' => 'Ground coffee', 'category_id' => $pantry->id, 'unit' => 'kg', 'current_balance' => 4, 'min_stock' => 2]);
    }
}
