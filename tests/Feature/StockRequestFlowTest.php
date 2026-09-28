<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Item;
use App\Models\Request as StockRequest;
use App\Models\User;
use App\Services\StockService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class StockRequestFlowTest extends TestCase
{
    use RefreshDatabase;

    public function test_request_form_system_login_button_check_matches_active_account_phone(): void
    {
        User::factory()->create(['phone' => '555-0100', 'status' => 'active']);

        $this->get(route('request.account-check', ['phone' => '555-0100']))
            ->assertOk()
            ->assertExactJson(['has_account' => true]);
    }

    public function test_request_form_system_login_button_check_hides_for_missing_or_inactive_accounts(): void
    {
        User::factory()->create(['phone' => '555-0101', 'status' => 'inactive']);

        $this->get(route('request.account-check', ['phone' => '555-0100']))
            ->assertOk()
            ->assertExactJson(['has_account' => false]);

        $this->get(route('request.account-check', ['phone' => '555-0101']))
            ->assertOk()
            ->assertExactJson(['has_account' => false]);
    }

    public function test_issuing_an_approved_request_decrements_stock_and_records_transaction(): void
    {
        $admin = User::factory()->create(['role' => 'admin']);
        $item = Item::create([
            'item_code' => 'PEN-001', 'name' => 'Pen', 'category_id' => Category::create(['name' => 'Stationery'])->id,
            'unit' => 'pcs', 'current_balance' => 10,
        ]);
        $request = StockRequest::create([
            'employee_name' => 'Alex', 'employee_phone' => '555-0100', 'department' => 'Finance', 'item_id' => $item->id,
            'quantity' => 3, 'purpose' => 'Daily work', 'status' => 'APPROVED', 'tracking_code' => 'ABC12345',
        ]);

        app(StockService::class)->issue($request, $admin->id);

        $this->assertDatabaseHas('items', ['id' => $item->id, 'current_balance' => 7]);
        $this->assertDatabaseHas('stock_transactions', [
            'item_id' => $item->id, 'type' => 'OUT', 'quantity' => 3, 'balance_after' => 7,
            'reference_type' => 'request', 'reference_id' => $request->id, 'created_by' => $admin->id,
        ]);
        $this->assertDatabaseHas('requests', ['id' => $request->id, 'status' => 'ISSUED']);
    }
}
