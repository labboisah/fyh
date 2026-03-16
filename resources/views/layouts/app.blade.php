<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} | @yield('title')</title>
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
        <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <!-- DataTables CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
        <!-- DataTables Buttons CSS -->
        <link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.1/css/buttons.bootstrap5.min.css">
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
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
</style>
        @yield('styles')
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-light">

            <nav class="navbar navbar-expand-lg hospital-navbar shadow-sm">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                        <x-hospital-logo class="me-2" />
                        <div class="ms-2">
                            <div class="fw-bold brand-accent" ><img src="{{asset('images/logo.png')}}" alt="logo" width="60"> <span style="transform: scaleY(2);">FYH</span> </div>
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

                            @if(Auth::user()->hasRole('record_officer'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ url('/patients') }}"><i class="bi bi-people-fill me-2 text-success"></i>Patients</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('nurse'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('nurse.patients.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Patients</a>
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

                            @if(Auth::user()->hasRole('lab_technician'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.requests.index') }}"><i class="bi bi-vial me-2 text-success"></i>{{count(auth()->user()->department->investigationRequests())}} Requests</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.investigations.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Investigations</a>
                            </li>

                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.consumables.index') }}"><i class="bi bi-box-seam me-2 text-success"></i>Consumables</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.stocks.index') }}"><i class="bi bi-boxes me-2 text-success"></i>Stock</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('lab.investigations.index') }}"><i class="bi bi-cash-stack me-2 text-success"></i>Expense</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('radiologist'))
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('radiograph.requests.index') }}"><i class="bi bi-vial me-2 text-success"></i>{{count(auth()->user()->department->investigationRequests())}} Requests</a>
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
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ route('accountant.reports.financial') }}"><i class="bi bi-bar-chart me-2 text-success"></i>Reports</a>
                            </li>
                            @endif

                            @if(Auth::user()->hasRole('administrator'))
                                
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.index') }}"><i class="bi bi-shield-check me-2 text-success"></i>Access Control</a>
                                </li>
                                <!-- Add more admin-specific links here -->
                                 <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Manage Users</a>
                                </li>
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.services.index') }}"><i class="bi bi-gear-fill me-2 text-success"></i>Manage Services</a>
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

                                <li class="nav-item">
                                    <a class="nav-link" href="#">
                                        <i class="bi bi-bar-chart-line me-1"></i>
                                        Reports
                                    </a>
                                </li>
                            @endif

                            @auth
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle d-flex align-items-center" href="#" id="userMenu" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                                        <i class="bi bi-person-circle me-2 text-secondary"></i>
                                        {{ Auth::user()->name }}
                                    </a>
                                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="userMenu">
                                        <li><a class="dropdown-item" href="{{ route('profile.edit') }}">Profile</a></li>
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
                    @yield('content')
                </div>
            </main>

            

            
        </div>

        <!-- jQuery (required by DataTables) -->
        <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
        <!-- DataTables JS -->
        <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
        <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
        <!-- DataTables Buttons -->
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/dataTables.buttons.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.bootstrap5.min.js"></script>
        <!-- Export Libraries -->
        <script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.10.1/jszip.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/pdfmake.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.2.7/vfs_fonts.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.html5.min.js"></script>
        <script src="https://cdn.datatables.net/buttons/2.4.1/js/buttons.print.min.js"></script>

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
            function printContent(el) {
            var restorepage = $('body').html();
            var printcontent = $('#' + el).clone();
            $('body').empty().html(printcontent);
            window.print();
            $('body').html(restorepage);
            }
        </script>
    </body>
</html>
