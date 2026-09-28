<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Spatie\Permission\Models\Role;
use Tests\TestCase;

class DepartmentAccessTest extends TestCase
{
    use RefreshDatabase;

    public function test_super_admin_can_access_departments_index(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('super_admin');

        $response = $this->actingAs($user)->get('/admin/departments');

        $response->assertOk();
    }

    public function test_staff_cannot_access_departments_index(): void
    {
        $user = User::factory()->create();
        $user->syncRoles('staff');

        $response = $this->actingAs($user)->get('/admin/departments');

        $response->assertStatus(403);
    }

    public function test_staff_with_inventory_permission_can_access_system_settings(): void
    {
        $staffRole = Role::findByName('staff', 'web');
        $user = User::factory()->create();
        $user->assignRole($staffRole);

        $this->actingAs($user)
            ->get(route('admin.items.index', ['menu' => 'system-settings']))
            ->assertOk();
    }

    public function test_staff_with_inventory_permission_cannot_access_admin_dashboard(): void
    {
        $staffRole = Role::findByName('staff', 'web');
        $user = User::factory()->create();
        $user->assignRole($staffRole);

        $this->actingAs($user)
            ->get(route('admin.dashboard'))
            ->assertForbidden();
    }
}
