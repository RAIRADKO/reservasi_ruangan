<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Reservasi Ruangan')</title>

    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=inter:400,500,600,700&display=swap" rel="stylesheet" />
    
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">

    
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f8f9fa;
            padding-top: 80px;
        }
        .card {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.05);
            border-radius: 0.75rem;
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-5px);
        }
        .btn-primary {
            background-color: #0d6efd;
            border-color: #0d6efd;
            transition: all 0.2s;
        }
        .btn-primary:hover {
            background-color: #0b5ed7;
            transform: scale(1.02);
        }
        .navbar {
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            height: 80px;
        }
        .navbar-nav .nav-link {
            padding: 0.5rem 1rem;
            border-radius: 0.5rem;
            transition: background-color 0.2s;
        }
        .navbar-nav .nav-link:hover,
        .navbar-nav .nav-link.active {
            background-color: rgba(13, 110, 253, 0.1);
        }
        .dropdown-menu {
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
            border-radius: 0.5rem;
            margin-top: 0.5rem;
        }
        .dropdown-item {
            padding: 0.75rem 1.25rem;
            border-radius: 0.25rem;
            transition: all 0.2s;
        }
        .dropdown-item:hover {
            background-color: #0d6efd;
            color: white;
        }
        .alert {
            border-radius: 0.75rem;
        }
        .debug-info {
            background: #fff3cd;
            border: 1px solid #ffeaa7;
            color: #856404;
            padding: 0.75rem;
            margin-bottom: 1rem;
            border-radius: 0.375rem;
        }
        
            
        .alert-dismissible {
            animation: fadeIn 0.3s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        
        .btn-register {
            transition: all 0.3s;
        }
        .btn-register:hover {
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }
        
        
        @media (max-width: 991.98px) {
            .navbar-brand span {
                display: none;
            }
            
            .navbar {
                height: 70px;
                padding: 0.5rem 1rem;
            }
            
            body {
                padding-top: 70px;
            }
            
            .navbar-toggler {
                padding: 0.25rem 0.5rem;
                font-size: 1.25rem;
            }
            
            
            .navbar-collapse {
                background-color: #0F172A;
                border-radius: 0.5rem;
                margin-top: 1rem;
                padding: 1rem;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            }
            
            .navbar-nav .nav-link {
                padding: 0.75rem 1rem;
                margin-bottom: 0.5rem;
                border-radius: 0.5rem;
                border: 1px solid rgba(255, 255, 255, 0.1);
                transition: all 0.3s ease;
            }
            
            .navbar-nav .nav-link:hover {
                background-color: rgba(255, 255, 255, 0.1);
                border-color: rgba(255, 255, 255, 0.2);
            }
            
            .navbar-nav .nav-link.active {
                background-color: rgba(255, 255, 255, 0.15);
                border-color: rgba(255, 255, 255, 0.3);
            }
            
            
            .mobile-logout-btn {
                width: 100%;
                padding: 0.75rem;
                background-color: transparent;
                border: 1px solid rgba(220, 38, 127, 0.3);
                color: #f87171;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
                margin-top: 0.5rem;
            }
            
            .mobile-logout-btn:hover {
                background-color: rgba(220, 38, 127, 0.1);
                border-color: rgba(220, 38, 127, 0.5);
                color: #fca5a5;
            }
        }
        
        @media (max-width: 767.98px) {
            .navbar-brand {
                font-size: 1rem;
            }
            
            .navbar-nav .nav-link {
                padding: 0.5rem;
                font-size: 0.9rem;
            }
            
            .btn-register {
                padding: 0.375rem 0.75rem;
                font-size: 0.875rem;
            }
            
            .alert {
                font-size: 0.875rem;
            }
        }
        
        @media (max-width: 575.98px) {
            .navbar {
                height: 60px;
            }
            
            body {
                padding-top: 60px;
            }
            
            .navbar-brand {
                font-size: 0.9rem;
            }
            
            .navbar-toggler-icon {
                width: 1.2em;
                height: 1.2em;
            }
            
            .dropdown-item {
                padding: 0.5rem 1rem;
                font-size: 0.875rem;
            }
            
            .main {
                padding-top: 1rem;
                padding-bottom: 1rem;
            }
        }
    </style>
    
    @yield('styles')
    <style>
        
        .navbar-logo {
            width: 35px;
            height: auto;
        }

        .navbar-custom {
            background-color: #0F172A !important;
            padding: 1rem 0;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            height: auto;
        }
        
        .navbar-custom .navbar-brand,
        .navbar-custom .nav-link {
            color: #ffffff !important;
        }
        
        .navbar-custom .nav-link:hover {
            color: #94a3b8 !important;
            background-color: rgba(255, 255, 255, 0.1) !important;
        }
        
        .navbar-custom .navbar-toggler {
            border-color: rgba(255, 255, 255, 0.5);
        }
        
        .navbar-custom .navbar-toggler-icon {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 30 30'%3e%3cpath stroke='rgba%28255, 255, 255, 0.85%29' stroke-linecap='round' stroke-miterlimit='10' stroke-width='2' d='M4 7h22M4 15h22M4 23h22'/%3e%3c/svg%3e");
        }

        .navbar-custom .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1) !important;
            border-radius: 0.5rem;
        }

        /* Styling untuk jargon */
        .navbar-brand-text {
            line-height: 1;
        }

        .navbar-app-name {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.50rem;
        }

        .navbar-jargon {
            font-size: 0.7rem;
            font-weight: 400;
            color: #94a3b8 !important;
            margin-top: -2px;
            opacity: 0.9;
        }

        /* Responsive adjustments untuk jargon */
        @media (max-width: 991.98px) {
            .navbar-app-name {
                font-size: 1.2rem;
            }
            
            .navbar-jargon {
                font-size: 0.65rem;
            }
        }

        @media (max-width: 767.98px) {
            .navbar-app-name {
                font-size: 1rem;
            }
            
            .navbar-jargon {
                font-size: 0.6rem;
            }
        }

        @media (max-width: 575.98px) {
            .navbar-app-name {
                font-size: 0.9rem;
            }
            
            .navbar-jargon {
                font-size: 0.55rem;
            }
        }

        
        .footer-custom {
            background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%);
            color: #ffffff;
            padding: 3rem 0 1rem 0;
            margin-top: 4rem;
        }
        
        .footer-logo {
            max-width: 60px;
            height: auto;
        }
        
        .footer-title {
            font-size: 1.5rem;
            font-weight: 700;
            margin-bottom: 0.5rem;
        }
        
        .footer-subtitle {
            font-size: 0.95rem;
            color: #94a3b8;
            margin-bottom: 1rem;
        }
        
        .footer-description {
            font-size: 0.9rem;
            line-height: 1.6;
            color: #cbd5e1;
            margin-bottom: 1.5rem;
        }
        
        .footer-section-title {
            font-size: 1.1rem;
            font-weight: 600;
            margin-bottom: 1rem;
            color: #ffffff;
        }
        
        .footer-link {
            color: #94a3b8;
            text-decoration: none;
            font-size: 0.9rem;
            display: block;
            margin-bottom: 0.5rem;
            transition: color 0.3s ease;
        }
        
        .footer-link:hover {
            color: #ffffff;
            text-decoration: none;
        }
        
        .footer-social-icons {
            margin-top: 1rem;
        }
        
        .footer-social-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 40px;
            height: 40px;
            background-color: rgba(255, 255, 255, 0.1);
            border-radius: 50%;
            color: #94a3b8;
            text-decoration: none;
            margin-right: 0.5rem;
            transition: all 0.3s ease;
        }
        
        .footer-social-icon:hover {
            background-color: rgba(255, 255, 255, 0.2);
            color: #ffffff;
            transform: translateY(-2px);
        }
        
        .footer-contact-item {
            display: flex;
            align-items: flex-start;
            margin-bottom: 0.75rem;
            font-size: 0.9rem;
        }
        
        .footer-contact-item i {
            margin-right: 0.75rem;
            margin-top: 0.2rem;
            color: #94a3b8;
            width: 16px;
        }
        
        .footer-contact-item span {
            color: #cbd5e1;
        }
        
        .footer-bottom {
            border-top: 1px solid rgba(255, 255, 255, 0.1);
            padding-top: 1.5rem;
            margin-top: 2rem;
        }
        
        .footer-bottom-text {
            font-size: 0.85rem;
            color: #64748b;
            margin: 0;
        }
        
        .footer-developer {
            font-size: 0.85rem;
            color: #64748b;
            margin: 0;
        }
        
        .footer-developer a {
            color: #94a3b8;
            text-decoration: none;
        }
        
        .footer-developer a:hover {
            color: #ffffff;
        }
        
        
        @media (max-width: 768px) {
            .footer-custom {
                padding: 2rem 0 1rem 0;
            }
            
            .footer-title {
                font-size: 1.3rem;
            }
            
            .footer-section-title {
                font-size: 1rem;
                margin-top: 1.5rem;
            }
            
            .footer-bottom {
                text-align: center;
            }
            
            .footer-bottom .row > div {
                margin-bottom: 0.5rem;
            }
        }
    </style>
