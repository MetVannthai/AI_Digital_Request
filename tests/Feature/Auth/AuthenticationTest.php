<?php

namespace Tests\Feature\Auth;

use App\Models\Request as StockRequest;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_login_screen_displays_six_digit_pin_boxes(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200)
            ->assertSee('data-pin-group', false)
            ->assertSee('pin-digit', false);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $user = User::factory()->create(['username' => 'test-user']);

        $response = $this->post('/login', [
            'username' => $user->username,
            'pin' => '123456',
        ]);

        $this->assertAuthenticated();
        $response->assertRedirect(route('dashboard', absolute: false));
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $user = User::factory()->create(['username' => 'test-user']);

        $this->post('/login', [
            'username' => $user->username,
            'pin' => '000000',
        ]);

        $this->assertGuest();
    }

    public function test_staff_users_are_redirected_to_the_staff_dashboard(): void
    {
        $user = User::factory()->create(['username' => 'staff-user']);
        $user->syncRoles('staff');

        $response = $this->actingAs($user)->get('/dashboard');

        $response->assertRedirect(route('staff.dashboard', absolute: false));
    }

    public function test_staff_dashboard_loads_without_user_request_column_errors(): void
    {
        $user = User::factory()->create(['username' => 'staff-user-2']);
        $user->syncRoles('staff');

        $response = $this->actingAs($user)->get('/staff/dashboard');

        $response->assertOk();
    }

    public function test_staff_users_only_see_accessible_navigation_items(): void
    {
        $user = User::factory()->create(['username' => 'staff-user-3']);
        $user->syncRoles('staff');

        $response = $this->actingAs($user)->get('/staff/dashboard');

        $response->assertOk()
            ->assertSee('Dashboard')
            ->assertSee('Request Stock')
            ->assertSee('My Requests')
            ->assertDontSee('Users')
            ->assertDontSee('Reports')
            ->assertDontSee('Stock Items');
    }

    public function test_admin_users_only_see_admin_navigation_items(): void
    {
        $user = User::factory()->create(['username' => 'admin-user']);
        $user->syncRoles('admin');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertOk()
            ->assertSee('Inventory')
            ->assertSee('Categories')
            ->assertSee('Add Stock')
            ->assertDontSee('Users')
            ->assertDontSee('Roles & Permissions')
            ->assertDontSee('System Settings');
    }

    public function test_staff_users_can_view_their_request_history(): void
    {
        $user = User::factory()->create([
            'username' => 'staff-requests',
            'name' => 'Alicia Staff',
            'phone' => '555-2001',
            'department' => 'Finance',
        ]);
        $user->syncRoles('staff');

        $item = \App\Models\Item::create([
            'item_code' => 'STAP-001',
            'name' => 'Stapler',
            'category_id' => \App\Models\Category::create(['name' => 'Office Supplies'])->id,
            'unit' => 'pcs',
            'current_balance' => 20,
            'min_stock' => 5,
        ]);

        StockRequest::create([
            'employee_name' => $user->name,
            'employee_phone' => $user->phone,
            'department' => $user->department,
            'item_id' => $item->id,
            'quantity' => 2,
            'purpose' => 'Office use',
            'status' => 'PENDING',
            'tracking_code' => 'STAFFREQ1',
        ]);

        $response = $this->actingAs($user)->get('/staff/requests');

        $response->assertOk()
            ->assertSee('Alicia Staff')
            ->assertSee('Stapler');
    }

    public function test_super_admin_users_see_full_management_navigation_items(): void
    {
        $user = User::factory()->create(['username' => 'super-admin-user']);
        $user->syncRoles('super_admin');

        $response = $this->actingAs($user)->get('/admin/dashboard');

        $response->assertOk()
            ->assertSee('User Management')
            ->assertSee('Departments')
            ->assertSee('Categories')
            ->assertSee('Stock Transactions')
            ->assertSee('Audit Logs');
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
