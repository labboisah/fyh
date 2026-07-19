@php
    $sidebarUser = Auth::user();
    $roles = $sidebarUser?->roles()->with('permissions')->orderBy('name')->get() ?? collect();
    $roleNames = $roles->pluck('name')->all();
    $temporaryPermissions = $sidebarUser?->temporaryPermissions()->with('permission')->active()->get() ?? collect();
    $temporaryPermissionNames = $temporaryPermissions->pluck('permission.name')->filter()->values()->all();

    $isRouteActive = function (array $patterns) {
        return collect($patterns)->contains(fn ($pattern) => request()->routeIs($pattern));
    };

    $matchesItemAccess = function (array $item, string $roleName = '', array $permissionNames = []) use ($sidebarUser, $roleNames) {
        $itemRoles = $item['roles'] ?? [];
        $itemPermissions = $item['permissions'] ?? [];
        $permissionRoles = $item['permission_roles'] ?? null;
        $departmentKeywords = $item['department_keywords'] ?? [];
        $excludedDepartmentKeywords = $item['except_department_keywords'] ?? [];
        $requiredRoles = $item['required_roles'] ?? [];
        $requiredAnyRoles = $item['required_any_roles'] ?? [];
        $departmentName = strtolower((string) $sidebarUser?->department?->name);

        if (! empty($requiredRoles) && count(array_intersect($requiredRoles, $roleNames)) !== count($requiredRoles)) {
            return false;
        }

        if (! empty($requiredAnyRoles) && count(array_intersect($requiredAnyRoles, $roleNames)) === 0) {
            return false;
        }

        if (! empty($departmentKeywords)) {
            $matchesDepartment = collect($departmentKeywords)
                ->contains(fn ($keyword) => str_contains($departmentName, strtolower($keyword)));

            if (! $matchesDepartment) {
                return false;
            }
        }

        if (! empty($excludedDepartmentKeywords)) {
            $matchesExcludedDepartment = collect($excludedDepartmentKeywords)
                ->contains(fn ($keyword) => str_contains($departmentName, strtolower($keyword)));

            if ($matchesExcludedDepartment) {
                return false;
            }
        }

        return ($roleName !== '' && in_array($roleName, $itemRoles, true))
            || (
                ! empty($itemPermissions)
                && ($permissionRoles === null || in_array($roleName, $permissionRoles, true))
                && count(array_intersect($itemPermissions, $permissionNames)) > 0
            );
    };

    $dashboardRoute = in_array('administrator', $roleNames, true) ? route('admin.index') : route('dashboard');

    $navigationItems = [
        ['label' => 'Patients', 'icon' => 'bi-people-fill', 'route' => 'record.patients.index', 'patterns' => ['record.patients.*'], 'roles' => ['record'], 'permissions' => ['patient.read'], 'permission_roles' => ['record']],
        ['label' => 'Register Patient', 'icon' => 'bi-person-plus', 'route' => 'record.patients.register.form', 'patterns' => ['record.patients.register.*'], 'roles' => ['record'], 'permissions' => ['patient.create'], 'permission_roles' => ['record']],
        ['label' => 'Patient Register', 'icon' => 'bi-file-earmark-spreadsheet', 'route' => 'record.patient-register.index', 'patterns' => ['record.patient-register.*'], 'roles' => ['record'], 'permissions' => ['patient.read'], 'permission_roles' => ['record']],

        ['label' => 'Patients', 'icon' => 'bi-clipboard-pulse', 'route' => 'nurse.patient.index', 'patterns' => ['nurse.patient.*', 'vital_signs.*'], 'roles' => ['nurse'], 'permissions' => ['vital_sign.read', 'observation.read', 'nursing_note.read'], 'permission_roles' => ['nurse']],
        ['label' => 'Admissions', 'icon' => 'bi-hospital', 'route' => 'nurse.admissions.index', 'patterns' => ['nurse.admissions.*'], 'roles' => ['nurse'], 'permissions' => ['nursing_note.read', 'admission.read'], 'permission_roles' => ['nurse']],
        ['label' => 'Vital Signs', 'icon' => 'bi-heart-pulse', 'route' => 'nurse.clinicals.vital-signs', 'patterns' => ['nurse.clinicals.vital-signs'], 'roles' => ['nurse'], 'permissions' => ['vital_sign.read'], 'permission_roles' => ['nurse']],
        ['label' => 'Observations', 'icon' => 'bi-eye', 'route' => 'nurse.clinicals.observations', 'patterns' => ['nurse.clinicals.observations'], 'roles' => ['nurse'], 'permissions' => ['observation.read'], 'permission_roles' => ['nurse']],
        ['label' => 'Investigations', 'icon' => 'bi-clipboard2-pulse', 'route' => 'nurse.clinicals.investigations', 'patterns' => ['nurse.clinicals.investigations'], 'roles' => ['nurse'], 'permissions' => ['investigation_request.read'], 'permission_roles' => ['nurse']],
        ['label' => 'Drug Chart', 'icon' => 'bi-capsule-pill', 'route' => 'nurse.clinicals.drug-charts', 'patterns' => ['nurse.clinicals.drug-charts'], 'roles' => ['nurse'], 'permissions' => ['nursing_note.read'], 'permission_roles' => ['nurse']],
        ['label' => 'Fluid Balance', 'icon' => 'bi-droplet', 'route' => 'nurse.clinicals.fluid-balances', 'patterns' => ['nurse.clinicals.fluid-balances'], 'roles' => ['nurse'], 'permissions' => ['nursing_note.read'], 'permission_roles' => ['nurse']],
        ['label' => 'Patients', 'icon' => 'bi-person-vcard', 'route' => 'doctor.patient.index', 'patterns' => ['doctor.patient.*'], 'roles' => ['doctor'], 'permissions' => ['prescription.read', 'admission.read', 'discharge.read'], 'permission_roles' => ['doctor']],
        ['label' => 'Vital Signs', 'icon' => 'bi-heart-pulse', 'route' => 'doctor.clinicals.vital-signs', 'patterns' => ['doctor.clinicals.vital-signs'], 'roles' => ['doctor'], 'permissions' => ['vital_sign.read'], 'permission_roles' => ['doctor']],
        ['label' => 'Observations', 'icon' => 'bi-eye', 'route' => 'doctor.clinicals.observations', 'patterns' => ['doctor.clinicals.observations'], 'roles' => ['doctor'], 'permissions' => ['observation.read'], 'permission_roles' => ['doctor']],
        ['label' => 'Investigations', 'icon' => 'bi-clipboard2-pulse', 'route' => 'doctor.clinicals.investigations', 'patterns' => ['doctor.clinicals.investigations'], 'roles' => ['doctor'], 'permissions' => ['investigation_request.read'], 'permission_roles' => ['doctor']],
        ['label' => 'Admissions', 'icon' => 'bi-hospital', 'route' => 'doctor.clinicals.admissions', 'patterns' => ['doctor.clinicals.admissions'], 'roles' => ['doctor'], 'permissions' => ['admission.read'], 'permission_roles' => ['doctor']],
        ['label' => 'Prescriptions', 'icon' => 'bi-prescription2', 'route' => 'doctor.clinicals.prescriptions', 'patterns' => ['doctor.clinicals.prescriptions'], 'roles' => ['doctor'], 'permissions' => ['prescription.read'], 'permission_roles' => ['doctor']],
        ['label' => 'Continuation Sheet', 'icon' => 'bi-pencil', 'route' => 'doctor.clinicals.continuations', 'patterns' => ['doctor.clinicals.continuations'], 'roles' => ['doctor'], 'permissions' => ['prescription.read'], 'permission_roles' => ['doctor']],
        ['label' => 'Drug Chart', 'icon' => 'bi-capsule-pill', 'route' => 'doctor.clinicals.drug-charts', 'patterns' => ['doctor.clinicals.drug-charts'], 'roles' => ['doctor'], 'permissions' => ['prescription.read'], 'permission_roles' => ['doctor']],
        ['label' => 'Fluid Balance', 'icon' => 'bi-droplet', 'route' => 'doctor.clinicals.fluid-balances', 'patterns' => ['doctor.clinicals.fluid-balances'], 'roles' => ['doctor'], 'permissions' => ['admission.read'], 'permission_roles' => ['doctor']],

        ['label' => 'ANC Patients', 'icon' => 'bi-people-fill', 'route' => 'midwife.patient.index', 'patterns' => ['midwife.patient.*'], 'roles' => ['midwife'], 'permissions' => ['antenatal_care.read']],
        ['label' => 'Antenatal Care', 'icon' => 'bi-heart-pulse-fill', 'route' => 'midwife.antenatal.index', 'patterns' => ['midwife.antenatal.*'], 'roles' => ['midwife'], 'permissions' => ['antenatal_care.read', 'antenatal_care.create']],
        ['label' => 'Labour', 'icon' => 'bi-activity', 'route' => 'midwife.labour.index', 'patterns' => ['midwife.labour.*'], 'roles' => ['midwife'], 'permissions' => ['labour.read', 'labour.create']],
        ['label' => 'Delivery', 'icon' => 'bi-hospital-fill', 'route' => 'midwife.delivery.index', 'patterns' => ['midwife.delivery.*'], 'roles' => ['midwife'], 'permissions' => ['delivery.read', 'delivery.create']],
        ['label' => 'Newborn', 'icon' => 'bi-bandaid-fill', 'route' => 'midwife.newborn.index', 'patterns' => ['midwife.newborn.*'], 'roles' => ['midwife'], 'permissions' => ['newborn.read', 'newborn.create']],
        ['label' => 'Newborn Exams', 'icon' => 'bi-clipboard2-pulse', 'route' => 'midwife.newborn-examination.index', 'patterns' => ['midwife.newborn-examination.*'], 'roles' => ['midwife'], 'permissions' => ['newborn_examination.read']],
        ['label' => 'Postnatal', 'icon' => 'bi-journal-medical', 'route' => 'midwife.postnatal-examination.index', 'patterns' => ['midwife.postnatal-examination.*'], 'roles' => ['midwife'], 'permissions' => ['postnatal_examination.read', 'postnatal_examination.create']],
        ['label' => 'Child Follow-up', 'icon' => 'bi-arrow-repeat', 'route' => 'midwife.child-follow-up.index', 'patterns' => ['midwife.child-follow-up.*'], 'roles' => ['midwife'], 'permissions' => ['child_follow_up.read', 'child_follow_up.create']],

        ['label' => 'Lab Requests', 'icon' => 'bi-vial', 'route' => 'lab.requests.index', 'patterns' => ['lab.requests.*'], 'roles' => ['lab_technician', 'lab_scientist'], 'permissions' => ['investigation_request.read', 'laboratory_request.read'], 'permission_roles' => ['lab_technician', 'lab_scientist']],
        ['label' => 'Lab Investigations', 'icon' => 'bi-list-check', 'route' => 'lab.investigations.index', 'patterns' => ['lab.investigations.*'], 'roles' => ['lab_technician', 'lab_scientist'], 'permissions' => ['investigation_result.read', 'laboratory_investigation.read'], 'permission_roles' => ['lab_technician', 'lab_scientist']],
        ['label' => 'Results Entry', 'icon' => 'bi-clipboard2-data', 'route' => 'lab.result', 'patterns' => ['lab.result'], 'roles' => ['lab_technician', 'lab_scientist'], 'permissions' => ['investigation_result.create', 'laboratory_result.create'], 'permission_roles' => ['lab_technician', 'lab_scientist']],

        ['label' => 'Radiology Requests', 'icon' => 'bi-vial', 'route' => 'radiology.requests.index', 'patterns' => ['radiology.requests.*'], 'roles' => ['radiologist', 'radiographer'], 'permissions' => ['radiology_request.read'], 'permission_roles' => ['radiologist', 'radiographer']],
        ['label' => 'Radiology Investigations', 'icon' => 'bi-list-check', 'route' => 'radiology.investigations.index', 'patterns' => ['radiology.investigations.*'], 'roles' => ['radiologist', 'radiographer'], 'permissions' => ['radiology_investigation.read'], 'permission_roles' => ['radiologist', 'radiographer']],

        ['label' => 'Users', 'icon' => 'bi-people', 'route' => 'department.users.index', 'patterns' => ['department.users.*'], 'roles' => ['head_of_department'], 'permissions' => ['user.read'], 'permission_roles' => ['head_of_department']],
        ['label' => 'Investigations', 'icon' => 'bi-clipboard2-data', 'route' => 'department.investigations.index', 'patterns' => ['department.investigations.*'], 'roles' => ['head_of_department'], 'permissions' => ['investigation.read'], 'permission_roles' => ['head_of_department'], 'department_keywords' => ['lab', 'radio']],
        ['label' => 'Consumables', 'icon' => 'bi-box-seam', 'route' => 'department.consumables.index', 'patterns' => ['department.consumables.*'], 'roles' => ['head_of_department'], 'permissions' => ['consumable.read'], 'except_department_keywords' => ['pharmacy']],
        ['label' => 'Consumable Stock', 'icon' => 'bi-boxes', 'route' => 'department.stocks.index', 'patterns' => ['department.stocks.*'], 'roles' => ['head_of_department'], 'permissions' => ['consumable_stock.read'], 'except_department_keywords' => ['pharmacy']],
        ['label' => 'Stock Usage', 'icon' => 'bi-clipboard-check', 'route' => 'department.stock-usage.index', 'patterns' => ['department.stock-usage.*'], 'roles' => ['head_of_department'], 'permissions' => ['consumable_stock.read'], 'except_department_keywords' => ['pharmacy']],

        ['label' => 'Transactions', 'icon' => 'bi-arrow-left-right', 'route' => 'pharmacy.transactions.index', 'patterns' => ['pharmacy.transactions.index', 'pharmacy.transactions.create'], 'roles' => ['pharmacist'], 'permissions' => ['pharmacy_sale.read']],
        ['label' => 'Prescriptions', 'icon' => 'bi-prescription2', 'route' => 'pharmacy.prescriptions.index', 'patterns' => ['pharmacy.prescriptions.*'], 'roles' => ['pharmacist'], 'permissions' => ['dispense.read', 'pharmacy_sale.read']],
        ['label' => 'Activities', 'icon' => 'bi-activity', 'route' => 'reports.my-activities.index', 'patterns' => ['reports.my-activities.*'], 'roles' => ['pharmacist'], 'permissions' => ['pharmacy_sale.read', 'dispense.read']],
        ['label' => 'Transaction Report', 'icon' => 'bi-graph-up', 'route' => 'pharmacy.transactions.report', 'patterns' => ['pharmacy.transactions.report'], 'roles' => ['pharmacist'], 'permissions' => ['pharmacy_sale.read']],
        ['label' => 'Bills', 'icon' => 'bi-receipt', 'route' => 'pharmacy.finance.bills', 'patterns' => ['pharmacy.finance.bills'], 'roles' => ['pharmacist', 'head_of_department'], 'permissions' => ['pharmacy_sale.read', 'department_report.read'], 'department_keywords' => ['pharmacy']],
        ['label' => 'Payments', 'icon' => 'bi-credit-card-2-front', 'route' => 'pharmacy.finance.payments', 'patterns' => ['pharmacy.finance.payments', 'pharmacy.finance.payments.receipt'], 'roles' => ['pharmacist', 'head_of_department'], 'permissions' => ['pharmacy_sale.read', 'department_report.read'], 'department_keywords' => ['pharmacy']],
        ['label' => 'Financial Report', 'icon' => 'bi-file-earmark-text', 'route' => 'pharmacy.finance.report', 'patterns' => ['pharmacy.finance.report'], 'roles' => ['pharmacist', 'head_of_department'], 'permissions' => ['pharmacy_sale.read', 'department_report.read'], 'department_keywords' => ['pharmacy']],
        ['label' => 'Medicines', 'icon' => 'bi-capsule', 'route' => 'pharmacy.medicines.index', 'patterns' => ['pharmacy.medicines.*'], 'roles' => ['pharmacist', 'head_of_department'], 'permissions' => ['medicine.read'], 'department_keywords' => ['pharmacy']],
        ['label' => 'Stock', 'icon' => 'bi-box-seam', 'route' => 'pharmacy.stocks.index', 'patterns' => ['pharmacy.stocks.*'], 'roles' => ['pharmacist', 'head_of_department'], 'permissions' => ['medicine_stock.read'], 'department_keywords' => ['pharmacy']],
        ['label' => 'Batches', 'icon' => 'bi-layers', 'route' => 'pharmacy.batches.index', 'patterns' => ['pharmacy.batches.*'], 'roles' => ['pharmacist', 'head_of_department'], 'permissions' => ['medicine_stock.read'], 'department_keywords' => ['pharmacy']],
        ['label' => 'Expiry Alerts', 'icon' => 'bi-exclamation-triangle', 'route' => 'pharmacy.expiries.index', 'patterns' => ['pharmacy.expiries.*'], 'roles' => ['pharmacist', 'head_of_department'], 'permissions' => ['expiry_alert.read'], 'department_keywords' => ['pharmacy']],

        ['label' => 'Bills', 'icon' => 'bi-receipt', 'route' => 'accountant.bills.index', 'patterns' => ['accountant.bills.*'], 'roles' => ['accountant'], 'permissions' => ['bill.read']],
        ['label' => 'Payments', 'icon' => 'bi-credit-card-2-front', 'route' => 'accountant.payments.index', 'patterns' => ['accountant.payments.*'], 'roles' => ['accountant'], 'permissions' => ['payment.read']],
        ['label' => 'Billing Report', 'icon' => 'bi-file-earmark-text', 'route' => 'reports.finance.index', 'patterns' => ['reports.finance.*'], 'roles' => ['administrator', 'accountant'], 'permissions' => ['bill.read', 'report.read']],
        ['label' => 'Payment Report', 'icon' => 'bi-bar-chart-line', 'route' => 'reports.payments.index', 'patterns' => ['reports.payments.*'], 'roles' => ['administrator', 'accountant'], 'permissions' => ['payment.read', 'report.read']],

        ['label' => 'Admin Bills', 'icon' => 'bi-receipt', 'route' => 'admin.bills.index', 'patterns' => ['admin.bills.*'], 'roles' => ['administrator'], 'permissions' => ['bill.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Admin Payments', 'icon' => 'bi-credit-card-2-front', 'route' => 'admin.payments.index', 'patterns' => ['admin.payments.*'], 'roles' => ['administrator'], 'permissions' => ['payment.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Patient Register', 'icon' => 'bi-file-earmark-spreadsheet', 'route' => 'admin.patient-register.index', 'patterns' => ['admin.patient-register.*'], 'roles' => ['administrator'], 'permissions' => ['patient.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Expenses', 'icon' => 'bi-cash-stack', 'route' => 'admin.expenses.index', 'patterns' => ['admin.expenses.*'], 'roles' => ['administrator'], 'permissions' => ['expense.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Revenues', 'icon' => 'bi-graph-up-arrow', 'route' => 'admin.revenues.index', 'patterns' => ['admin.revenues.*'], 'roles' => ['administrator'], 'permissions' => ['revenue.read'], 'permission_roles' => ['administrator']],

        ['label' => 'Departments', 'icon' => 'bi-buildings', 'route' => 'admin.departments.index', 'patterns' => ['admin.departments.*'], 'roles' => ['administrator'], 'permissions' => ['department.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Services', 'icon' => 'bi-gear-fill', 'route' => 'admin.services.index', 'patterns' => ['admin.services.*'], 'roles' => ['administrator'], 'permissions' => ['service.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Investigations', 'icon' => 'bi-clipboard2-data', 'route' => 'admin.investigations.index', 'patterns' => ['admin.investigations.*'], 'roles' => ['administrator'], 'permissions' => ['investigation.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Wards', 'icon' => 'bi-hospital', 'route' => 'admin.wards.index', 'patterns' => ['admin.wards.*'], 'roles' => ['administrator'], 'permissions' => ['ward.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Access Control', 'icon' => 'bi-shield-check', 'route' => 'admin.access-control', 'patterns' => ['admin.access-control'], 'roles' => ['administrator'], 'permissions' => ['role.read', 'permission.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Users', 'icon' => 'bi-people-fill', 'route' => 'admin.users.index', 'patterns' => ['admin.users.*'], 'roles' => ['administrator'], 'permissions' => ['user.read'], 'permission_roles' => ['administrator']],
        ['label' => 'Data Sync', 'icon' => 'bi-cloud-arrow-up', 'route' => 'admin.sync.index', 'patterns' => ['admin.sync.*'], 'roles' => ['administrator'], 'permissions' => ['sync.read'], 'permission_roles' => ['administrator']],
        ['label' => 'System Update', 'icon' => 'bi-arrow-repeat', 'route' => 'admin.system.update', 'patterns' => ['admin.system.update'], 'roles' => ['administrator'], 'permissions' => ['system.update'], 'permission_roles' => ['administrator']],
    ];

    $roleIcons = [
        'administrator' => 'bi-shield-check',
        'record' => 'bi-folder2-open',
        'nurse' => 'bi-heart-pulse',
        'doctor' => 'bi-person-vcard',
        'midwife' => 'bi-heart-pulse-fill',
        'lab_technician' => 'bi-vial',
        'lab_scientist' => 'bi-vial',
        'radiologist' => 'bi-radioactive',
        'radiographer' => 'bi-radioactive',
        'accountant' => 'bi-cash-stack',
        'pharmacist' => 'bi-capsule',
        'head_of_department' => 'bi-box-seam',
    ];

    $roleGroups = $roles->map(function ($role) use ($navigationItems, $matchesItemAccess, $isRouteActive, $roleIcons) {
        $rolePermissionNames = $role->permissions->pluck('name')->all();
        $items = collect($navigationItems)
            ->filter(fn ($item) => $matchesItemAccess($item, $role->name, $rolePermissionNames))
            ->unique('route')
            ->values();

        return [
            'id' => 'role-' . $role->id,
            'roleName' => $role->name,
            'label' => $role->display_name ?: str($role->name)->headline()->toString(),
            'icon' => $roleIcons[$role->name] ?? 'bi-grid',
            'items' => $items,
            'open' => $items->contains(fn ($item) => $isRouteActive($item['patterns'])),
        ];
    })->filter(fn ($group) => $group['items']->isNotEmpty())->values();

    $temporaryItems = collect($navigationItems)
        ->filter(fn ($item) => ! empty($item['permissions']) && count(array_intersect($item['permissions'], $temporaryPermissionNames)) > 0)
        ->filter(function ($item) use ($sidebarUser, $roleNames) {
            $requiredRoles = $item['required_roles'] ?? [];
            $requiredAnyRoles = $item['required_any_roles'] ?? [];

            if (! empty($requiredRoles) && count(array_intersect($requiredRoles, $roleNames)) !== count($requiredRoles)) {
                return false;
            }

            if (! empty($requiredAnyRoles) && count(array_intersect($requiredAnyRoles, $roleNames)) === 0) {
                return false;
            }

            $departmentKeywords = $item['department_keywords'] ?? [];
            $excludedDepartmentKeywords = $item['except_department_keywords'] ?? [];

            if (empty($departmentKeywords)) {
                $matchesDepartment = true;
            } else {
                $departmentName = strtolower((string) $sidebarUser?->department?->name);
                $matchesDepartment = collect($departmentKeywords)
                    ->contains(fn ($keyword) => str_contains($departmentName, strtolower($keyword)));
            }

            $departmentName = strtolower((string) $sidebarUser?->department?->name);

            if (! $matchesDepartment) {
                return false;
            }

            if (empty($excludedDepartmentKeywords)) {
                return true;
            }

            return ! collect($excludedDepartmentKeywords)
                ->contains(fn ($keyword) => str_contains($departmentName, strtolower($keyword)));
        })
        ->unique('route')
        ->values();

    $temporaryOpen = $temporaryItems->contains(fn ($item) => $isRouteActive($item['patterns']));
    $activitiesOpen = request()->routeIs('reports.activities.*');
@endphp

<aside class="admin-sidebar">
    <div class="admin-sidebar-header">
        <div class="admin-sidebar-title">
            <div class="fw-bold text-success">Menu</div>
            <small class="text-muted">Roles and permissions</small>
        </div>
        <button type="button"
                class="btn btn-outline-success sidebar-toggle-btn"
                id="sidebarToggle"
                aria-label="Toggle sidebar"
                aria-expanded="true"
                title="Toggle sidebar">
            <i class="bi bi-layout-sidebar-inset"></i>
        </button>
    </div>

    <nav class="admin-sidebar-nav">
        <a class="admin-sidebar-link {{ request()->routeIs('dashboard') || request()->routeIs('admin.index') ? 'active' : '' }}" href="{{ $dashboardRoute }}">
            <i class="bi bi-speedometer2"></i>
            Dashboard
        </a>

        @foreach($roleGroups as $group)
            <button class="admin-sidebar-toggle {{ $group['open'] ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#{{ $group['id'] }}SidebarMenu" aria-expanded="{{ $group['open'] ? 'true' : 'false' }}" aria-controls="{{ $group['id'] }}SidebarMenu">
                <span><i class="bi {{ $group['icon'] }}"></i> {{ $group['label'] }}</span>
                <i class="bi bi-chevron-down admin-sidebar-chevron"></i>
            </button>
            <div class="collapse {{ $group['open'] ? 'show' : '' }}" id="{{ $group['id'] }}SidebarMenu">
                <div class="admin-sidebar-submenu">
                    @foreach($group['items'] as $item)
                        <a class="admin-sidebar-link {{ $isRouteActive($item['patterns']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                            <i class="bi {{ $item['icon'] }}"></i>
                            {{ $item['label'] }}
                        </a>
                    @endforeach

                    @if($group['roleName'] === 'administrator')
                        <button class="admin-sidebar-toggle {{ $activitiesOpen ? '' : 'collapsed' }} mt-1" type="button" data-bs-toggle="collapse" data-bs-target="#activitiesSidebarMenu" aria-expanded="{{ $activitiesOpen ? 'true' : 'false' }}" aria-controls="activitiesSidebarMenu">
                            <span><i class="bi bi-activity"></i> Activities</span>
                            <i class="bi bi-chevron-down admin-sidebar-chevron"></i>
                        </button>
                        <div class="collapse {{ $activitiesOpen ? 'show' : '' }}" id="activitiesSidebarMenu">
                            <div class="admin-sidebar-submenu admin-sidebar-scroll">
                                @foreach(\App\Models\Department::orderBy('name')->get(['id', 'name']) as $activityDepartment)
                                    <a class="admin-sidebar-link {{ request()->routeIs('reports.activities.*') && request()->route('department')?->id === $activityDepartment->id ? 'active' : '' }}" href="{{ route('reports.activities.show', $activityDepartment) }}">
                                        <i class="bi bi-building"></i>
                                        {{ $activityDepartment->name }}
                                    </a>
                                @endforeach
                            </div>
                        </div>
                    @endif
                </div>
            </div>
        @endforeach

        @if($temporaryItems->isNotEmpty())
            <button class="admin-sidebar-toggle {{ $temporaryOpen ? '' : 'collapsed' }}" type="button" data-bs-toggle="collapse" data-bs-target="#temporaryAccessSidebarMenu" aria-expanded="{{ $temporaryOpen ? 'true' : 'false' }}" aria-controls="temporaryAccessSidebarMenu">
                <span><i class="bi bi-clock-history"></i> Temporary Access</span>
                <i class="bi bi-chevron-down admin-sidebar-chevron"></i>
            </button>
            <div class="collapse {{ $temporaryOpen ? 'show' : '' }}" id="temporaryAccessSidebarMenu">
                <div class="admin-sidebar-submenu">
                    @foreach($temporaryItems as $item)
                        <a class="admin-sidebar-link {{ $isRouteActive($item['patterns']) ? 'active' : '' }}" href="{{ route($item['route']) }}">
                            <i class="bi {{ $item['icon'] }}"></i>
                            {{ $item['label'] }}
                        </a>
                    @endforeach
                </div>
            </div>
        @endif
    </nav>
</aside>

@once
    <script>
        document.addEventListener('DOMContentLoaded', function () {
            if (window.__fayhosSidebarToggleBound) {
                return;
            }

            window.__fayhosSidebarToggleBound = true;

            const toggle = document.getElementById('sidebarToggle');
            const icon = toggle ? toggle.querySelector('i') : null;
            const mobileQuery = window.matchMedia('(max-width: 991.98px)');

            if (!toggle || !icon) {
                return;
            }

            const applySidebarState = function (collapsed) {
                document.body.classList.toggle('sidebar-collapsed', collapsed);
                toggle.setAttribute('aria-expanded', collapsed ? 'false' : 'true');
                icon.classList.toggle('bi-layout-sidebar-inset', !collapsed);
                icon.classList.toggle('bi-list', collapsed);
            };

            const preferredCollapsedState = function () {
                const storageKey = mobileQuery.matches ? 'sidebar-collapsed-mobile' : 'sidebar-collapsed';
                const stored = localStorage.getItem(storageKey);

                if (stored !== null) {
                    return stored === 'true';
                }

                return mobileQuery.matches;
            };

            applySidebarState(preferredCollapsedState());

            toggle.addEventListener('click', function () {
                const collapsed = !document.body.classList.contains('sidebar-collapsed');
                const storageKey = mobileQuery.matches ? 'sidebar-collapsed-mobile' : 'sidebar-collapsed';
                localStorage.setItem(storageKey, collapsed ? 'true' : 'false');
                applySidebarState(collapsed);
            });

            document.querySelectorAll('.admin-sidebar-link').forEach(function (link) {
                link.addEventListener('click', function () {
                    if (mobileQuery.matches) {
                        localStorage.setItem('sidebar-collapsed-mobile', 'true');
                        applySidebarState(true);
                    }
                });
            });

            mobileQuery.addEventListener('change', function () {
                applySidebarState(preferredCollapsedState());
            });
        });
    </script>
@endonce
