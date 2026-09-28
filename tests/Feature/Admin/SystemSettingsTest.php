<?php

namespace Tests\Feature\Admin;

use App\Models\Category;
use App\Models\Item;
use App\Models\Request as StockRequest;
use App\Models\SystemSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class SystemSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_staff_can_update_stock_settings(): void
    {
        $user = User::factory()->create();
        $user->assignRole(Role::findByName('staff', 'web'));

        $this->actingAs($user)
            ->put(route('admin.settings.update'), [
                'default_min_stock' => 8,
                'require_request_approval' => '0',
            ])
            ->assertRedirect(route('admin.settings.edit'));

        $this->assertDatabaseHas('system_settings', [
            'id' => 1,
            'default_min_stock' => 8,
            'require_request_approval' => false,
        ]);
    }

    public function test_new_items_use_the_configured_low_stock_threshold(): void
    {
        SystemSetting::current()->update(['default_min_stock' => 9]);
        $category = Category::create(['name' => 'Stationery']);
        $user = User::factory()->create();
        $user->assignRole(Role::findByName('staff', 'web'));

        $this->actingAs($user)->post(route('admin.items.store'), [
            'item_code' => 'PAPER-001',
            'name' => 'Copy paper',
            'category_id' => $category->id,
            'unit' => 'ream',
            'current_balance' => 4,
        ])->assertRedirect(route('admin.items.index'));

        $this->assertDatabaseHas('items', ['item_code' => 'PAPER-001', 'min_stock' => 9]);
    }

    public function test_dashboard_uses_the_default_threshold_for_items_without_one(): void
    {
        SystemSetting::current()->update(['default_min_stock' => 5]);
        $category = Category::create(['name' => 'Stationery']);
        $item = Item::create([
            'item_code' => 'PAPER-002',
            'name' => 'Copy paper',
            'category_id' => $category->id,
            'unit' => 'ream',
            'current_balance' => 4,
            'min_stock' => null,
        ]);
        $user = User::factory()->create();
        $user->assignRole(Role::findByName('admin', 'web'));

        $this->actingAs($user)->get(route('admin.dashboard'))
            ->assertOk()
            ->assertViewHas('lowStockItems', fn ($items) => $items->contains('id', $item->id));
    }

    public function test_disabling_request_approval_auto_approves_new_requests(): void
    {
        SystemSetting::current()->update(['require_request_approval' => false]);
        $category = Category::create(['name' => 'Stationery']);
        $item = Item::create([
            'item_code' => 'PAPER-003',
            'name' => 'Copy paper',
            'category_id' => $category->id,
            'unit' => 'ream',
            'current_balance' => 10,
        ]);

        $this->post(route('request.store'), [
            'employee_name' => 'Alex',
            'employee_phone' => '555-0100',
            'department' => 'Finance',
            'item_id' => $item->id,
            'quantity' => 1,
            'purpose' => 'Office use',
        ])->assertOk()->assertViewHas('request', fn (StockRequest $request) => $request->status === 'APPROVED');
    }
}
