<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fatima Yahaya Hospital Sifawa - Record Management System</title>

        <!-- Bootstrap CSS (local) -->
        <link rel="stylesheet" href="{{ asset('css/bootstrap.min.css') }}">
        <!-- Bootstrap Icons -->
        <link rel="stylesheet" href="{{ asset('vendor/bootstrap-icons/font/bootstrap-icons.css') }}">
        <!-- Fonts: using local/system fonts -->
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
        <style>
            :root {
                --primary-green: #27AE60;
                --primary-orange: #FF8C42;
                --light-green: #E8F8F5;
                --light-orange: #FFF5E6;
            }

            * {
                font-family: 'Poppins', sans-serif;
            }

            body {
                background: linear-gradient(135deg, var(--light-green) 0%, #f8f9fa 100%);
                overflow-x: hidden;
            }

            /* Navbar */
            .navbar {
                background-color: white;
                box-shadow: 0 4px 18px rgba(0, 0, 0, 0.08);
                padding: 1rem 0;
                border-bottom: 1px solid rgba(0, 0, 0, 0.08);
            }

            .navbar-brand {
                font-weight: 700;
                font-size: 1.35rem;
                color: #1f2937 !important;
                display: flex;
                align-items: center;
                gap: 0.75rem;
            }

            .navbar-brand img {
                background: white;
                border-radius: 12px;
                padding: 0.25rem;
                box-shadow: 0 2px 10px rgba(0, 0, 0, 0.08);
            }

            .nav-link {
                color: #475569 !important;
                font-weight: 500;
                margin: 0 0.5rem;
                transition: all 0.25s ease;
            }

            .nav-link:hover {
                color: var(--primary-green) !important;
                text-shadow: none;
            }

            .navbar-toggler {
                border-color: rgba(71, 85, 105, 0.3);
            }

            .btn-success {
                background-color: var(--primary-green);
                border-color: var(--primary-green);
                font-weight: 600;
                padding: 0.6rem 1.5rem;
                transition: all 0.3s ease;
            }

            .btn-success:hover {
                background-color: #229954;
                border-color: #229954;
                box-shadow: 0 6px 20px rgba(39, 174, 96, 0.3);
            }

            .btn-warning {
                background-color: var(--primary-orange);
                border-color: var(--primary-orange);
                color: white;
                font-weight: 600;
                padding: 0.6rem 1.5rem;
                transition: all 0.3s ease;
            }

            .btn-warning:hover {
                background-color: #E67E22;
                border-color: #E67E22;
                color: white;
                box-shadow: 0 6px 20px rgba(255, 140, 66, 0.3);
            }

            /* ================= HERO SECTION (REFINED) ================= */
            .hero-section {
                position: relative;
                min-height: 90vh;
                display: flex;
                align-items: center;
                justify-content: center;
                overflow: hidden;

                /* Background Image */
                background-image: url('/images/hero-bg.png');
                background-position: center;
                background-repeat: no-repeat;
                background-size: cover;

                /* Improve rendering */
                image-rendering: auto;
            }

            /* Softer overlay (less washout) */
            .hero-section::before {
                content: '';
                position: absolute;
                inset: 0;
                background: linear-gradient(
                    135deg,
                    rgba(39, 174, 96, 0.35),   /* softer green */
                    rgba(255, 140, 66, 0.25)   /* softer orange */
                );
                z-index: 1;
            }

            /* Content */
            .hero-content {
                position: relative;
                z-index: 2;
                color: #fff;
                text-align: center;

                /* Light glass effect (no blur distortion) */
                background: rgba(0, 0, 0, 0.25);
                padding: 30px;
                border-radius: 15px;
                backdrop-filter: blur(1px); /* reduced */
            }

            /* Icon */
            .hero-icon i {
                font-size: 3rem;
                color: #00ff88;
                margin-bottom: 15px;
            }

            /* Title */
            .hero-title {
                font-size: 2.8rem;
                font-weight: 700;
                text-shadow: 0 4px 15px rgba(0,0,0,0.6);
            }

            /* Subtitle */
            .hero-subtitle {
                font-size: 1.3rem;
                font-weight: 500;
                opacity: 0.95;
            }

            /* Tagline */
            .hero-tagline {
                font-size: 1rem;
                opacity: 0.9;
            }
            /* Feature Cards */
            .feature-card {
                background: white;
                border: 2px solid transparent;
                border-radius: 15px;
                padding: 2rem;
                margin: 1rem 0;
                transition: all 0.3s ease;
                box-shadow: 0 5px 15px rgba(0, 0, 0, 0.08);
                height: 100%;
            }

            .feature-card:hover {
                border-color: var(--primary-green);
                transform: translateY(-10px);
                box-shadow: 0 15px 30px rgba(39, 174, 96, 0.2);
            }

            .feature-icon {
                font-size: 2.5rem;
                margin-bottom: 1rem;
                width: 70px;
                height: 70px;
                display: flex;
                align-items: center;
                justify-content: center;
                background: var(--light-green);
                border-radius: 12px;
                color: var(--primary-green);
            }

            .feature-card:nth-child(2n) .feature-icon {
                background: var(--light-orange);
                color: var(--primary-orange);
            }

            .feature-title {
                font-size: 1.3rem;
                font-weight: 600;
                color: #1a1a1a;
                margin-bottom: 0.5rem;
            }

            .feature-description {
                color: #666;
                font-size: 0.95rem;
                line-height: 1.6;
            }

            /* Stats Section */
            .stats-section {
                background: linear-gradient(135deg, rgba(39, 174, 96, 0.05) 0%, rgba(255, 140, 66, 0.05) 100%);
                padding: 3rem 0;
                margin: 3rem 0;
                border-radius: 15px;
            }

            .stat-item {
                text-align: center;
                padding: 1.5rem;
            }

            .stat-number {
                font-size: 2.5rem;
                font-weight: 700;
                color: var(--primary-green);
                display: block;
            }

            .stat-label {
                color: #666;
                font-weight: 500;
                margin-top: 0.5rem;
            }

            /* CTA Button */
            .cta-button {
                position: relative;
                overflow: hidden;
                font-weight: 600;
                padding: 0.8rem 2.5rem;
                font-size: 1rem;
                border-radius: 50px;
                border: none;
                transition: all 0.3s ease;
            }

            .cta-button::before {
                content: '';
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: rgba(255, 255, 255, 0.2);
                transition: left 0.3s ease;
            }

            .cta-button:hover::before {
                left: 100%;
            }

            .cta-button span {
                position: relative;
                z-index: 1;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            /* Footer */
            .footer {
                background: linear-gradient(90deg, #1a4d2e 0%, var(--primary-green) 100%);
                color: white;
                padding: 2rem 0;
                margin-top: 3rem;
                text-align: center;
            }

            .footer p {
                margin: 0;
                opacity: 0.9;
            }

            /* Badge */
            .badge-green {
                background-color: var(--primary-green);
                color: white;
            }

            .badge-orange {
                background-color: var(--primary-orange);
                color: white;
            }

            /* Responsive */
            @media (max-width: 768px) {
                .hero-title {
                    font-size: 1.8rem;
                }

                .hero-icon {
                    font-size: 3rem;
                }

                .stat-number {
                    font-size: 2rem;
                }
            }

            /* ================= DEPARTMENTS ================= */
            .departments-section {
                background: linear-gradient(
                    135deg,
                    rgba(39, 174, 96, 0.05),
                    rgba(255, 140, 66, 0.05)
                );
                border-radius: 20px;
            }

            /* Section title */
            .section-title {
                font-size: 2.2rem;
                font-weight: 700;
                color: #1a1a1a;
            }

            .section-subtitle {
                color: #666;
                font-size: 1rem;
            }

            /* Card */
            .dept-card {
                background: #fff;
                border-radius: 15px;
                padding: 25px;
                text-align: center;
                transition: all 0.3s ease;
                height: 100%;
                border: 1px solid transparent;
                box-shadow: 0 5px 15px rgba(0,0,0,0.05);
            }

            .dept-card:hover {
                transform: translateY(-8px);
                border-color: var(--primary-green);
                box-shadow: 0 15px 30px rgba(39, 174, 96, 0.15);
            }

            /* Icon container */
            .dept-icon {
                width: 65px;
                height: 65px;
                margin: 0 auto 15px;
                display: flex;
                align-items: center;
                justify-content: center;
                border-radius: 12px;
                font-size: 1.8rem;
            }

            /* Color variations */
            .bg-green {
                background: var(--light-green);
                color: var(--primary-green);
            }

            .bg-orange {
                background: var(--light-orange);
                color: var(--primary-orange);
            }

            /* Text */
            .dept-card h5 {
                font-weight: 600;
                margin-bottom: 8px;
            }

            .dept-card p {
                font-size: 0.9rem;
                color: #666;
            }
        </style>
    </head>
    <body>
        <!-- Navigation -->
        <nav class="navbar navbar-expand-lg navbar-light fixed-top w-100">
            <div class="container-fluid px-4">
                <a class="navbar-brand" href="#">
                    <img src="{{ asset('images/logo.png') }}" alt="FYH Logo" width="40" height="40" class="d-inline-block align-text-top">
                    Fatima Yahaya Hospital, Sifawa
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarNav">
                    <div class="navbar-nav ms-auto">
                        @if (Route::has('login'))
                            @auth
                                <a class="nav-link" href="{{ url('/dashboard') }}">
                                    <i class="bi bi-speedometer2"></i> Dashboard
                                </a>
                            @else
                                <a class="nav-link" href="{{ route('login') }}">
                                    <i class="bi bi-box-arrow-in-left"></i> Login
                                </a>
                                @if (Route::has('register'))
                                    <a class="nav-link" href="{{ route('register') }}">
                                        <i class="bi bi-person-plus"></i> Register
                                    </a>
                                @endif
                            @endauth
                        @endif
                    </div>
                </div>
            </div>
        </nav>

        <!-- Hero Section -->
        <section class="hero-section w-100 mt-5">
            <div class="overlay"></div> <!-- for readability -->
            
            <div class="container-lg">
                <div class="hero-content text-center">
                    <h1 class="hero-title">Fatima Yahaya Hospital Sifawa</h1>
                    <p class="hero-subtitle mb-4">Patient Record Management System</p>
                    <p class="hero-tagline">Revolutionizing Healthcare with Digital Excellence</p>
                </div>
            </div>
        </section>

        <!-- Main Content -->
        <div class="container-lg py-5">
            <section class="departments-section py-5">
                <div class="container-lg">
                    <div class="text-center mb-5">
                        <h2 class="section-title">Our Departments & Services</h2>
                        <p class="section-subtitle">
                            Comprehensive healthcare services powered by modern technology
                        </p>
                    </div>

                    <div class="row g-4">

                        <!-- Card 1 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-green">
                                    <i class="bi bi-heart-pulse"></i>
                                </div>
                                <h5>Cardiology</h5>
                                <p>Advanced heart care with ECG, ECHO, and monitoring systems.</p>
                            </div>
                        </div>

                        <!-- Card 2 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-orange">
                                    <i class="bi bi-clipboard2-pulse"></i>
                                </div>
                                <h5>Laboratory</h5>
                                <p>Accurate diagnostic testing with digital lab record integration.</p>
                            </div>
                        </div>

                        <!-- Card 3 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-green">
                                    <i class="bi bi-x-ray"></i>
                                </div>
                                <h5>Radiology</h5>
                                <p>X-ray, ultrasound, and imaging services with digital storage.</p>
                            </div>
                        </div>

                        <!-- Card 4 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-orange">
                                    <i class="bi bi-hospital"></i>
                                </div>
                                <h5>Emergency</h5>
                                <p>24/7 emergency response with fast patient record access.</p>
                            </div>
                        </div>

                        <!-- Card 5 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-green">
                                    <i class="bi bi-person-heart"></i>
                                </div>
                                <h5>Outpatient</h5>
                                <p>Efficient patient consultations and appointment tracking.</p>
                            </div>
                        </div>

                        <!-- Card 6 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-orange">
                                    <i class="bi bi-capsule"></i>
                                </div>
                                <h5>Pharmacy</h5>
                                <p>Medication management and prescription tracking system.</p>
                            </div>
                        </div>

                        <!-- Card 7 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-green">
                                    <i class="bi bi-activity"></i>
                                </div>
                                <h5>Diagnostics</h5>
                                <p>Comprehensive health checks powered by smart analytics.</p>
                            </div>
                        </div>

                        <!-- Card 8 -->
                        <div class="col-md-6 col-lg-3">
                            <div class="dept-card">
                                <div class="dept-icon bg-orange">
                                    <i class="bi bi-shield-check"></i>
                                </div>
                                <h5>Health Records</h5>
                                <p>Secure patient data management with instant accessibility.</p>
                            </div>
                        </div>

                    </div>
                </div>
            </section>
            <!-- Features Section -->
            <section class="mb-5">
                <h2 class="text-center mb-5" style="font-size: 2rem; font-weight: 700; color: var(--primary-green);">
                    <i class="bi bi-stars"></i> Key Features
                </h2>
                
                <div class="row">
                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-file-earmark-medical"></i>
                            </div>
                            <h5 class="feature-title">Medical Records</h5>
                            <p class="feature-description">Comprehensive patient medical history and clinical data management with secure access control.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-person-check"></i>
                            </div>
                            <h5 class="feature-title">Patient Management</h5>
                            <p class="feature-description">Easy registration, tracking and management of patient information and appointments.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-prescription2"></i>
                            </div>
                            <h5 class="feature-title">Prescriptions</h5>
                            <p class="feature-description">Digital prescription management with medication tracking and dosage records.</p>
                        </div>
                    </div>

                    <div class="col-md-6 col-lg-3">
                        <div class="feature-card">
                            <div class="feature-icon">
                                <i class="bi bi-graph-up"></i>
                            </div>
                            <h5 class="feature-title">Analytics</h5>
                            <p class="feature-description">Real-time analytics and reports for better healthcare decision making.</p>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Stats Section -->
            <section class="stats-section">
                <div class="row">
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">500+</span>
                            <span class="stat-label">Patients Served</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">50+</span>
                            <span class="stat-label">Medical Staff</span>
                        </div>
                    </div>
                    <div class="col-md-4">
                        <div class="stat-item">
                            <span class="stat-number">24/7</span>
                            <span class="stat-label">Service Available</span>
                        </div>
                    </div>
                </div>
            </section>

            <!-- Additional Features Grid -->
            <section class="my-5">
                <h3 class="text-center mb-5" style="font-size: 1.8rem; font-weight: 700; color: var(--primary-orange);">
                    <i class="bi bi-gear"></i> Advanced Capabilities
                </h3>

                <div class="row" style="gap: 2rem;">
                    <div class="col-md-6">
                        <div class="d-flex gap-3 p-3" style="background: var(--light-green); border-radius: 10px;">
                            <div style="font-size: 2rem; color: var(--primary-green); flex-shrink: 0;">
                                <i class="bi bi-shield-check"></i>
                            </div>
                            <div>
                                <h5 style="color: var(--primary-green); font-weight: 600;">Secure Data Protection</h5>
                                <p style="color: #666; margin: 0;">HIPAA compliant with end-to-end encryption</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex gap-3 p-3" style="background: var(--light-orange); border-radius: 10px;">
                            <div style="font-size: 2rem; color: var(--primary-orange); flex-shrink: 0;">
                                <i class="bi bi-cloud-check"></i>
                            </div>
                            <div>
                                <h5 style="color: var(--primary-orange); font-weight: 600;">Cloud Integration</h5>
                                <p style="color: #666; margin: 0;">Accessible from anywhere with cloud backup</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex gap-3 p-3" style="background: var(--light-green); border-radius: 10px;">
                            <div style="font-size: 2rem; color: var(--primary-green); flex-shrink: 0;">
                                <i class="bi bi-phone"></i>
                            </div>
                            <div>
                                <h5 style="color: var(--primary-green); font-weight: 600;">Mobile Accessible</h5>
                                <p style="color: #666; margin: 0;">Responsive design for all devices</p>
                            </div>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="d-flex gap-3 p-3" style="background: var(--light-orange); border-radius: 10px;">
                            <div style="font-size: 2rem; color: var(--primary-orange); flex-shrink: 0;">
                                <i class="bi bi-chat-dots"></i>
                            </div>
                            <div>
                                <h5 style="color: var(--primary-orange); font-weight: 600;">Telemedicine Support</h5>
                                <p style="color: #666; margin: 0;">Remote consultation and monitoring</p>
                            </div>
                        </div>
                    </div>
                </div>
            </section>

            <!-- CTA Section -->
            <section class="text-center my-5 py-5">
                <h3 style="font-size: 2rem; font-weight: 700; color: #1a1a1a; margin-bottom: 2rem;">
                    Ready to Get Started?
                </h3>
                <div class="d-flex gap-3 justify-content-center flex-wrap">
                    @if (Route::has('login'))
                        @auth
                            <a href="{{ url('/dashboard') }}" class="btn btn-success cta-button">
                                <span><i class="bi bi-arrow-right"></i> Go to Dashboard</span>
                            </a>
                        @else
                            <a href="{{ route('login') }}" class="btn btn-success cta-button">
                                <span><i class="bi bi-box-arrow-in-right"></i> Login Now</span>
                            </a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}" class="btn btn-warning cta-button">
                                    <span><i class="bi bi-person-plus"></i> Create Account</span>
                                </a>
                            @endif
                        @endauth
                    @endif
                </div>
            </section>

        </div>

        <!-- Footer -->
        <footer class="footer w-100 mt-5">
            <div class="container-lg">
                <p><i class="bi bi-c-circle"></i> 2026 Fatima Yahaya Hospital Sifawa. All rights reserved.</p>
                <p style="font-size: 0.9rem; margin-top: 0.5rem;">Patient Safety | Data Security | Healthcare Excellence</p>
            </div>
        </footer>

        <!-- Bootstrap JS -->
        <script src="{{ asset('js/bootstrap.bundle.min.js') }}"></script>
    </body>
</html>
