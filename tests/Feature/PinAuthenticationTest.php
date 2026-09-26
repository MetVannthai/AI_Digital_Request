<?php

namespace Tests\Feature;

use App\Models\User;
use Database\Seeders\RolePermissionSeeder;
use Database\Seeders\SuperAdminSeeder;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Hash;
use Tests\TestCase;

class PinAuthenticationTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        $this->seed([RolePermissionSeeder::class, SuperAdminSeeder::class]);
    }

    public function test_super_admin_is_forced_to_change_the_seed_pin(): void
    {
        $response = $this->post('/login', ['username' => 'vannthai', 'pin' => '507601']);

        $response->assertRedirect(route('pin.change'));
        $this->assertAuthenticatedAs(User::where('username', 'vannthai')->first());
        $this->assertNotNull(User::where('username', 'vannthai')->first()->last_login_at);
        $this->get(route('admin.dashboard'))->assertRedirect(route('pin.change'));
    }

    public function test_changing_the_pin_unlocks_the_dashboard(): void
    {
        $this->post('/login', ['username' => 'vannthai', 'pin' => '507601']);

        $this->put(route('pin.update'), [
            'new_pin' => '123456',
            'new_pin_confirmation' => '123456',
        ])->assertRedirect(route('dashboard'));

        $user = User::where('username', 'vannthai')->firstOrFail();
        $this->assertFalse($user->must_change_pin);
        $this->assertTrue(Hash::check('123456', $user->pin));
        $this->get(route('admin.dashboard'))->assertOk();
    }

    public function test_inactive_users_cannot_log_in(): void
    {
        $user = User::factory()->create(['username' => 'inactive-user', 'pin' => Hash::make('123456'), 'status' => 'inactive']);
        $user->assignRole('staff');

        $this->post('/login', ['username' => 'inactive-user', 'pin' => '123456'])
            ->assertSessionHasErrors('username');
        $this->assertGuest();
    }

    public function test_super_admin_can_create_a_staff_user_with_one_time_credentials(): void
    {
        $admin = User::where('username', 'vannthai')->firstOrFail();
        $admin->update(['must_change_pin' => false]);

        $this->actingAs($admin)->post(route('admin.users.store'), [
            'name' => 'Office Staff',
            'username' => 'office.staff',
            'department' => 'Finance',
            'phone' => '555-0101',
            'role' => 'staff',
        ])->assertRedirect(route('admin.users.index'))->assertSessionHas('created_user_credentials');

        $user = User::where('username', 'office.staff')->firstOrFail();
        $this->assertTrue(Hash::check(session('created_user_credentials.pin'), $user->pin));
        $this->assertTrue($user->must_change_pin);
        $this->assertTrue($user->hasRole('staff'));
    }
}
