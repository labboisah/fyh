<?php

namespace Database\Seeders;

use App\Models\Module;
use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;
use App\Models\Department;
use Illuminate\Support\Str;

class ModulePermissionSeeder extends Seeder
{
    public function run(): void
    {
        

        $departments = Department::all();

        foreach ($departments as $department) {

            $roleName = 'head_of_' . Str::slug($department->name, '_');

            Role::updateOrCreate(
                ['name' => $roleName],
                [
                    'display_name' => ucwords(strtolower($department->name)),
                    'description' => 'Head of department role for ' . $department->name,
                ]
            );
        }

        $modules = [
            [
                'label' => 'Patients',
                'name' => 'record_patients',
                'icon' => 'bi-people-fill',
                'route' => 'record.patients.index',
                'group' => 'record',
                'roles' => ['record'],
                'permissions' => [
                    'patient.read',
                    'patient.create',
                    'patient.update',
                    'patient.delete',
                ],
            ],

            [
                'label' => 'Patients',
                'name' => 'accountant_patients',
                'icon' => 'bi-people-fill',
                'route' => 'accountant.patients.index',
                'group' => 'accountant',
                'roles' => ['accountant'],
                'permissions' => [
                    'patient.read',
                ],
            ],

            [
                'label' => 'Patients',
                'name' => 'doctor_patients',
                'icon' => 'bi-people-fill',
                'route' => 'doctor.patients.index',
                'group' => 'doctor',
                'roles' => ['doctor'],
                'permissions' => [
                    'patient.read',
                ],
            ],

            [
                'label' => 'Patients',
                'name' => 'nurse_patients',
                'icon' => 'bi-people-fill',
                'route' => 'nurse.patients.index',
                'group' => 'nurse',
                'roles' => ['nurse'],
                'permissions' => [
                    'patient.read',
                ],
            ],
            [
                'label' => 'Patients',
                'name' => 'midwife_patients',
                'icon' => 'bi-people-fill',
                'route' => 'midwife.patients.index',
                'group' => 'midwife',
                'roles' => ['midwife'],
                'permissions' => [
                    'patient.read',
                ],
            ],

            [
                'label' => 'Vital Signs',
                'name' => 'vital_signs',
                'icon' => 'bi-heart-pulse',
                'route' => 'nurse.clinicals.vital-signs',
                'group' => 'nurse',
                'roles' => ['nurse', 'doctor'],
                'permissions' => [
                    'vital_sign.read',
                    'vital_sign.create',
                    'vital_sign.update',
                    'vital_sign.delete',
                ],
            ],

            [
                'label' => 'Observations',
                'name' => 'observations',
                'icon' => 'bi-eye',
                'route' => 'nurse.clinicals.observations',
                'group' => 'nurse',
                'roles' => ['nurse', 'doctor'],
                'permissions' => [
                    'observation.read',
                    'observation.create',
                    'observation.update',
                    'observation.delete',
                ],
            ],

            [
                'label' => 'Investigation Requests',
                'name' => 'investigation_requests',
                'icon' => 'bi-clipboard2-pulse',
                'route' => 'doctor.clinicals.investigations',
                'group' => 'clinical',
                'roles' => ['doctor', 'nurse'],
                'permissions' => [
                    'investigation_request.read',
                    'investigation_request.create',
                    'investigation_request.update',
                    'investigation_request.delete',
                ],
            ],

            [
                'label' => 'Lab Requests',
                'name' => 'lab_requests',
                'icon' => 'bi-vial',
                'route' => 'lab.requests.index',
                'group' => 'laboratory',
                'roles' => ['lab_technician', 'lab_scientist'],
                'permissions' => [
                    'laboratory_request.read',
                    'laboratory_request.create',
                    'laboratory_request.update',
                    'laboratory_request.delete',
                ],
            ],

            [
                'label' => 'Results Entry',
                'name' => 'lab_results',
                'icon' => 'bi-clipboard2-data',
                'route' => 'lab.result',
                'group' => 'laboratory',
                'roles' => ['lab_technician', 'lab_scientist'],
                'permissions' => [
                    'laboratory_result.read',
                    'laboratory_result.create',
                    'laboratory_result.update',
                    'laboratory_result.delete',
                    'laboratory_result.print',
                ],
            ],

            [
                'label' => 'Bills',
                'name' => 'bills',
                'icon' => 'bi-receipt',
                'route' => 'accountant.bills.index',
                'group' => 'finance',
                'roles' => ['accountant', 'administrator'],
                'permissions' => [
                    'bill.read',
                    'bill.create',
                    'bill.update',
                    'bill.delete',
                    'bill.print',
                ],
            ],

            [
                'label' => 'Payments',
                'name' => 'payments',
                'icon' => 'bi-credit-card-2-front',
                'route' => 'accountant.payments.index',
                'group' => 'finance',
                'roles' => ['accountant', 'administrator'],
                'permissions' => [
                    'payment.read',
                    'payment.create',
                    'payment.update',
                    'payment.delete',
                    'payment.print',
                ],
            ],

            [
                'label' => 'Users',
                'name' => 'users',
                'icon' => 'bi-people-fill',
                'route' => 'admin.users.index',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => [
                    'user.read',
                    'user.create',
                    'user.update',
                    'user.delete',
                ],
            ],

            [
                'label' => 'Data Sync',
                'name' => 'data_sync',
                'icon' => 'bi-cloud-arrow-up',
                'route' => 'admin.sync.index',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => [
                    'sync.read',
                    'sync.create',
                    'sync.update',
                ],
            ],
            [
                'name' => 'access_control',
                'label' => 'Access Control',
                'icon' => 'bi-shield-check',
                'route' => 'admin.access-control',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => ['role.read', 'permission.read', 'role.update', 'permission.update'],
            ],
            [
                'name' => 'users',
                'label' => 'Users',
                'icon' => 'bi-people-fill',
                'route' => 'admin.users.index',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => ['user.read', 'user.create', 'user.update', 'user.delete'],
            ],
            [
                'name' => 'departments',
                'label' => 'Departments',
                'icon' => 'bi-buildings',
                'route' => 'admin.departments.index',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => ['department.read', 'department.create', 'department.update', 'department.delete'],
            ],
            [
                'name' => 'admin_wards',
                'label' => 'Wards',
                'icon' => 'bi-hospital',
                'route' => 'admin.wards.index',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => ['ward.read', 'ward.create', 'ward.update', 'ward.delete'],
            ],
            [
                'name' => 'beds',
                'label' => 'Beds',
                'icon' => 'bi-hospital-fill',
                'route' => 'admin.beds.index',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => ['bed.read', 'bed.create', 'bed.update', 'bed.delete'],
            ],
            [
                'name' => 'admin_investigations',
                'label' => 'Investigations',
                'icon' => 'bi-clipboard2-data',
                'route' => 'admin.investigations.index',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => ['investigation.read', 'investigation.create', 'investigation.update', 'investigation.delete'],
            ],
            [
                'name' => 'services',
                'label' => 'Services',
                'icon' => 'bi-gear-fill',
                'route' => 'admin.services.index',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => ['service.read', 'service.create', 'service.update', 'service.delete'],
            ],
            [
                'name' => 'system_update',
                'label' => 'System Update',
                'icon' => 'bi-arrow-repeat',
                'route' => 'admin.system.update',
                'group' => 'administration',
                'roles' => ['administrator'],
                'permissions' => ['system.update'],
            ],

            // ADMIN FINANCE
            [
                'name' => 'admin_bills',
                'label' => 'Bills',
                'icon' => 'bi-receipt',
                'route' => 'admin.bills.index',
                'group' => 'finance',
                'roles' => ['administrator'],
                'permissions' => ['bill.read', 'bill.create', 'bill.update', 'bill.delete', 'bill.print'],
            ],
            [
                'name' => 'admin_payments',
                'label' => 'Payments',
                'icon' => 'bi-credit-card-2-front',
                'route' => 'admin.payments.index',
                'group' => 'finance',
                'roles' => ['administrator'],
                'permissions' => ['payment.read', 'payment.create', 'payment.update', 'payment.delete', 'payment.print'],
            ],
            [
                'name' => 'revenues',
                'label' => 'Revenues',
                'icon' => 'bi-graph-up-arrow',
                'route' => 'admin.revenues.index',
                'group' => 'finance',
                'roles' => ['administrator'],
                'permissions' => ['revenue.read', 'revenue.create', 'revenue.update', 'revenue.delete'],
            ],
            [
                'name' => 'expenses',
                'label' => 'Expenses',
                'icon' => 'bi-cash-stack',
                'route' => 'admin.expenses.index',
                'group' => 'finance',
                'roles' => ['administrator'],
                'permissions' => ['expense.read', 'expense.create', 'expense.update', 'expense.delete'],
            ],

            // ACCOUNTANT FINANCE
            [
                'name' => 'accountant_bills',
                'label' => 'Bills',
                'icon' => 'bi-receipt',
                'route' => 'accountant.bills.index',
                'group' => 'finance',
                'roles' => ['accountant'],
                'permissions' => ['bill.read', 'bill.create', 'bill.update', 'bill.print'],
            ],
            [
                'name' => 'accountant_payments',
                'label' => 'Payments',
                'icon' => 'bi-credit-card-2-front',
                'route' => 'accountant.payments.index',
                'group' => 'finance',
                'roles' => ['accountant'],
                'permissions' => ['payment.read', 'payment.create', 'payment.update', 'payment.print'],
            ],
            [
                'name' => 'accountant_billing_report',
                'label' => 'Billing Report',
                'icon' => 'bi-file-earmark-text',
                'route' => 'reports.finance.index',
                'group' => 'reports',
                'roles' => ['administrator', 'accountant'],
                'permissions' => ['bill.read', 'report.read'],
            ],
            [
                'name' => 'accountant_payment_report',
                'label' => 'Payment Report',
                'icon' => 'bi-bar-chart-line',
                'route' => 'reports.payments.index',
                'group' => 'reports',
                'roles' => ['administrator', 'accountant'],
                'permissions' => ['payment.read', 'report.read'],
            ],

            // GENERAL / ALL ROLES
            [
                'name' => 'activity_report',
                'label' => 'Activities',
                'icon' => 'bi-activity',
                'route' => 'reports.my-activities.index',
                'group' => 'reports',
                'roles' => [
                    'administrator',
                    'record',
                    'nurse',
                    'doctor',
                    'midwife',
                    'lab_technician',
                    'lab_scientist',
                    'radiologist',
                    'radiographer',
                    'accountant',
                    'pharmacist',
                    'head_of_department',
                ],
                'permissions' => ['activity.read'],
            ],

        ];

        foreach ($modules as $index => $item) {
            $module = Module::updateOrCreate(
                ['name' => $item['name']],
                [
                    'label' => $item['label'],
                    'route' => $item['route'],
                    'icon' => $item['icon'],
                    'group' => $item['group'],
                    'sort_order' => $index + 1,
                    'is_active' => true,
                ]
            );

            foreach ($item['permissions'] as $permissionName) {
                $action = str($permissionName)->afterLast('.')->toString();

                $permission = Permission::updateOrCreate(
                    ['name' => $permissionName],
                    [
                        'module_id' => $module->id,
                        'action' => $action,
                    ]
                );

                foreach ($item['roles'] as $roleName) {
                    $role = Role::where('name', $roleName)->first();

                    if ($role) {
                        $role->permissions()->syncWithoutDetaching([$permission->id]);
                    }
                }
            }

            foreach ($item['roles'] as $roleName) {
                $role = Role::where('name', $roleName)->first();

                if ($role) {
                    $role->modules()->syncWithoutDetaching([$module->id]);
                }
            }
        }

    }
}