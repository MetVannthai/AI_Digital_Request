<?php

use Illuminate\Database\Migrations\Migration;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    private array $permissions = ['manage-items', 'manage-stock', 'export-data'];

    public function up(): void
    {
        $role = Role::where('name', 'staff')->where('guard_name', 'web')->first();

        if (! $role) {
            return;
        }

        foreach ($this->permissions as $name) {
            $permission = Permission::where('name', $name)->where('guard_name', 'web')->first();

            if ($permission && ! $role->hasPermissionTo($permission)) {
                $role->givePermissionTo($permission);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }

    public function down(): void
    {
        $role = Role::where('name', 'staff')->where('guard_name', 'web')->first();

        if (! $role) {
            return;
        }

        foreach ($this->permissions as $name) {
            $permission = Permission::where('name', $name)->where('guard_name', 'web')->first();

            if ($permission && $role->hasPermissionTo($permission)) {
                $role->revokePermissionTo($permission);
            }
        }

        app(PermissionRegistrar::class)->forgetCachedPermissions();
    }
};
