<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RolePermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'manage-users',
            'manage-departments',
            'manage-items',
            'manage-stock',
            'approve-requests',
            'issue-stock',
            'view-reports',
            'export-data',
            'view-own-requests',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission, 'guard_name' => 'web']);
        }

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web'])->syncPermissions($permissions);
        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web'])->syncPermissions([
            'manage-items', 'manage-stock', 'approve-requests', 'issue-stock', 'view-reports', 'export-data',
        ]);
        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web'])->syncPermissions(['view-own-requests']);
    }
}
