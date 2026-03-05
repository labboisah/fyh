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

        $recordOfficer = User::firstOrCreate(
            ['email' => 'record@hospital.test'],
            [
                'name' => 'Record Officer',
                'password' => Hash::make('record@123'),
                'department_id'=>4
            ]);
            
        $recordOfficerRole = Role::where('name', 'record_officer')->first();
        if ($recordOfficerRole && !$recordOfficer->hasRole('record_officer')) {
            $recordOfficer->assignRole($recordOfficerRole);
        }

        // assign acoountant role to record officer 
        if ($recordOfficerRole && !$recordOfficer->hasRole('accountant')) { 
            $recordOfficer->assignRole('accountant'); 
        }

         $pharmacist = User::firstOrCreate(
            ['email' => 'pharmacist@hospital.test'],
            [
                'name' => 'Pharmacist',
                'password' => Hash::make('pharm@123'),
                'department_id'=>3
            ]);
            
        $pharmacistRole = Role::where('name', 'pharmacist')->first();
        if ($pharmacistRole && !$pharmacist->hasRole('pharmacist')) {
            $pharmacist->assignRole($pharmacistRole);
        }

         $labTechnician = User::firstOrCreate(
            ['email' => 'lab@hospital.test'],
            [
                'name' => 'Lab Technician',
                'password' => Hash::make('lab@123'),
                'department_id'=>2
            ]);
            
        $labTechnicianRole = Role::where('name', 'lab_technician')->first();
        if ($labTechnicianRole && !$labTechnician->hasRole('lab_technician')) {
            $labTechnician->assignRole($labTechnicianRole);
        }

            $nurse = User::firstOrCreate(
                ['email' => 'nurse@hospital.test'],
                [
                    'name' => 'Nurse',
                    'password' => Hash::make('nurse@123'),
                ]);
                
        $nurseRole = Role::where('name', 'nurse')->first();
        if ($nurseRole && !$nurse->hasRole('nurse')) {
            $nurse->assignRole($nurseRole);
        }

        $doctor = User::firstOrCreate(
            ['email' => 'doctor@hospital.test'],
            [
                'name' => 'Doctor',
                'password' => Hash::make('doctor@123'),
            ]);
            
        $doctorRole = Role::where('name', 'doctor')->first();
        if ($doctorRole && !$doctor->hasRole('doctor')) {
            $doctor->assignRole($doctorRole);
        }

        $radiologist = User::firstOrCreate(
            ['email' => 'radio@hospital.test'],
            [
                'name' => 'Radiologist',
                'password' => Hash::make('radio@123'),
                'department_id'=>1
            ]);
            
        $radiologistRole = Role::where('name', 'radiologist')->first();
        if ($radiologistRole && !$radiologist->hasRole('radiologist')) {
            $radiologist->assignRole($radiologistRole);
        }

    }
}
