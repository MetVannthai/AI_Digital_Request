<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        $permissions = [
            'manage-users',
            'manage-items',
            'manage-stock',
            'approve-requests',
            'issue-stock',
            'view-reports',
            'export-data',
            'view-own-requests',
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate([
                'name' => $permission,
                'guard_name' => 'web',
            ]);
        }

        Role::firstOrCreate(['name' => 'super_admin', 'guard_name' => 'web'])
            ->syncPermissions($permissions);

        Role::firstOrCreate(['name' => 'admin', 'guard_name' => 'web'])
            ->syncPermissions([
                'manage-items',
                'manage-stock',
                'approve-requests',
                'issue-stock',
                'view-reports',
                'export-data',
            ]);

        Role::firstOrCreate(['name' => 'staff', 'guard_name' => 'web'])
            ->syncPermissions(['view-own-requests']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        foreach (['super_admin', 'admin', 'staff'] as $roleName) {
            $role = Role::where('name', $roleName)->where('guard_name', 'web')->first();
            if ($role) {
                $role->delete();
            }
        }

        foreach (['manage-users', 'manage-items', 'manage-stock', 'approve-requests', 'issue-stock', 'view-reports', 'export-data', 'view-own-requests'] as $permissionName) {
            $permission = Permission::where('name', $permissionName)->where('guard_name', 'web')->first();
            if ($permission) {
                $permission->delete();
            }
        }
    }
};
