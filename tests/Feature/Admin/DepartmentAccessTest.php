<?php

namespace Tests\Feature\Admin;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
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
}
