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
            
            // Laboratory Services
            ['code' => 'LAB001', 'name' => 'Blood Test (Full Profile)', 'description' => 'Complete blood count and chemistry panel', 'price' => 80.00, 'category' => 'Laboratory'],
            ['code' => 'LAB002', 'name' => 'Malaria Test', 'description' => 'Rapid malaria antigen test', 'price' => 30.00, 'category' => 'Laboratory'],
            ['code' => 'LAB003', 'name' => 'Typhoid Test', 'description' => 'Typhoid fever serological test', 'price' => 35.00, 'category' => 'Laboratory'],
            ['code' => 'LAB004', 'name' => 'HIV Test', 'description' => 'HIV antibody screening test', 'price' => 50.00, 'category' => 'Laboratory'],
            ['code' => 'LAB005', 'name' => 'Pregnancy Test', 'description' => 'Human beta hCG blood test', 'price' => 40.00, 'category' => 'Laboratory'],
            ['code' => 'LAB006', 'name' => 'Urinalysis', 'description' => 'Complete urinalysis', 'price' => 25.00, 'category' => 'Laboratory'],
            
            // Imaging Services
            ['code' => 'IMG001', 'name' => 'X-Ray (Single View)', 'description' => 'Basic X-ray imaging', 'price' => 90.00, 'category' => 'Imaging'],
            ['code' => 'IMG002', 'name' => 'Chest X-Ray', 'description' => 'Chest imaging', 'price' => 75.00, 'category' => 'Imaging'],
            ['code' => 'IMG003', 'name' => 'Abdominal Ultrasound', 'description' => 'Abdominal ultrasound imaging', 'price' => 120.00, 'category' => 'Imaging'],
            ['code' => 'IMG004', 'name' => 'Pelvic Ultrasound', 'description' => 'Pelvic ultrasound imaging', 'price' => 110.00, 'category' => 'Imaging'],
            
            // Emergency/Procedure Services
            ['code' => 'PROC001', 'name' => 'Wound Dressing', 'description' => 'Wound cleaning and dressing', 'price' => 40.00, 'category' => 'Procedures'],
            ['code' => 'PROC002', 'name' => 'Injection Service', 'description' => 'Intramuscular or intravenous injection', 'price' => 15.00, 'category' => 'Procedures'],
            ['code' => 'PROC003', 'name' => 'Minor Surgery', 'description' => 'Minor surgical procedure', 'price' => 250.00, 'category' => 'Procedures'],
            ['code' => 'PROC004', 'name' => 'Suture Removal', 'description' => 'Suture removal service', 'price' => 20.00, 'category' => 'Procedures'],
            
            // Medication/Drugs
            ['code' => 'MED001', 'name' => 'Paracetamol (500mg x 10)', 'description' => 'Paracetamol tablets', 'price' => 12.00, 'category' => 'Medication'],
            ['code' => 'MED002', 'name' => 'Amoxicillin (500mg x 10)', 'description' => 'Antibiotic tablets', 'price' => 25.00, 'category' => 'Medication'],
            ['code' => 'MED003', 'name' => 'Multivitamin (30 tablets)', 'description' => 'Multivitamin supplements', 'price' => 35.00, 'category' => 'Medication'],
            
            // Vaccination Services
            ['code' => 'VAC001', 'name' => 'COVID-19 Vaccine', 'description' => 'COVID-19 vaccination', 'price' => 0.00, 'category' => 'Vaccination'],
            ['code' => 'VAC002', 'name' => 'Yellow Fever Vaccine', 'description' => 'Yellow fever vaccination', 'price' => 30.00, 'category' => 'Vaccination'],
            ['code' => 'VAC003', 'name' => 'Routine Vaccination', 'description' => 'Routine childhood vaccination', 'price' => 20.00, 'category' => 'Vaccination'],
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['code' => $service['code']], $service);
        }
    }
}
