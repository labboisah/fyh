<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminUserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin = User::firstOrCreate(
            ['email' => 'admin@hospital.test'],
            [
                'name' => 'Administrator',
                'password' => Hash::make('admin@123'),
            ]
        );

        // Assign administrator role
        $adminRole = Role::where('name', 'administrator')->first();
        if ($adminRole && !$admin->hasRole('administrator')) {
            $admin->assignRole($adminRole);
        }
    }
}
