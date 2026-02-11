<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            // Role Management Permissions (Admin Only)
            [
                'name' => 'manage_roles',
                'display_name' => 'Manage Roles',
                'description' => 'Create, edit, and delete roles',
                'module' => 'Administration',
            ],
            [
                'name' => 'manage_permissions',
                'display_name' => 'Manage Permissions',
                'description' => 'Create, edit, and delete permissions',
                'module' => 'Administration',
            ],
            [
                'name' => 'manage_users',
                'display_name' => 'Manage Users',
                'description' => 'Create, edit, delete users and assign roles',
                'module' => 'Administration',
            ],
            [
                'name' => 'view_audit_logs',
                'display_name' => 'View Audit Logs',
                'description' => 'View system audit logs and activity',
                'module' => 'Administration',
            ],

            // Patient Records Permissions
            [
                'name' => 'view_records',
                'display_name' => 'View Patient Records',
                'description' => 'View patient medical records',
                'module' => 'Patient Records',
            ],
            [
                'name' => 'create_records',
                'display_name' => 'Create Patient Records',
                'description' => 'Create new patient medical records',
                'module' => 'Patient Records',
            ],
            [
                'name' => 'edit_records',
                'display_name' => 'Edit Patient Records',
                'description' => 'Edit existing patient medical records',
                'module' => 'Patient Records',
            ],
            [
                'name' => 'delete_records',
                'display_name' => 'Delete Patient Records',
                'description' => 'Delete patient medical records',
                'module' => 'Patient Records',
            ],
            [
                'name' => 'export_records',
                'display_name' => 'Export Patient Records',
                'description' => 'Export patient records to external formats',
                'module' => 'Patient Records',
            ],

            // Patient Management Permissions
            [
                'name' => 'view_patients',
                'display_name' => 'View Patients',
                'description' => 'View patient list and details',
                'module' => 'Patient Management',
            ],
            [
                'name' => 'create_patients',
                'display_name' => 'Create Patients',
                'description' => 'Register new patients',
                'module' => 'Patient Management',
            ],
            [
                'name' => 'edit_patients',
                'display_name' => 'Edit Patients',
                'description' => 'Edit patient information',
                'module' => 'Patient Management',
            ],
            [
                'name' => 'delete_patients',
                'display_name' => 'Delete Patients',
                'description' => 'Delete patient records',
                'module' => 'Patient Management',
            ],

            // Appointment Permissions
            [
                'name' => 'view_appointments',
                'display_name' => 'View Appointments',
                'description' => 'View appointment schedule',
                'module' => 'Appointments',
            ],
            [
                'name' => 'create_appointments',
                'display_name' => 'Create Appointments',
                'description' => 'Schedule new appointments',
                'module' => 'Appointments',
            ],
            [
                'name' => 'edit_appointments',
                'display_name' => 'Edit Appointments',
                'description' => 'Modify appointments',
                'module' => 'Appointments',
            ],
            [
                'name' => 'cancel_appointments',
                'display_name' => 'Cancel Appointments',
                'description' => 'Cancel appointments',
                'module' => 'Appointments',
            ],

            // Prescription Permissions
            [
                'name' => 'view_prescriptions',
                'display_name' => 'View Prescriptions',
                'description' => 'View patient prescriptions',
                'module' => 'Prescriptions',
            ],
            [
                'name' => 'create_prescriptions',
                'display_name' => 'Create Prescriptions',
                'description' => 'Issue new prescriptions',
                'module' => 'Prescriptions',
            ],
            [
                'name' => 'edit_prescriptions',
                'display_name' => 'Edit Prescriptions',
                'description' => 'Modify prescriptions',
                'module' => 'Prescriptions',
            ],
            [
                'name' => 'approve_prescriptions',
                'display_name' => 'Approve Prescriptions',
                'description' => 'Approve and verify prescriptions',
                'module' => 'Prescriptions',
            ],

            // Laboratory Permissions
            [
                'name' => 'view_lab_tests',
                'display_name' => 'View Lab Tests',
                'description' => 'View laboratory test requests and results',
                'module' => 'Laboratory',
            ],
            [
                'name' => 'create_lab_tests',
                'display_name' => 'Create Lab Tests',
                'description' => 'Request laboratory tests',
                'module' => 'Laboratory',
            ],
            [
                'name' => 'edit_lab_tests',
                'display_name' => 'Edit Lab Tests',
                'description' => 'Modify laboratory test records',
                'module' => 'Laboratory',
            ],
            [
                'name' => 'submit_lab_results',
                'display_name' => 'Submit Lab Results',
                'description' => 'Submit laboratory test results',
                'module' => 'Laboratory',
            ],

            // Pharmacy Permissions
            [
                'name' => 'view_inventory',
                'display_name' => 'View Inventory',
                'description' => 'View pharmacy inventory',
                'module' => 'Pharmacy',
            ],
            [
                'name' => 'manage_inventory',
                'display_name' => 'Manage Inventory',
                'description' => 'Manage medication inventory and stock',
                'module' => 'Pharmacy',
            ],
            [
                'name' => 'dispense_medications',
                'display_name' => 'Dispense Medications',
                'description' => 'Dispense medications to patients',
                'module' => 'Pharmacy',
            ],
            [
                'name' => 'view_expiry_dates',
                'display_name' => 'View Expiry Dates',
                'description' => 'View medication expiry dates and expirations',
                'module' => 'Pharmacy',
            ],

            // Billing & Finance Permissions
            [
                'name' => 'view_billing',
                'display_name' => 'View Billing',
                'description' => 'View patient bills and invoices',
                'module' => 'Billing',
            ],
            [
                'name' => 'create_billing',
                'display_name' => 'Create Billing',
                'description' => 'Create patient bills and invoices',
                'module' => 'Billing',
            ],
            [
                'name' => 'process_payments',
                'display_name' => 'Process Payments',
                'description' => 'Process patient payments',
                'module' => 'Billing',
            ],
            [
                'name' => 'view_financial_reports',
                'display_name' => 'View Financial Reports',
                'description' => 'View financial and revenue reports',
                'module' => 'Billing',
            ],

            // Vital Signs & Observations
            [
                'name' => 'record_vitals',
                'display_name' => 'Record Vital Signs',
                'description' => 'Record patient vital signs',
                'module' => 'Clinical',
            ],
            [
                'name' => 'view_vitals',
                'display_name' => 'View Vital Signs',
                'description' => 'View patient vital signs and observations',
                'module' => 'Clinical',
            ],

            // Diagnosis & Treatment
            [
                'name' => 'record_diagnosis',
                'display_name' => 'Record Diagnosis',
                'description' => 'Record patient diagnosis',
                'module' => 'Clinical',
            ],
            [
                'name' => 'view_diagnosis',
                'display_name' => 'View Diagnosis',
                'description' => 'View patient diagnosis',
                'module' => 'Clinical',
            ],
            [
                'name' => 'record_treatment',
                'display_name' => 'Record Treatment',
                'description' => 'Record patient treatment',
                'module' => 'Clinical',
            ],

            // Patient Admissions, Discharges & Referrals
            [
                'name' => 'manage_patient_visits',
                'display_name' => 'Manage Patient Visits',
                'description' => 'Record and maintain patient visit histories',
                'module' => 'Patient Management',
            ],
            [
                'name' => 'manage_patient_admissions',
                'display_name' => 'Manage Patient Admissions',
                'description' => 'Document patient admissions',
                'module' => 'Patient Management',
            ],
            [
                'name' => 'manage_patient_discharges',
                'display_name' => 'Manage Patient Discharges',
                'description' => 'Document patient discharges',
                'module' => 'Patient Management',
            ],
            [
                'name' => 'manage_patient_referrals',
                'display_name' => 'Manage Patient Referrals',
                'description' => 'Document patient referrals',
                'module' => 'Patient Management',
            ],
            [
                'name' => 'search_patients',
                'display_name' => 'Search Patients',
                'description' => 'Search and retrieve patient records using various criteria',
                'module' => 'Patient Management',
            ],
        ];

        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission['name']], $permission);
        }

        // Assign permissions to roles
        $this->assignPermissionsToRoles();
    }

    /**
     * Assign permissions to their respective roles.
     */
    private function assignPermissionsToRoles(): void
    {
        $admin = Role::where('name', 'administrator')->first();
        $recordOfficer = Role::where('name', 'record_officer')->first();
        $nurse = Role::where('name', 'nurse')->first();
        $doctor = Role::where('name', 'doctor')->first();
        $pharmacist = Role::where('name', 'pharmacist')->first();
        $labTechnician = Role::where('name', 'lab_technician')->first();
        $accountant = Role::where('name', 'accountant')->first();

        // Administrator - All permissions
        if ($admin) {
            $allPermissions = Permission::all();
            $admin->permissions()->sync($allPermissions->pluck('id')->toArray());
        }

        // Record Officer
        if ($recordOfficer) {
            $permissions = Permission::whereIn('name', [
                'view_records', 'create_records', 'edit_records', 'export_records',
                'view_patients', 'create_patients', 'edit_patients',
                'view_appointments', 'create_appointments', 'edit_appointments', 'cancel_appointments',
                'manage_patient_visits', 'manage_patient_admissions', 'manage_patient_discharges',
                'manage_patient_referrals', 'search_patients'
            ])->pluck('id')->toArray();
            $recordOfficer->permissions()->sync($permissions);
        }

        // Nurse
        if ($nurse) {
            $permissions = Permission::whereIn('name', [
                'view_records', 'view_patients', 'view_appointments',
                'record_vitals', 'view_vitals', 'view_diagnosis', 'record_treatment',
                'view_prescriptions', 'view_lab_tests'
            ])->pluck('id')->toArray();
            $nurse->permissions()->sync($permissions);
        }

        // Doctor
        if ($doctor) {
            $permissions = Permission::whereIn('name', [
                'view_records', 'edit_records', 'view_patients',
                'create_appointments', 'view_appointments',
                'create_prescriptions', 'view_prescriptions', 'edit_prescriptions',
                'record_diagnosis', 'view_diagnosis', 'record_treatment',
                'view_vitals', 'create_lab_tests', 'view_lab_tests'
            ])->pluck('id')->toArray();
            $doctor->permissions()->sync($permissions);
        }

        // Pharmacist
        if ($pharmacist) {
            $permissions = Permission::whereIn('name', [
                'view_prescriptions', 'approve_prescriptions',
                'view_inventory', 'manage_inventory', 'dispense_medications',
                'view_expiry_dates', 'view_patients'
            ])->pluck('id')->toArray();
            $pharmacist->permissions()->sync($permissions);
        }

        // Lab Technician
        if ($labTechnician) {
            $permissions = Permission::whereIn('name', [
                'view_lab_tests', 'edit_lab_tests', 'submit_lab_results',
                'view_patients', 'view_records'
            ])->pluck('id')->toArray();
            $labTechnician->permissions()->sync($permissions);
        }

        // Accountant
        if ($accountant) {
            $permissions = Permission::whereIn('name', [
                'view_billing', 'create_billing', 'process_payments',
                'view_financial_reports', 'view_patients'
            ])->pluck('id')->toArray();
            $accountant->permissions()->sync($permissions);
        }
    }
}