</head>
<body class="d-flex flex-column min-vh-100">

    <nav class="navbar navbar-expand-lg fixed-top navbar-custom">
        <div class="container">
            <a class="navbar-brand fw-bold d-flex align-items-center" href="{{ route('home') }}">
                <img src="{{ asset('img/Lambang_Kabupaten_Purworejo.png') }}" alt="Logo Kabupaten Purworejo" class="navbar-logo me-3">
                <div class="navbar-brand-text">
                    <div class="navbar-app-name">CommandBook</div>
                    <div class="navbar-jargon">Mewujudkan Tata Kelola Perkantoran yang Modern, Efisien, dan Terintegrasi</div>
                </div>
            </a>
            
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            
            <div class="collapse navbar-collapse" id="navbarNav">
                <ul class="navbar-nav ms-auto align-items-lg-center">
                    @auth
                        
                        
                        
                        <li class="nav-item me-lg-2 d-none d-lg-block">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="bi bi-house-door me-2"></i>Home
                            </a>
                        </li>
                        <li class="nav-item me-lg-2 d-none d-lg-block">
                            <a class="nav-link {{ request()->routeIs('user.reservations') ? 'active' : '' }}" href="{{ route('user.reservations') }}">
                                <i class="bi bi-calendar-check me-2"></i>Reservasi Saya
                            </a>
                        </li>
                        <li class="nav-item me-lg-2 d-none d-lg-block">
                            <a class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">
                                <i class="bi bi-person me-2"></i>{{ Auth::user()->name }}
                            </a>
                        </li>
                        <li class="nav-item d-none d-lg-block">
                            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="nav-link btn btn-link text-decoration-none border-0 p-0" style="color: #ffffff !important;">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                        
                        
                        <li class="nav-item d-lg-none">
                            <a class="nav-link {{ request()->routeIs('home') ? 'active' : '' }}" href="{{ route('home') }}">
                                <i class="bi bi-house-door me-2"></i>Home
                            </a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link {{ request()->routeIs('user.reservations') ? 'active' : '' }}" href="{{ route('user.reservations') }}">
                                <i class="bi bi-calendar-check me-2"></i>Reservasi Saya
                            </a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <a class="nav-link {{ request()->routeIs('user.profile') ? 'active' : '' }}" href="{{ route('user.profile') }}">
                                <i class="bi bi-person me-2"></i>{{ Auth::user()->name }}
                            </a>
                        </li>
                        <li class="nav-item d-lg-none">
                            <form action="{{ route('logout') }}" method="POST" class="mb-0">
                                @csrf
                                <button type="submit" class="mobile-logout-btn">
                                    <i class="bi bi-box-arrow-right me-2"></i>Logout
                                </button>
                            </form>
                        </li>
                    @else
                        
                        <li class="nav-item me-2">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}" href="{{ route('login') }}">
                                <i class="bi bi-box-arrow-in-right me-1"></i>Login
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="btn btn-primary btn-register px-3" href="{{ route('register') }}">
                                <i class="bi bi-person-plus me-1"></i>Register
                            </a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="py-4 flex-grow-1 mt-4">
        <div class="container">
            
            @if(session('success'))
                <div class="alert alert-success alert-dismissible fade show d-flex align-items-center" role="alert">
                    <i class="bi bi-check-circle-fill me-2 fs-5"></i>
                    <div>{{ session('success') }}</div>
                    <button type="button" class="btn-close ms-auto" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            
            @if(session('error') || $errors->any())
                <div class="alert alert-danger alert-dismissible fade show" role="alert">
                    <div class="d-flex align-items-center">
                        <i class="bi bi-exclamation-triangle-fill me-2 fs-5"></i>
                        <strong>Oops!</strong>
                    </div>
                    
                    @if(session('error'))
                        <div class="mt-2">{{ session('error') }}</div>
                    @else
                        <ul class="mb-0 mt-2 ps-4">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    @endif
                    
                    <button type="button" class="btn-close position-absolute top-0 end-0 mt-2 me-3" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @yield('content')
        </div>
    </main>

    <footer class="footer-custom">
        <div class="container">
            <div class="row">
                <div class="col-lg-5 col-md-6 mb-4">
                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ asset('img/Lambang_Kabupaten_Purworejo.png') }}" alt="Logo Kabupaten Purworejo" class="footer-logo me-3">
                        <div>
                            <h5 class="footer-title">CoommandBook</h5>
                            <p class="footer-subtitle">Pemerintah Kabupaten Purworejo</p>
                        </div>
                    </div>
                    <p class="footer-description">
                        Aplikasi digital untuk memudahkan proses reservasi ruangan rapat di lingkungan Pemerintah Kabupaten Purworejo dengan sistem yang terintegrasi, efisien, dan mudah digunakan.
                    </p>
                    <div class="footer-social-icons">
                        <a href="https://www.instagram.com/purworejokab_/" class="footer-social-icon">
                            <i class="bi bi-instagram"></i>
                        </a>
                        <a href="https://www.youtube.com/@pemkabpurworejo8120" class="footer-social-icon">
                            <i class="bi bi-youtube"></i>
                        </a>
                        <a href="https://www.purworejokab.go.id/web/home.html" class="footer-social-icon">
                            <i class="bi bi-globe"></i>
                        </a>
                        <a href="https://x.com/purworejokab_" class="footer-social-icon">
                            <i class="bi bi-twitter-x"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-4 col-md-6 mb-4">
                    <h6 class="footer-section-title">Hubungi Kami</h6>
                    <div class="footer-contact-item">
                        <i class="bi bi-geo-alt-fill"></i>
                        <span>Jl. Proklamasi No. 2 Purworejo</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-telephone-fill"></i>
                        <span>(0275) 321493</span>
                    </div>
                    <div class="footer-contact-item">
                        <i class="bi bi-envelope-fill"></i>
                        <span>dinkominfo@purworejokab.go.id</span>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6 mb-4">
                    <h6 class="footer-section-title">Informasi</h6>
                    <p class="footer-description">
                        Sistem reservasi ruangan online yang memungkinkan pemesanan ruangan rapat 24/7 dengan kalender ketersediaan real-time dan notifikasi otomatis.
                    </p>
                </div>
            </div>

            <div class="footer-bottom">
                <div class="row align-items-center">
                    <div class="col-md-6">
                        <p class="footer-bottom-text">
                            © 2025 CommandBook - Kabupaten Purworejo. All rights reserved.
                        </p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="footer-developer">
                            Developed by <a href="https://www.linkedin.com/in/rahmatirfan/">Rahmat Irfan Adie Purwatmoko</a>
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </footer>

    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>

    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Initialize navbar toggle
            const navbarToggler = document.querySelector('.navbar-toggler');
            const navbarCollapse = document.querySelector('.navbar-collapse');
            
            if (navbarToggler && navbarCollapse) {
                navbarToggler.addEventListener('click', function() {
                    // Toggle the collapse manually if Bootstrap isn't working
                    if (navbarCollapse.classList.contains('show')) {
                        navbarCollapse.classList.remove('show');
                        navbarToggler.setAttribute('aria-expanded', 'false');
                    } else {
                        navbarCollapse.classList.add('show');
                        navbarToggler.setAttribute('aria-expanded', 'true');
                    }
                });
                
                // Close navbar when clicking outside
                document.addEventListener('click', function(event) {
                    if (!navbarToggler.contains(event.target) && !navbarCollapse.contains(event.target)) {
                        navbarCollapse.classList.remove('show');
                        navbarToggler.setAttribute('aria-expanded', 'false');
                    }
                });
                
                // Close navbar when clicking on nav links (mobile)
                const navLinks = navbarCollapse.querySelectorAll('.nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', function() {
                        if (window.innerWidth < 992) { // Only on mobile
                            navbarCollapse.classList.remove('show');
                            navbarToggler.setAttribute('aria-expanded', 'false');
                        }
                    });
                });
            }

            // Auto-close alerts after 5 seconds
            const alerts = document.querySelectorAll('.alert-dismissible');
            alerts.forEach(alert => {
                setTimeout(() => {
                    const bsAlert = new bootstrap.Alert(alert);
                    bsAlert.close();
                }, 5000);
            });
        });
    </script>

    @yield('scripts')
</body>
</html>