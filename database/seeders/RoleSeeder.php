<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            [
                'name' => 'administrator',
                'display_name' => 'Administrator',
                'description' => 'Full system access with ability to manage all users, roles, and permissions',
            ],
            [
                'name' => 'record_officer',
                'display_name' => 'Record Officer',
                'description' => 'Manages patient medical records and documentation',
            ],
            [
                'name' => 'nurse',
                'display_name' => 'Nurse',
                'description' => 'Manages patient care, vital signs, and treatment notes',
            ],
            [
                'name' => 'doctor',
                'display_name' => 'Doctor',
                'description' => 'Diagnoses patients, prescribes treatments, and manages prescriptions',
            ],
            [
                'name' => 'pharmacist',
                'display_name' => 'Pharmacist',
                'description' => 'Manages medications and pharmacy inventory',
            ],
            [
                'name' => 'lab_technician',
                'display_name' => 'Lab Technician',
                'description' => 'Manages laboratory tests and test results',
            ],
            [
                'name' => 'accountant',
                'display_name' => 'Accountant',
                'description' => 'Manages billing, payments, and financial records',
            ],
            [
                'name' => 'radiologist',
                'display_name' => 'Accountant',
                'description' => 'Manages billing, payments, and financial records',
            ],

            [
                'name' => 'head_of_department',
                'display_name' => 'Head of Department',
                'description' => 'Manages expenses, consumable, and consumable stoch, financial records generation and reporting',
            ],
        ];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role['name']], $role);
        }
    }
}
