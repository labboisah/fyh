<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Models\InvestigationType;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('investigation_id');
            $table->text('name');
            $table->text('unit')->nullable();
            $table->text('reference_range')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
        
        $investigations = [

            /* =========================
            LABORATORY INVESTIGATIONS
            ========================= */
            "Laboratory Investigations" => [

                [
                    "name" => "Complete Blood Count (CBC)",
                    "parameters" => [
                        ["name" => "Hemoglobin", "unit" => "g/dL", "range" => "12 - 17"],
                        ["name" => "WBC", "unit" => "×10^9/L", "range" => "4 - 11"],
                        ["name" => "Platelets", "unit" => "×10^9/L", "range" => "150 - 450"]
                    ]
                ],

                [
                    "name" => "Blood Glucose (Fasting / Random)",
                    "parameters" => [
                        ["name" => "Fasting Glucose", "unit" => "mg/dL", "range" => "70 - 99"],
                        ["name" => "Random Glucose", "unit" => "mg/dL", "range" => "< 140"]
                    ]
                ],

                [
                    "name" => "HbA1c",
                    "parameters" => [
                        ["name" => "HbA1c", "unit" => "%", "range" => "4.0 - 5.6"]
                    ]
                ],

                [
                    "name" => "Lipid Profile",
                    "parameters" => [
                        ["name" => "Total Cholesterol", "unit" => "mg/dL", "range" => "< 200"],
                        ["name" => "HDL", "unit" => "mg/dL", "range" => "> 40"],
                        ["name" => "LDL", "unit" => "mg/dL", "range" => "< 100"],
                        ["name" => "Triglycerides", "unit" => "mg/dL", "range" => "< 150"]
                    ]
                ],

                [
                    "name" => "Liver Function Test (LFT)",
                    "parameters" => [
                        ["name" => "ALT", "unit" => "U/L", "range" => "7 - 56"],
                        ["name" => "AST", "unit" => "U/L", "range" => "5 - 40"],
                        ["name" => "Total Bilirubin", "unit" => "mg/dL", "range" => "0.1 - 1.2"]
                    ]
                ],

                [
                    "name" => "Renal Function Test (RFT)",
                    "parameters" => [
                        ["name" => "Urea", "unit" => "mg/dL", "range" => "7 - 20"],
                        ["name" => "Creatinine", "unit" => "mg/dL", "range" => "0.6 - 1.3"]
                    ]
                ],

                [
                    "name" => "Electrolyte Panel",
                    "parameters" => [
                        ["name" => "Sodium (Na+)", "unit" => "mmol/L", "range" => "135 - 145"],
                        ["name" => "Potassium (K+)", "unit" => "mmol/L", "range" => "3.5 - 5.0"]
                    ]
                ],

                [
                    "name" => "Malaria Parasite Test",
                    "parameters" => [
                        ["name" => "Malaria Parasite", "unit" => "Positive/Negative", "range" => "Negative"]
                    ]
                ],

                [
                    "name" => "Widal Test",
                    "parameters" => [
                        ["name" => "Salmonella Typhi O", "unit" => "Titre", "range" => "< 1:80"],
                        ["name" => "Salmonella Typhi H", "unit" => "Titre", "range" => "< 1:80"]
                    ]
                ],

                [
                    "name" => "Hepatitis B Surface Antigen (HBsAg)",
                    "parameters" => [
                        ["name" => "HBsAg", "unit" => "Positive/Negative", "range" => "Negative"]
                    ]
                ],

                [
                    "name" => "Hepatitis C Antibody",
                    "parameters" => [
                        ["name" => "HCV Antibody", "unit" => "Positive/Negative", "range" => "Negative"]
                    ]
                ],

                [
                    "name" => "HIV Test",
                    "parameters" => [
                        ["name" => "HIV I & II", "unit" => "Positive/Negative", "range" => "Negative"]
                    ]
                ],

                [
                    "name" => "Urinalysis",
                    "parameters" => [
                        ["name" => "Protein", "unit" => "mg/dL", "range" => "0 - 8"],
                        ["name" => "Glucose", "unit" => "mg/dL", "range" => "Negative"]
                    ]
                ],

                [
                    "name" => "Stool Microscopy",
                    "parameters" => [
                        ["name" => "Ova/Parasite", "unit" => "Present/Absent", "range" => "Absent"]
                    ]
                ],

                [
                    "name" => "Blood Culture",
                    "parameters" => [
                        ["name" => "Organism Isolated", "unit" => "Text", "range" => "No Growth"]
                    ]
                ],

                [
                    "name" => "Thyroid Function Test (TFT)",
                    "parameters" => [
                        ["name" => "TSH", "unit" => "mIU/L", "range" => "0.4 - 4.0"],
                        ["name" => "T3", "unit" => "ng/dL", "range" => "80 - 200"],
                        ["name" => "T4", "unit" => "µg/dL", "range" => "5 - 12"]
                    ]
                ],

                [
                    "name" => "Prostate Specific Antigen (PSA)",
                    "parameters" => [
                        ["name" => "PSA", "unit" => "ng/mL", "range" => "< 4.0"]
                    ]
                ],
            ],

            /* =========================
            RADIOLOGICAL INVESTIGATIONS
            ========================= */
            "Radiological Investigations" => [

                [
                    "name" => "Chest X-ray",
                    "parameters" => [
                        ["name" => "Radiologist Impression", "unit" => "Text", "range" => "Normal"]
                    ]
                ],

                [
                    "name" => "CT Scan (Head)",
                    "parameters" => [
                        ["name" => "Findings", "unit" => "Text", "range" => "Normal"]
                    ]
                ],

                [
                    "name" => "MRI Brain",
                    "parameters" => [
                        ["name" => "Impression", "unit" => "Text", "range" => "Normal"]
                    ]
                ],
            ],

            /* =========================
            CARDIOLOGY
            ========================= */
            "Cardiology Investigations" => [

                [
                    "name" => "Electrocardiogram (ECG)",
                    "parameters" => [
                        ["name" => "Heart Rate", "unit" => "bpm", "range" => "60 - 100"]
                    ]
                ],

                [
                    "name" => "Cardiac Enzyme Test (Troponin)",
                    "parameters" => [
                        ["name" => "Troponin I", "unit" => "ng/mL", "range" => "< 0.04"]
                    ]
                ],
            ],

        ];

        foreach ($investigations as $category => $tests) {
            $count = 1;
            foreach ($tests as $test) {
                $investigationType = InvestigationType::firstOrCreate(['name' => $category]);

                $investigation = $investigationType->investigations()->create([
                    'investigation_type_id' => $investigationType->id,
                    'name' => $test['name'],
                    'price'=>    rand(2000,10000),
                    'code'=> 'INV-0'.$count
                ]);
                $investigation->parameters()->createMany($test['parameters']);
                $count++;
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('parameters');
    }
};
