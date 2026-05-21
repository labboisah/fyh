<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} | @yield('title')</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
        <!-- Fonts -->
        <link rel="stylesheet" href="{{ asset('vendor/fontawesome/css/all.min.css') }}">
        <!-- Bootstrap CSS (local) -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <!-- Icons -->
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="{{ asset('vendor/datatables/css/dataTables.bootstrap5.min.css') }}">
        <!-- DataTables Buttons CSS -->
        <link rel="stylesheet" href="{{ asset('vendor/datatables/css/buttons.bootstrap5.min.css') }}">
        <!-- Scripts -->
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}" defer></script>
        
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
        <style>
            :root {
                --primary-green: #27AE60;
                --primary-orange: #FF8C42;
                --light-green: #E8F8F5;
                --light-orange: #FFF5E6;
                --dark-green: #1a4d2e;
                --secondary-green: #229954;
            }

            * {
                font-family: 'Figtree', 'Poppins', sans-serif;
            }

            body {
                background: linear-gradient(135deg, var(--light-green) 0%, #f8f9fa 100%);
                overflow-x: hidden;
            }

            /* ========== NAVBAR ========== */
            .hospital-navbar {
                background: #fff;
                border-bottom: 2px solid var(--primary-green);
                box-shadow: 0 2px 8px rgba(39, 174, 96, 0.1);
                padding: 0.8rem 0;
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 1.3rem;
            }

            .brand-accent {
                color: var(--primary-green);
                font-weight: 700;
                transition: all 0.3s ease;
            }

            .hospital-navbar .nav-link {
                color: #333 !important;
                font-weight: 500;
                transition: all 0.3s ease;
                margin: 0 0.25rem;
            }

            .hospital-navbar .nav-link:hover,
            .hospital-navbar .nav-link.active {
                color: var(--primary-green) !important;
                text-shadow: 0 0 8px rgba(39, 174, 96, 0.2);
            }

            .navbar-toggler {
                border-color: var(--primary-green);
            }

            /* ========== BUTTONS ========== */
            .btn-accent {
                background: linear-gradient(90deg, var(--primary-green), var(--primary-orange));
                color: #fff !important;
                border: none;
                font-weight: 600;
                padding: 0.6rem 1.5rem;
                transition: all 0.3s ease;
                box-shadow: 0 4px 12px rgba(39, 174, 96, 0.2);
            }

            .btn-accent:hover {
                transform: translateY(-2px);
                box-shadow: 0 8px 20px rgba(39, 174, 96, 0.3);
            }

            .btn-outline-success {
                color: var(--primary-green);
                border-color: var(--primary-green);
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-outline-success:hover {
                background-color: var(--primary-green);
                border-color: var(--primary-green);
                box-shadow: 0 4px 12px rgba(39, 174, 96, 0.2);
                transform: translateY(-2px);
            }

            .btn-success {
                background-color: var(--primary-green);
                border-color: var(--primary-green);
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-success:hover {
                background-color: var(--secondary-green);
                border-color: var(--secondary-green);
                transform: translateY(-2px);
            }

            .btn-primary {
                background-color: #667eea;
                border-color: #667eea;
                font-weight: 600;
                transition: all 0.3s ease;
            }

            .btn-primary:hover {
                background-color: #5568d3;
                border-color: #5568d3;
                transform: translateY(-2px);
            }

            /* ========== CARDS ========== */
            .card {
                border: none;
                transition: all 0.3s ease;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
                border-radius: 12px;
            }

            .card:hover {
                transform: translateY(-4px);
                box-shadow: 0 8px 20px rgba(39, 174, 96, 0.1);
                border: 1px solid var(--primary-green);
            }

            .card-header {
                background: linear-gradient(90deg, rgba(39, 174, 96, 0.1) 0%, rgba(255, 140, 66, 0.05) 100%);
                border: none;
                border-bottom: 2px solid var(--primary-green);
                font-weight: 600;
                color: var(--primary-green);
            }

            /* ========== BADGES ========== */
            .badge-green {
                background-color: var(--primary-green);
                color: white;
            }

            .badge-orange {
                background-color: var(--primary-orange);
                color: white;
            }

            .badge-success {
                background-color: var(--primary-green) !important;
            }

            /* ========== BREADCRUMB ========== */
            .breadcrumb {
                background: transparent;
                padding: 0;
                font-size: 0.95rem;
            }

            .breadcrumb-item {
                color: #666;
            }

            .breadcrumb-item.active {
                color: var(--primary-green);
                font-weight: 600;
            }

            .breadcrumb-item a {
                color: var(--primary-green);
                text-decoration: none;
                transition: all 0.3s ease;
                font-weight: 500;
            }

            .breadcrumb-item a:hover {
                color: var(--primary-orange);
                text-decoration: underline;
            }

            /* ========== FOOTER ========== */
            footer.site-footer {
                background: linear-gradient(90deg, var(--dark-green) 0%, var(--primary-green) 100%);
                color: #fff;
                border-top: 3px solid var(--primary-orange);
                margin-top: auto;
            }

            footer.site-footer strong {
                color: #fff;
            }

            footer.site-footer .text-white-50 {
                opacity: 0.85;
            }

            /* ========== HEADER ========== */
            header {
                background: linear-gradient(135deg, #f8f9fa 0%, var(--light-green) 100%);
            }

            header h1, header h2, header h3 {
                color: var(--primary-green);
                font-weight: 700;
            }

            /* ========== TABLES ========== */
            .table thead th {
                background: linear-gradient(90deg, rgba(39, 174, 96, 0.1) 0%, rgba(255, 140, 66, 0.05) 100%);
                color: var(--primary-green);
                border-color: var(--primary-green);
                font-weight: 600;
                padding: 1rem 0.75rem;
            }

            .table tbody tr {
                transition: all 0.2s ease;
            }

            .table tbody tr:hover {
                background-color: var(--light-green);
            }

            .table-hover tbody tr:hover {
                background-color: #f1f9f6;
            }

            /* ========== FORMS ========== */
            .form-control {
                border-color: #ddd;
                transition: all 0.3s ease;
                border-radius: 8px;
            }

            .form-control:focus {
                border-color: var(--primary-green);
                box-shadow: 0 0 0 0.2rem rgba(39, 174, 96, 0.15);
            }

            .form-label {
                color: #333;
                font-weight: 600;
                margin-bottom: 0.5rem;
            }

            .form-label .text-danger {
                color: #dc3545;
                margin-left: 0.25rem;
            }

            /* ========== ALERTS ========== */
            .alert {
                border: none;
                border-left: 4px solid;
                border-radius: 8px;
                box-shadow: 0 2px 8px rgba(0, 0, 0, 0.06);
            }

            .alert-success {
                background-color: rgba(39, 174, 96, 0.1);
                border-left-color: var(--primary-green);
                color: #1a4d2e;
            }

            .alert-warning {
                background-color: rgba(255, 140, 66, 0.1);
                border-left-color: var(--primary-orange);
                color: #5a2c1f;
            }

            .alert-danger {
                background-color: rgba(220, 53, 69, 0.1);
                border-left-color: #dc3545;
                color: #5c0011;
            }

            .alert-info {
                background-color: rgba(102, 126, 234, 0.1);
                border-left-color: #667eea;
                color: #1a2e66;
            }

            /* ========== TRANSITIONS & ANIMATIONS ========== */
            a {
                transition: all 0.3s ease;
            }

            a:hover {
                text-decoration: none;
            }

            /* ========== UTILITY ========== */
            .text-success {
                color: var(--primary-green) !important;
            }

            .text-warning {
                color: var(--primary-orange) !important;
            }

            .bg-success-light {
                background-color: var(--light-green);
            }

            .bg-warning-light {
                background-color: var(--light-orange);
            }

            .border-success {
                border-color: var(--primary-green) !important;
            }

            .border-warning {
                border-color: var(--primary-orange) !important;
            }

            /* ========== RESPONSIVE ========== */
            @media (max-width: 768px) {
                .brand-accent {
                    font-size: 1rem;
                }

                .hospital-navbar .nav-link {
                    margin: 0.5rem 0;
                }

                header {
                    padding: 1rem 0 !important;
                }
            }
        </style>
        <style>
    .btn-outline-primary, .btn-outline-info, .btn-outline-success, .btn-outline-danger {
        transition: all 0.3s ease;
    }

    .btn-outline-primary:hover, .btn-outline-info:hover, .btn-outline-success:hover, .btn-outline-danger:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 16px rgba(0, 0, 0, 0.1) !important;
    }

    .table tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .page-loader {
        position: fixed;
        inset: 0;
        z-index: 2000;
        display: flex;
        align-items: center;
        justify-content: center;
        background: rgba(255, 255, 255, 0.95);
        transition: opacity 0.25s ease, visibility 0.25s ease;
        opacity: 1;
        visibility: visible;
    }

    .page-loader.hidden {
        opacity: 0;
        visibility: hidden;
        pointer-events: none;
    }

    .page-loader .loader-box {
        text-align: center;
        padding: 1.5rem 2rem;
        border-radius: 1rem;
        background: #fff;
        box-shadow: 0 18px 60px rgba(0, 0, 0, 0.12);
    }

    .page-loader .loader-box .loader-logo {
        width: 80px;
        max-width: 100%;
        margin-bottom: 1rem;
    }

    .page-loader .loader-box .spinner-border {
        width: 3rem;
        height: 3rem;
    }

    .toast-spinner {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }
</style>
        @yield('styles')
    </head>
    <body class="font-sans antialiased">
        <div id="page-loader" class="page-loader">
            <div class="loader-box">
                
                <div class="spinner-border text-success" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
                <div class="mt-3">Loading page...</div>
            </div>
        </div>
        <div class="min-h-screen bg-light">

            <nav class="navbar navbar-expand-lg hospital-navbar shadow-sm">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                        <x-hospital-logo class="me-2" />
                        <div class="ms-2">
                            <div class="fw-bold brand-accent" ><img src="{{asset('images/logo.png')}}" alt="logo" width="60"> <span style="transform: scaleY(2);">FAYHOS</span> </div>
                        </div>
                    </a>

                    <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#mainNav" aria-controls="#mainNav" aria-expanded="false" aria-label="Toggle navigation">
                        <span class="navbar-toggler-icon"></span>
                    </button>

                    <div class="collapse navbar-collapse" id="mainNav">
                        <ul class="navbar-nav ms-auto mb-2 mb-lg-0 align-items-lg-center">
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('dashboard') }}"><i class="bi bi-house-fill me-2 text-success"></i>Home</a>
                            </li>

                            @if(Auth::user()->hasRole('record'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('record.patients.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Patients</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('nurse'))

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Patients</a>
                            </li>
                            @endif



                            @if(Auth::user()->hasRole('doctor'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('doctor.patients.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Patients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('doctor.patients.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Admission</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('doctor.patients.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Discharge</a>
                            </li>
                            
                            @endif

                            @if(Auth::user()->hasRole('midwife'))
                            
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('midwife.patient.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>ANC Patients</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('midwife'))
                            <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-lightning-fill me-2 text-success"></i>
                                        Quick Actions
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.antenatal.index') }}">

                                                <i class="bi bi-heart-pulse-fill me-2 text-primary"></i>
                                                Antenatal Care

                                            </a>
                                        </li>

                                        <!-- Labour -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.labour.index') }}">

                                                <i class="bi bi-activity me-2 text-warning"></i>
                                                Labour Management

                                            </a>
                                        </li>

                                        <!-- Delivery -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.delivery.index') }}">

                                                <i class="bi bi-hospital-fill me-2 text-danger"></i>
                                                Delivery Records

                                            </a>
                                        </li>

                                        <!-- Newborn -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.newborn.index') }}">

                                                <i class="bi bi-bandaid-fill me-2 text-info"></i>
                                                Newborn Records

                                            </a>
                                        </li>

                                        <!-- Newborn Examinations -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.newborn-examination.index') }}">

                                                <i class="bi bi-clipboard2-pulse-fill me-2 text-success"></i>
                                                Newborn Examinations

                                            </a>
                                        </li>

                                        <!-- Postnatal Examination -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.postnatal-examination.index') }}">

                                                <i class="bi bi-journal-medical me-2 text-secondary"></i>
                                                Postnatal Examination

                                            </a>
                                        </li>

                                        <!-- Child Follow Up -->
                                        <li>
                                            <a class="dropdown-item"
                                            href="{{ route('midwife.child-follow-up.index') }}">

                                                <i class="bi bi-arrow-repeat me-2 text-dark"></i>
                                                Child Follow-up

                                            </a>
                                        </li>
                                        
                                    </ul>
                                </li>
                            

                            @endif

                            @if(Auth::user()->hasRole('lab_technician') || Auth::user()->hasRole('lab_scientist'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.requests.index') }}"><i class="bi bi-vial me-2 text-success"></i>{{auth()->user()->department->requestStats()['pending']}} Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.investigations.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Investigations</a>
                            </li>
                            @endif
                            @if(Auth::user()->hasRole('head_of_department'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.consumables.index') }}"><i class="bi bi-box-seam me-2 text-success"></i>Consumables</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.stocks.index') }}"><i class="bi bi-boxes me-2 text-success"></i>Stock</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('department.expenses.index') }}"><i class="bi bi-cash-stack me-2 text-success"></i>Expense</a>
                            </li>
                            
                            @endif

                            @if(Auth::user()->hasRole('radiologist'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('radiograph.requests.index') }}"><i class="bi bi-vial me-2 text-success"></i>{{auth()->user()->department->requestStats()['pending']}} Requests</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('radiograph.investigations.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Investigations</a>
                            </li>
                            
                            @endif

                            @if(Auth::user()->hasRole('accountant'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('accountant.bills.index') }}"><i class="fa-solid fa-naira-sign me-2 text-success"></i>  Billing</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('accountant.payments.index') }}"><i class="bi bi-receipt me-2 text-success"></i>Payments</a>
                            </li>
                            
                            @endif

                            @if(Auth::user()->hasRole('administrator'))
                                
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.index') }}"><i class="bi bi-shield-check me-2 text-success"></i>Access Control</a>
                                </li>
                                <!-- Add more admin-specific links here -->
                                 <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Users</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.services.index') }}"><i class="bi bi-gear-fill me-2 text-success"></i>Services</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.investigations.index') }}"><i class="bi bi-clipboard2-data me-2 text-success"></i>Investigation</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.departments.index') }}"><i class="bi bi-buildings me-2 text-success"></i>Departments</a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.wards.index') }}"><i class="bi bi-buildings me-2 text-success"></i>Wards</a>
                                </li>
                            @endif

                            @if(Auth::user()->hasRole('pharmacist'))
                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-file-medical me-1"></i>
                                        Prescriptions
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('pharmacy.transactions.index')}}">
                                        <i class="bi bi-file-medical me-1"></i>
                                        Transactions
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('pharmacy.medicines.index')}}">
                                        <i class="bi bi-capsule me-1"></i>
                                        Medicine
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('pharmacy.stocks.index')}}">
                                        <i class="bi bi-box-seam me-1"></i>
                                        Stock
                                    </a>
                                </li>

                                <li class="nav-item">
                                    <a class="nav-link" href="{{route('pharmacy.expiries.index')}}">
                                        <i class="bi bi-exclamation-triangle me-1"></i>
                                        Expiry Alerts
                                    </a>
                                </li>

                                
                            @endif

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('reports.index') }}"><i class="bi bi-bar-chart me-2 text-success"></i>Report</a>
                            </li>

                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle me-2 text-secondary"></i>
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
                                        @if(Auth::user()->hasRole('administrator'))
                                            <li><hr class="dropdown-divider"></li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.system.update') }}">Check System Update</a>
                                            </li>
                                            <li>
                                                <a class="dropdown-item" href="{{ route('admin.sync.dashboard') }}">Data Synchronization Dashboard</a>
                                            </li>
                                        @endif
                                        <li><hr class="dropdown-divider"></li>
                                        <li>
                                            <form method="POST" action="{{ route('logout') }}">
                                                @csrf
                                                <button type="submit" class="dropdown-item">Log Out</button>
                                            </form>
                                        </li>
                                    </ul>
                                </li>
                            @else
                                <li class="nav-item">
                                    <a class="btn btn-outline-success ms-2 d-flex align-items-center" href="{{ route('login') }}"><i class="bi bi-box-arrow-in-right me-2"></i>Login</a>
                                </li>
                            @endauth
                        </ul>
                    </div>
                </div>
            </nav>

            <!-- Page Heading -->
           
                <header class="bg-white">
                    <div class="container py-3" style="border-bottom: 1px solid rgba(0,0,0,0.05);">
                        @if (class_exists(\Diglactic\Breadcrumbs\Breadcrumbs::class))
                            @php
                                try {
                                    $routeName = \Illuminate\Support\Facades\Route::currentRouteName();
                                    if ($routeName) {
                                        // Get the bound route parameters (already resolved models)
                                        $routeParams = \Illuminate\Support\Facades\Route::current()->parameters();
                                        $parameters = array_values($routeParams);
                                        $breadcrumbsHtml = \Diglactic\Breadcrumbs\Breadcrumbs::render($routeName, ...$parameters);
                                        // Add Bootstrap breadcrumb classes if breadcrumbs exist
                                        echo str_replace(['<ul>', '</ul>', '<li>', '</li>'], 
                                                       ['<ol class="breadcrumb mb-0">', '</ol>', '<li class="breadcrumb-item">', '</li>'], 
                                                       $breadcrumbsHtml);
                                    }
                                } catch (\Exception $e) {
                                    // no breadcrumbs defined for this route
                                }
                            @endphp
                        @endif
                    </div>
                    <div class="container py-4">
                        @yield('header')
                    </div>
                </header>
           

            <!-- Page Content -->
            <main class="py-4">
                <div class="container">
                    @if(session('success'))
                    <div id="toast"
                        class="fixed top-5 right-5 bg-accent text-white px-6 py-3 rounded-xl shadow-lg">
                        {{ session('success') }}
                    </div>

                    <script>
                        setTimeout(() => {
                            document.getElementById("toast").remove();
                        }, 3000);
                    </script>
                    @endif

                    @yield('content')
                </div>

                
            </main>

            

            
        </div>

        <!-- jQuery (required by DataTables) ante-->
        <script src="{{ asset('vendor/jquery/jquery-3.7.1.min.js') }}"></script>
        <!-- DataTables JS -->
        <script src="{{ asset('vendor/datatables/js/jquery.dataTables.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables/js/dataTables.bootstrap5.min.js') }}"></script>
        <!-- DataTables Buttons -->
        <script src="{{ asset('vendor/datatables/js/dataTables.buttons.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables/js/buttons.bootstrap5.min.js') }}"></script>
        <!-- Export Libraries -->
        <script src="{{ asset('vendor/jszip/jszip.min.js') }}"></script>
        <script src="{{ asset('vendor/pdfmake/pdfmake.min.js') }}"></script>
        <script src="{{ asset('vendor/pdfmake/vfs_fonts.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables/js/buttons.html5.min.js') }}"></script>
        <script src="{{ asset('vendor/datatables/js/buttons.print.min.js') }}"></script>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
                if (window.jQuery && $.fn.DataTable) {
                    $('table.datatable').each(function () {
                        if (!$.fn.DataTable.isDataTable(this)) {
                            // Get table title from card header or use generic name
                            var $card = $(this).closest('.card');
                            var tableTitle = $card.length > 0 
                                ? $card.find('.card-header h5').text().replace(/<[^>]*>/g, '').trim() 
                                : 'Table_Export_' + new Date().getTime();

                            var dt = $(this).DataTable({
                                responsive: true,
                                pageLength: 25,
                                lengthMenu: [10, 25, 50, 100],
                                autoWidth: false,
                                language: {
                                    search: "_INPUT_",
                                    searchPlaceholder: "Search table..."
                                },
                                columnDefs: [
                                    { orderable: false, targets: 'no-sort' }
                                ],
                                dom: 'Bfrtip',
                                buttons: [
                                    {
                                        extend: 'copy',
                                        className: 'btn btn-sm btn-outline-success me-2',
                                        text: '<i class="bi bi-clipboard me-1"></i>Copy',
                                        title: tableTitle,
                                        exportOptions: {
                                            columns: ':visible:not(.no-export)'
                                        }
                                    },
                                    {
                                        extend: 'csv',
                                        className: 'btn btn-sm btn-outline-warning me-2',
                                        text: '<i class="bi bi-filetype-csv me-1"></i>CSV',
                                        title: tableTitle,
                                        filename: tableTitle + '_' + new Date().toISOString().split('T')[0],
                                        exportOptions: {
                                            columns: ':visible:not(.no-export)'
                                        }
                                    },
                                    {
                                        extend: 'excel',
                                        className: 'btn btn-sm btn-outline-success me-2',
                                        text: '<i class="bi bi-file-earmark-spreadsheet me-1"></i>Excel',
                                        title: tableTitle,
                                        filename: tableTitle + '_' + new Date().toISOString().split('T')[0],
                                        exportOptions: {
                                            columns: ':visible:not(.no-export)'
                                        }
                                    },
                                    {
                                        extend: 'pdf',
                                        className: 'btn btn-sm btn-outline-warning me-2',
                                        text: '<i class="bi bi-file-earmark-pdf me-1"></i>PDF',
                                        title: tableTitle,
                                        filename: tableTitle + '_' + new Date().toISOString().split('T')[0],
                                        exportOptions: {
                                            columns: ':visible:not(.no-export)'
                                        },
                                        orientation: 'landscape',
                                        pageSize: 'A4'
                                    },
                                    {
                                        extend: 'print',
                                        className: 'btn btn-sm btn-outline-success',
                                        text: '<i class="bi bi-printer me-1"></i>Print',
                                        title: tableTitle,
                                        exportOptions: {
                                            columns: ':visible:not(.no-export)'
                                        }
                                    }
                                ]
                            });

                            // Style the button container
                            var buttonContainer = dt.buttons().container();
                            $(buttonContainer).addClass('d-flex gap-2 mb-3 flex-wrap');
                            
                            // Insert buttons before table
                            $(this).closest('.table-responsive').before(buttonContainer);

                            // Hide server-rendered pagination (if any) to avoid duplicate controls
                            try {
                                $(this).closest('.card').find('.card-footer').hide();
                            } catch (e) {
                                // ignore
                            }
                        }
                    });
                }
            });
        </script>

        <script>
            (function () {
                const pageLoader = document.getElementById('page-loader');
                let loaderHideTimeout;

                const showPageLoader = () => {
                    if (pageLoader) {
                        pageLoader.classList.remove('hidden');
                    }
                    // Clear any pending hide timeout
                    if (loaderHideTimeout) {
                        clearTimeout(loaderHideTimeout);
                    }
                };

                const hidePageLoader = () => {
                    if (pageLoader) {
                        pageLoader.classList.add('hidden');
                    }
                    if (loaderHideTimeout) {
                        clearTimeout(loaderHideTimeout);
                    }
                };

                const disableElement = (element) => {
                    if (!element || element.dataset.loading === 'true') {
                        return;
                    }

                    element.dataset.loading = 'true';

                    if (element.tagName === 'A') {
                        element.style.pointerEvents = 'none';
                        element.setAttribute('aria-disabled', 'true');
                        element.classList.add('disabled');
                        return;
                    }

                    element.disabled = true;
                };

                const setButtonLoading = (button, label) => {
                    if (!button || button.dataset.loading === 'true') {
                        return;
                    }

                    disableElement(button);
                    button.dataset.originalHtml = button.innerHTML;
                    button.innerHTML = `
                        <span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>
                        ${label}
                    `;

                    setTimeout(() => {
                        if (button.dataset.originalHtml) {
                            button.innerHTML = button.dataset.originalHtml;
                            button.disabled = false;
                            delete button.dataset.loading;
                            delete button.dataset.originalHtml;
                        }
                    }, 5000);
                };

                const isActionableLink = (link) => {
                    if (!link || !link.href) {
                        return false;
                    }

                    const href = link.getAttribute('href');
                    if (!href || href.startsWith('#') || href.startsWith('javascript:') || href === 'mailto:' || href === 'tel:') {
                        return false;
                    }

                    if (link.target && link.target !== '_self') {
                        return false;
                    }

                    if (link.hasAttribute('download')) {
                        return false;
                    }

                    return link.hostname === window.location.hostname;
                };

                document.addEventListener('DOMContentLoaded', function () {
                    hidePageLoader();

                    document.querySelectorAll('a').forEach(link => {
                        if (!isActionableLink(link)) {
                            return;
                        }

                        link.addEventListener('click', function () {
                            disableElement(link);
                            showPageLoader();

                            if (typeof NProgress !== 'undefined') {
                                NProgress.start();
                            }
                        });
                    });

                    document.querySelectorAll('button, input[type="submit"], input[type="button"], input[type="reset"]').forEach(control => {
                        control.addEventListener('click', function (event) {
                            const button = event.currentTarget;

                            if (button.matches('[data-bs-toggle], [data-toggle], .dropdown-toggle')) {
                                return;
                            }

                            if (button.closest('form') && (button.type === 'submit' || button.getAttribute('type') === 'submit')) {
                                return;
                            }

                            setButtonLoading(button, button.dataset.loadingText || 'Loading...');
                        });
                    });

                    document.querySelectorAll('form').forEach(form => {
                        form.addEventListener('submit', function () {
                            const button = form.querySelector("button[type='submit'], input[type='submit']");
                            if (button) {
                                setButtonLoading(button, button.dataset.loadingText || 'Processing...');
                            }
                        });
                    });
                });

                // Handle both normal load and cache restoration (back button)
                window.addEventListener('load', function () {
                    hidePageLoader();
                    if (typeof NProgress !== 'undefined') {
                        NProgress.done();
                    }
                });

                // Handle pageshow event (fires on load and cache restoration)
                window.addEventListener('pageshow', function (event) {
                    // Hide loader immediately when page is shown from cache or normal load
                    hidePageLoader();
                    if (typeof NProgress !== 'undefined') {
                        NProgress.done();
                    }
                    // If this is a cached page restoration, ensure loader is hidden
                    if (event.persisted) {
                        loaderHideTimeout = setTimeout(hidePageLoader, 50);
                    }
                });

                // Handle pagehide event to prepare for navigation
                window.addEventListener('pagehide', function () {
                    // Clear any pending operations
                    if (loaderHideTimeout) {
                        clearTimeout(loaderHideTimeout);
                    }
                });

                // Safety timeout: hide loader if still visible after 3 seconds
                loaderHideTimeout = setTimeout(hidePageLoader, 3000);
            })();
        </script>
        <script>
            function printContent(el) {
            var restorepage = $('body').html();
            var printcontent = $('#' + el).clone();
            $('body').empty().html(printcontent);
            window.print();
            $('body').html(restorepage);
            }
        </script>
        <script src="{{ asset('vendor/chart.js/chart.min.js') }}"></script>
    </body>
</html>
