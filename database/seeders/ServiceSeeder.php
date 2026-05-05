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
            [
            'code'=>'SVC01',
            'description'=>'',
            'category'=>'General',
            'price'=>2000, 
            'name'=>'Opening of Personal File'
            ],
            [
            'code'=>'SVC02',
            'description'=>'',
            'category'=>'General',
            'price'=>3000, 
            'name'=>'Opening Family File'
            ],
            [
            'code'=>'SVC03',
            'description'=>'',
            'category'=>'General',
            'price'=>1000,   
            'name'=>'Family File Update'
            ],
            [
            'code'=>'SVC04',
            'description'=>'',
            'category'=>'General',
            'price'=>2000,  
            'name'=>'Normal Bed Space'
            ],
            [
            'code'=>'SVC05',
            'description'=>'',
            'category'=>'General',
            'price'=>5000,    
            'name'=>'Amenity Room'
            ],
            [
            'code'=>'SVC06',
            'description'=>'',
            'category'=>'General',
            'price'=>2000,    
            'name'=>'Specialist Consultation'
            ],
            [
            'code'=>'SVC07',
            'description'=>'',
            'category'=>'General',
            'price'=>500,    
            'name'=>'Nursing Service Fee'
            ],
            [
            'code'=>'SVC08',
            'description'=>'',
            'category'=>'General',
            'price'=>1000,    
            'name'=>'Observation Charges'
            ],
            [
            'code'=>'SVC09',
            'description'=>'',
            'category'=>'General',
            'price'=>500,   
            'name'=>'A & E Card'
            ],
            [
            'code'=>'SVC10',
            'description'=>'',
            'category'=>'General',
            'price'=>1000,    
            'name'=>'A & E Card'
            ],
            [
            'code'=>'SVC11',
            'description'=>'',
            'category'=>'General',
            'price'=>2000,    
            'name'=>'A & E Card'
            ],
            [
            'code'=>'SVC12',
            'description'=>'',
            'category'=>'General',
            'price'=>10000,    
            'name'=>'MVA'
            ],
            [
            'code'=>'SVC13',
            'description'=>'',
            'category'=>'General',
            'price'=>10000,    
            'name'=>'Normal Labour'
            ],
            [
            'code'=>'SVC14',
            'description'=>'',
            'category'=>'General',
            'price'=>200000,    
            'name'=>'CS Labour'
            ],
            [
            'code'=>'SVC15',
            'description'=>'',
            'category'=>'General',
            'price'=>500,    
            'name'=>'Dressing Fee'
            ],
            [
            'code'=>'SVC16',
            'description'=>'',
            'category'=>'General',
            'price'=>1000,    
            'name'=>'Dressing Fee'
            ],
            [
            'code'=>'SVC17',
            'description'=>'',
            'category'=>'General',
            'price'=>500,    
            'name'=>'ANC Service Fee'
            ]
        ];

        foreach ($services as $service) {
            Service::firstOrCreate(['code' => $service['code']], $service);
        }
    }
}
