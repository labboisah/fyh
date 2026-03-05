<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Department;
use App\Models\InvestigationType;
use App\Models\Investigation;

class InvestigationSeeder extends Seeder
{
    public function run()
    {

        /*
        |-----------------------------------------------------
        | DEPARTMENTS
        |-----------------------------------------------------
        */

        $radiology = Department::create(['name' => 'Radiology']);
        $laboratory = Department::create(['name' => 'Laboratory']);
        $pharmacy = Department::create(['name' => 'Pharmacy']);
        $record = Department::create(['name' => 'Record']);
        


        /*
        |-----------------------------------------------------
        | RADIOLOGY TYPES
        |-----------------------------------------------------
        */

        $xray = InvestigationType::create([
            'department_id' => $radiology->id,
            'name' => 'X-Ray'
        ]);

        $contrast = InvestigationType::create([
            'department_id' => $radiology->id,
            'name' => 'Contrast Studies'
        ]);

        $ultrasound = InvestigationType::create([
            'department_id' => $radiology->id,
            'name' => 'Ultrasound'
        ]);

        $cardiology = InvestigationType::create([
            'department_id' => $radiology->id,
            'name' => 'Cardiology'
        ]);


        /*
        |-----------------------------------------------------
        | LAB TYPES
        |-----------------------------------------------------
        */

        $chemical = InvestigationType::create([
            'department_id' => $laboratory->id,
            'name' => 'Chemical Pathology'
        ]);

        $haematology = InvestigationType::create([
            'department_id' => $laboratory->id,
            'name' => 'Haematology'
        ]);

        $microbiology = InvestigationType::create([
            'department_id' => $laboratory->id,
            'name' => 'Microbiology'
        ]);


        /*
        |-----------------------------------------------------
        | RADIOLOGY - X RAY
        |-----------------------------------------------------
        */

        $xrays = [

            ['Chest X-Ray AP',5000],
            ['Chest AP & LAT',8000],
            ['Abdominal X-Ray (KUB)',6000],
            ['Plain Abdominal X-Ray (Erect & Supine)',10000],
            ['Foot AP & LAT',8000],
            ['Ankle AP & LAT',8000],
            ['Leg/Tibia and Fibula',8000],
            ['Femur AP & LAT',8000],
            ['Knee AP & LAT',8000],
            ['Hip AP & LAT/Both',10000],
            ['Hand AP & LAT',8000],
            ['Wrist AP & LAT',8000],
            ['Forearm / Radius & Ulna',8000],
            ['Elbow Joint',8000],
            ['Humerus AP & LAT',8000],
            ['Shoulder Joint AP',8000],
            ['Skull X-Ray',6000],
            ['Mandible / Jaw X-Ray',8000],
            ['Paranasal Sinuses',8000],
            ['Postnasal Space',8000],
            ['Cervical Spine',10000],
            ['Thoracic Spine',10000],
            ['Lumbosacral Spine',10000],
            ['Thoracolumbar Spine',10000],
            ['Total Spine',20000],
            ['Both Shoulder',12000],
            ['Both Humerus',12000],
            ['Both Elbow',12000],
            ['Both Forearm',12000],
            ['Both Wrist',12000],
            ['Both Hands',12000],
            ['Both Femur',12000],
            ['Both Knee',12000],
            ['Both Legs/Tibia Fibula',12000],
            ['Both Ankles',12000],
            ['Both Feet',12000]

        ];

        foreach($xrays as $x){

            Investigation::create([
                'investigation_type_id'=>$xray->id,
                'name'=>$x[0],
                'price'=>$x[1]
            ]);

        }


        /*
        |-----------------------------------------------------
        | RADIOLOGY - CONTRAST
        |-----------------------------------------------------
        */

        $contrastStudies = [

            ['IVU',30000],
            ['HSG',25000],
            ['RUCG',20000],
            ['MUCG',25000],
            ['RUCG/MUCG',35000],
            ['Sinography',15000],
            ['Genitography',25000],
            ['Barium Enema',35000],
            ['Barium Meal',25000],
            ['Barium Swallow',15000],
            ['Barium Swallow/Meal',30000],
            ['Sialography',25000],
            ['Fistulography',10000]

        ];

        foreach($contrastStudies as $c){

            Investigation::create([
                'investigation_type_id'=>$contrast->id,
                'name'=>$c[0],
                'price'=>$c[1]
            ]);

        }



        /*
        |-----------------------------------------------------
        | ULTRASOUND
        |-----------------------------------------------------
        */

        $ultrasounds = [

            ['Abdominal USS',3000],
            ['Abdominal Pelvic USS',3000],
            ['Pelvic USS',3000],
            ['Obstetric USS',3000],
            ['Lessional USS',4500],
            ['Knee USS',4500],
            ['Arm USS',4500],
            ['Thyroid USS',4500],
            ['Neck USS',4500],
            ['Transfuntal USS',4500],
            ['TVS USS',4500],
            ['Scrotal USS',4500],
            ['Renal USS',4500],
            ['Doppler USS',15000],
            ['Sono HSG',15000]

        ];

        foreach($ultrasounds as $u){

            Investigation::create([
                'investigation_type_id'=>$ultrasound->id,
                'name'=>$u[0],
                'price'=>$u[1]
            ]);

        }



        /*
        |-----------------------------------------------------
        | CARDIOLOGY
        |-----------------------------------------------------
        */

        Investigation::create([
            'investigation_type_id'=>$cardiology->id,
            'name'=>'ECG',
            'price'=>4000
        ]);

        Investigation::create([
            'investigation_type_id'=>$cardiology->id,
            'name'=>'ECHO',
            'price'=>15000
        ]);



        /*
        |-----------------------------------------------------
        | CHEMICAL PATHOLOGY
        |-----------------------------------------------------
        */

        $chemicalTests = [

            ['Fasting Blood Sugar',1000],
            ['Random Blood Sugar',1000],
            ['2 Hours Postprandial',1500],
            ['EUCr',4000],
            ['LFT',5500],
            ['OGTT',5000],
            ['Uric Acid',3500],
            ['Fasting Lipid Profile',6000],
            ['Calcium',4000],
            ['Magnesium',3000],
            ['Phosphorous',3000],
            ['Bilirubin Total',2000],
            ['Bilirubin Direct',2000],
            ['Urinalysis',1000],
            ['Pregnancy Test',1200],
            ['CSF Biochemistry',3000],
            ['CSF Protein',2000],
            ['Albumin',2000],
            ['CSF Glucose',2000],
            ['Electrolytes',3000],
            ['Protein (Albumin , T/Protein)',3000],
            ['Potassium',2000],
            ['PSA',6500],
            ['HBa1c',7000],
            ['T3',5000],
            ['T4',5000],
            ['TSH',5000],
            ['B-HcG',6000],
            ['TFT',15000],
            ['LH',7000],
            ['FSH',7000],
            ['Prolactin',7000],
            ['CRP',8000]

        ];

        foreach($chemicalTests as $test){

            Investigation::create([
                'investigation_type_id'=>$chemical->id,
                'name'=>$test[0],
                'price'=>$test[1]
            ]);

        }



        /*
        |-----------------------------------------------------
        | HAEMATOLOGY
        |-----------------------------------------------------
        */

        $haemTests = [

            ['Full Blood Count',3000],
            ['Packed Cell Volume',1000],
            ['ESR',2000],
            ['Genotype',1500],
            ['Blood Group',1000],
            ['Blood Grouping & Crossmatching',7500],
            ['RVS',2000],
            ['HBsAg',1700],
            ['HCV',1700],
            ['Clotting Time',2000],
            ['Hepatitis B Profile',6000],
            ['DCT',3000],
            ['ICT',3000],
            ['PT',3000],
            ['PTTK',3000],
            ['Clotting Profile',6000]

        ];

        foreach($haemTests as $test){

            Investigation::create([
                'investigation_type_id'=>$haematology->id,
                'name'=>$test[0],
                'price'=>$test[1]
            ]);

        }



        /*
        |-----------------------------------------------------
        | MICROBIOLOGY
        |-----------------------------------------------------
        */

        $microTests = [

            ['MP Microscopy',1500],
            ['MP RDT',800],
            ['Widal',1500],
            ['H. Pylori',2000],
            ['Stool Microscopy',2000],
            ['Urine Microscopy',2000],
            ['Stool MCS',4000],
            ['Urine MCS',4000],
            ['Sputum MCS',4000],
            ['Mantoux',3000],
            ['Swab MCS',4000],
            ['HVS MCS',4000],
            ['ECS MCS',4000],
            ['VDRL',1500]

        ];

        foreach($microTests as $test){

            Investigation::create([
                'investigation_type_id'=>$microbiology->id,
                'name'=>$test[0],
                'price'=>$test[1]
            ]);

        }

        $otherDepartments = [
            'Accident & Emergency (A&E) / Emergency Department',
            'General Outpatient Department (GOPD)',
            'Internal Medicine',
            'Surgery',
            'Pediatrics',
            'Obstetrics & Gynecology',
            'Orthopedics',
            'Cardiology',
            'Neurology',
            'Nephrology',
            'Urology',
            'Gastroenterology',
            'Dermatology',
            'Psychiatry',
            'Oncology',
            'Ophthalmology (Eye Clinic)',
            'Ear, Nose & Throat (ENT)',
            'Dental / Dentistry',
            'Physiotherapy / Rehabilitation',
            'Family Medicine',
            'Infectious Diseases'
        ];

        foreach($otherDepartments as $department){
            Department::firstOrCreate(['name'=>$department]);
        }

    }
}