<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::updateOrCreate(
            ['username' => 'vannthai'],
            [
                'name' => 'Met Vannthai',
                'pin' => Hash::make('507601'),
                'role' => 'super_admin',
                'status' => 'active',
                'must_change_pin' => true,
            ],
        );

        $user->syncRoles('super_admin');
    }
}
