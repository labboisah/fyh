<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="csrf-token" content="{{ csrf_token() }}">

        <title>{{ config('app.name') }} | @yield('title')</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

            <!-- Bootstrap CSS -->
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
        <!-- Icons -->
        <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
        <!-- Scripts -->
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" defer></script>
        <style>
            :root{
                --primary-green: #27AE60;
                --primary-orange: #FF8C42;
            }
            .hospital-navbar{ background: #fff; border-bottom: 1px solid rgba(0,0,0,0.05); }
            .brand-accent{ color: var(--primary-green); }
            .btn-accent{ background: linear-gradient(90deg, var(--primary-green), var(--primary-orange)); color: #fff; }
            footer.site-footer{ background:#072B21; color: #fff; }
        </style>
    </head>
    <body class="font-sans antialiased">
        <div class="min-h-screen bg-light">

            <nav class="navbar navbar-expand-lg hospital-navbar shadow-sm">
                <div class="container">
                    <a class="navbar-brand d-flex align-items-center" href="{{ route('dashboard') }}">
                        <x-hospital-logo class="me-2" />
                        <div class="ms-2">
                            <div class="fw-bold brand-accent"><img src="{{asset('images/logo.png')}}" alt="logo" width="60"> FATIMA YAHAYA HOSPITAL</div>
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
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ url('/patients') }}"><i class="bi bi-people-fill me-2 text-success"></i>Patients</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link d-flex align-items-center" href="{{ url('/records') }}"><i class="bi bi-folder-fill me-2 text-success"></i>Records</a>
                            </li>
                            @if(Auth::user()->hasRole('administrator'))
                                
                                <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.index') }}"><i class="bi bi-shield-check me-2 text-success"></i>Access Controle</a>
                                </li>
                                <!-- Add more admin-specific links here -->
                                 <li class="nav-item">
                                    <a class="nav-link d-flex align-items-center" href="{{ route('admin.users.index') }}"><i class="bi bi-people-fill me-2 text-success"></i>Manage Users</a>
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
                                        $breadcrumbsHtml = \Diglactic\Breadcrumbs\Breadcrumbs::render($routeName);
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

            

            <footer class="site-footer mt-auto py-4">
                <div class="container d-flex justify-content-between align-items-center">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-hospital-fill fs-4 text-white me-2"></i>
                        <div>
                            <strong class="text-white">Fatima Yahaya Hospital</strong>
                            <div class="small text-muted">Sifawa</div>
                        </div>
                    </div>
                    <div class="text-end small text-white-50">
                        <div><i class="bi bi-telephone-fill me-1"></i>+234 800 000 0000</div>
                        <div class="text-muted">&copy; {{ date('Y') }} {{ config('app.name', 'FH') }}</div>
                    </div>
                </div>
            </footer>
        </div>
    </body>
</html>
