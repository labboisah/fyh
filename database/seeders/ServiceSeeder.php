<?php

namespace Database\Seeders;

use App\Models\Service;
use Illuminate\Database\Seeder;

class ServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $services = [
            // Consultation Services
            ['code' => 'SVC001', 'name' => 'General Consultation', 'description' => 'General medical consultation', 'price' => 50.00, 'category' => 'Consultation'],
            ['code' => 'SVC002', 'name' => 'Specialized Consultation', 'description' => 'Consultation with specialist doctor', 'price' => 100.00, 'category' => 'Consultation'],
            ['code' => 'SVC003', 'name' => 'Follow-up Consultation', 'description' => 'Follow-up consultation visit', 'price' => 35.00, 'category' => 'Consultation'],
            
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['code' => $service['code']], $service);
        }
    }
}
