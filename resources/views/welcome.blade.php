<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>Fatima Yahaya Hospital Sifawa - Record Management System</title>
        
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <link rel="icon" href="{{ asset('images/logo.png') }}" type="image/png">
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

    </body>
</html>
