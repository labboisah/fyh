<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Create roles
        $this->call(RoleSeeder::class);

        // Create permissions and assign to roles
        $this->call(PermissionSeeder::class);

        // Create admin user
        $this->call(AdminUserSeeder::class);

        // Create accountant role and permissions
        $this->call(AccountantSeeder::class);

        // Create services
        $this->call(ServiceSeeder::class);
        
        $this->call(InvestigationSeeder::class);
        
        $this->call(RouteSeeder::class);
        
        $this->call(ConsumableSeeder::class);
    }
}
