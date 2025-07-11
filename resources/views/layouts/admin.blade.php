<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title', 'Admin Panel')</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.0/font/bootstrap-icons.css">
    
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    
    <style>
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            background-color: #f8f9fa;
        }
        
        .navbar-brand {
            font-weight: 600;
        }
        
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            transition: all 0.2s ease-in-out;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
        }
        
        .btn {
            border-radius: 0.375rem;
            font-weight: 500;
            transition: all 0.2s ease-in-out;
        }
        
        .btn:hover {
            transform: translateY(-1px);
        }
        
        .text-gray-800 {
            color: #5a5c69 !important;
        }
        
        .bg-gradient-primary {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        
        .bg-gradient-success {
            background: linear-gradient(135deg, #28a745 0%, #20c997 100%);
        }
        
        .bg-gradient-danger {
            background: linear-gradient(135deg, #dc3545 0%, #fd7e14 100%);
        }
        
        .bg-gradient-warning {
            background: linear-gradient(135deg, #ffc107 0%, #fd7e14 100%);
        }
        
        .bg-gradient-info {
            background: linear-gradient(135deg, #17a2b8 0%, #6f42c1 100%);
        }
        
        /* Mobile-First Design */
        .mobile-header {
            background: #212529;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            z-index: 1030;
            position: fixed;
            top: 0;
            width: 100%;
            left: 0;
            right: 0;
        }
        
        .mobile-toggle {
            color: white;
            font-size: 1.5rem;
            border: none;
            background: none;
            padding: 0.5rem;
            cursor: pointer;
            transition: all 0.2s ease-in-out;
        }
        
        .mobile-toggle:hover,
        .mobile-toggle:focus {
            color: #fff;
            background: rgba(255,255,255,0.1);
            border-radius: 0.375rem;
            outline: none;
        }
        
        .mobile-toggle:active {
            transform: scale(0.95);
        }
        
        .sidebar {
            background: #212529;
            box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            z-index: 1025;
            transition: all 0.3s ease-in-out;
        }
        
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            transition: all 0.2s ease-in-out;
            padding: 0.75rem 1rem;
            margin: 0.25rem 0.5rem;
            border-radius: 0.375rem;
        }
        
        .sidebar .nav-link:hover {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.1);
        }
        
        .sidebar .nav-link.active {
            color: #ffffff;
            background-color: rgba(255, 255, 255, 0.2);
        }
        
        .main-content {
            background-color: #ffffff;
            min-height: 100vh;
        }
        
        .sidebar-footer {
            border-top: 1px solid rgba(255,255,255,0.1);
            padding: 1rem;
            margin-top: auto;
        }
        
        /* Desktop Styles */
        @media (min-width: 992px) {
            .mobile-header {
                display: none;
            }
            
            .sidebar {
                position: sticky;
                top: 0;
                height: 100vh;
                overflow-y: auto;
                transform: translateX(0);
            }
            
            .main-content {
                border-radius: 0.5rem 0 0 0.5rem;
                box-shadow: 0 0.15rem 1.75rem 0 rgba(58, 59, 69, 0.15);
            }
        }
        
        /* Mobile Styles */
        @media (max-width: 991.98px) {
            .container-fluid {
                padding: 0;
            }
            
            .sidebar {
                position: fixed;
                top: 0;
                left: 0;
                width: 280px;
                height: 100vh;
                overflow-y: auto;
                padding-top: 60px;
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                width: 100%;
                height: 100%;
                background: rgba(0,0,0,0.5);
                z-index: 1020;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease-in-out;
            }
            
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
            
            .main-content {
                margin-top: 60px;
                padding: 1rem;
            }
            
            .btn-toolbar {
                flex-direction: column;
                gap: 0.5rem;
            }
            
            .btn-group {
                width: 100%;
            }
            
            .btn-group .btn {
                width: 100%;
            }
            
            /* Make tables responsive */
            .table-responsive {
                border-radius: 0.375rem;
                box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            }
            
            /* Adjust cards for mobile */
            .card {
                margin-bottom: 1rem;
            }
            
            .card-body {
                padding: 1rem;
            }
            
            /* Make buttons stack on mobile */
            .btn-group-vertical .btn {
                margin-bottom: 0.5rem;
            }
        }
        
        /* Tablet Styles */
        @media (min-width: 768px) and (max-width: 991.98px) {
            .main-content {
                padding: 1.5rem;
            }
        }
        
        /* Small Mobile Styles */
        @media (max-width: 575.98px) {
            .sidebar {
                width: 100%;
                transform: translateX(-100%);
            }
            
            .sidebar.show {
                transform: translateX(0);
            }
            
            .main-content {
                padding: 0.75rem;
            }
            
            .card-body {
                padding: 0.75rem;
            }
            
            .btn-sm {
                font-size: 0.75rem;
                padding: 0.25rem 0.5rem;
            }
            
            .h2 {
                font-size: 1.5rem;
            }
        }
        
        /* Animation classes */
        .fade-in {
            animation: fadeIn 0.5s ease-in-out;
        }
        
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .slide-up {
            animation: slideUp 0.3s ease-in-out;
        }
        
        @keyframes slideUp {
            from { transform: translateY(20px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        
        /* Utility classes for mobile */
        .text-truncate-mobile {
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        
        @media (max-width: 575.98px) {
            .text-truncate-mobile {
                max-width: 150px;
            }
        }
        
        /* Prevent body scroll when sidebar is open */
        body.sidebar-open {
            overflow: hidden;
        }
        
        /* Hamburger icon animation */
        .mobile-toggle .hamburger-icon {
            display: inline-block;
            transition: transform 0.3s ease-in-out;
        }
        
        .mobile-toggle.active .hamburger-icon {
            transform: rotate(90deg);
        }
    </style>
    
    @yield('styles')
</head>

<body>
    <div class="mobile-header d-lg-none">
        <div class="d-flex justify-content-between align-items-center p-3">
            <button class="mobile-toggle" type="button" id="sidebarToggle" aria-label="Toggle sidebar">
                <i class="bi bi-list hamburger-icon"></i>
            </button>
            <h5 class="text-white mb-0">
                <i class="bi bi-shield-check me-2"></i>
                Admin Panel
            </h5>
            <div class="dropdown">
                <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                    <i class="bi bi-person-circle"></i>
                </button>
                <ul class="dropdown-menu dropdown-menu-end">
                    <li><a class="dropdown-item" href="{{ route('home') }}" target="_blank"><i class="bi bi-house-door me-2"></i>Lihat Website</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li>
                        <form method="POST" action="{{ route('logout') }}" class="d-inline">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class="bi bi-box-arrow-right me-2"></i>Logout
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>
    </div>

    <div class="sidebar-overlay d-lg-none" id="sidebarOverlay"></div>

    <div class="container-fluid">
        <div class="row">
            <nav class="col-lg-2 sidebar" id="sidebar">
                <div class="h-100 d-flex flex-column">
                    <div class="text-center mb-4 pt-3 d-none d-lg-block">
                        <h4 class="text-white mb-0">
                            <i class="bi bi-shield-check me-2"></i>
                            {{-- Tampilkan nama peran secara dinamis --}}
                            @if(Auth::guard('admin')->user()->role == 'superadmin')
                                Super Admin Panel
                            @else
                                Admin Panel
                            @endif
                        </h4>
                    </div>
                    
                    <ul class="nav flex-column flex-grow-1">
                        {{-- Menu yang dapat diakses oleh SEMUA admin --}}
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}" 
                            href="{{ route('admin.dashboard') }}">
                                <i class="bi bi-speedometer2 me-2"></i>
                                Dashboard
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reservations.*') ? 'active' : '' }}" 
                            href="{{ route('admin.reservations.index') }}">
                                <i class="bi bi-journal-text me-2"></i>
                                Reservasi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.room.*') ? 'active' : '' }}"
                                href="{{ route('admin.room.index') }}">
                                <i class="bi bi-door-open-fill me-2"></i>
                                Manajemen Ruangan
                            </a>
                        </li>

                        {{-- Menu KHUSUS untuk Super Admin --}}
                        @if(Auth::guard('admin')->user()->role == 'superadmin')
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.calendar.*') ? 'active' : '' }}" 
                            href="{{ route('admin.calendar.management') }}">
                                <i class="bi bi-calendar3 me-2"></i>
                                Manajemen Kalender
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.users.*') ? 'active' : '' }}"
                                href="{{ route('admin.users.index') }}">
                                <i class="bi bi-people-fill me-2"></i>
                                Manajemen User
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}"
                                href="{{ route('admin.admins.index') }}">
                                <i class="bi bi-person-lock me-2"></i>
                                Manajemen Admin
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.dinas.*') ? 'active' : '' }}"
                                href="{{ route('admin.dinas.index') }}">
                                <i class="bi bi-building me-2"></i>
                                Manajemen Instansi
                            </a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('admin.reports.*') ? 'active' : '' }}"
                                href="{{ route('admin.reports.index') }}">
                                <i class="bi bi-graph-up me-2"></i>
                                Laporan
                            </a>
                        </li>
                        @endif
                    </ul>
                    
                    <div class="sidebar-footer d-none d-lg-block">
                        <div class="d-flex justify-content-around align-items-center">
                            <a class="btn btn-outline-light" href="{{ route('home') }}" target="_blank" data-bs-toggle="tooltip" data-bs-placement="top" title="Lihat Website">
                                <i class="bi bi-house-door"></i>
                            </a>
                            <form method="POST" action="{{ route('logout') }}" class="d-inline">
                                @csrf
                                <button type="submit" class="btn btn-outline-light" data-bs-toggle="tooltip" data-bs-placement="top" title="Logout">
                                    <i class="bi bi-box-arrow-right"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </nav>

            <main class="col-lg-10 main-content">
                <div class="pt-3 pb-2 mb-3 border-bottom d-none d-lg-block">
                    <div class="d-flex justify-content-between flex-wrap flex-md-nowrap align-items-center">
                        <h1 class="h2 text-gray-800">@yield('title', 'Admin Panel')</h1>
                        <div class="btn-toolbar mb-2 mb-md-0">
                            <div class="btn-group me-2">
                                <button type="button" class="btn btn-sm btn-outline-secondary">
                                    <i class="bi bi-person-circle me-1"></i>
                                    {{ Auth::guard('admin')->user()->username }}
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="pt-3 pb-2 mb-3 border-bottom d-lg-none">
                    <h1 class="h2 text-gray-800 text-center">@yield('title', 'Admin Panel')</h1>
                </div>

                <div class="fade-in">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>
                            {{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            {{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @yield('content')
                </div>
            </main>
        </div>
    </div>
    
    <div class="modal fade" id="confirmDeleteModal" tabindex="-1" aria-labelledby="confirmDeleteModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
          <div class="modal-header bg-danger text-white">
            <h5 class="modal-title" id="confirmDeleteModalLabel"><i class="bi bi-exclamation-triangle-fill me-2"></i>Konfirmasi Penghapusan</h5>
            <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
          </div>
          <div class="modal-body fs-6 py-4" id="confirmDeleteModalBody">
            Apakah Anda benar-benar yakin ingin menghapus item ini? Proses ini tidak dapat diurungkan.
          </div>
          <div class="modal-footer border-0">
            <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Batal</button>
            <form id="deleteForm" method="POST" action="" class="mb-0">
                @csrf
                @method('DELETE')
                <button type="submit" class="btn btn-danger"><i class="bi bi-trash-fill me-1"></i>Ya, Hapus</button>
            </form>
          </div>
        </div>
      </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        class SidebarToggle {
            constructor() {
                this.sidebar = document.getElementById('sidebar');
                this.overlay = document.getElementById('sidebarOverlay');
                this.toggleButton = document.getElementById('sidebarToggle');
                this.body = document.body;
                this.isOpen = false;
                
                this.init();
            }
            
            init() {
                if (this.toggleButton) {
                    this.toggleButton.addEventListener('click', (e) => {
                        e.preventDefault();
                        e.stopPropagation();
                        this.toggle();
                    });
                }
                
                if (this.overlay) {
                    this.overlay.addEventListener('click', () => {
                        this.close();
                    });
                }
                
                const navLinks = document.querySelectorAll('.sidebar .nav-link');
                navLinks.forEach(link => {
                    link.addEventListener('click', () => {
                        if (window.innerWidth < 992) {
                            this.close();
                        }
                    });
                });
                
                window.addEventListener('resize', () => {
                    if (window.innerWidth >= 992) {
                        this.close();
                    }
                });
                
                document.addEventListener('keydown', (e) => {
                    if (e.key === 'Escape' && this.isOpen) {
                        this.close();
                    }
                });
            }
            
            toggle() {
                if (this.isOpen) {
                    this.close();
                } else {
                    this.open();
                }
            }
            
            open() {
                if (this.sidebar && this.overlay) {
                    this.sidebar.classList.add('show');
                    this.overlay.classList.add('show');
                    this.body.classList.add('sidebar-open');
                    this.toggleButton.classList.add('active');
                    this.isOpen = true;
                }
            }
            
            close() {
                if (this.sidebar && this.overlay) {
                    this.sidebar.classList.remove('show');
                    this.overlay.classList.remove('show');
                    this.body.classList.remove('sidebar-open');
                    this.toggleButton.classList.remove('active');
                    this.isOpen = false;
                }
            }
        }
        
        document.addEventListener('DOMContentLoaded', function() {
            new SidebarToggle();
            
            setTimeout(function() {
                const alerts = document.querySelectorAll('.alert');
                alerts.forEach(function(alert) {
                    if (alert.classList.contains('show')) {
                        const bsAlert = new bootstrap.Alert(alert);
                        bsAlert.close();
                    }
                });
            }, 5000);
            
            if (window.innerWidth >= 992) {
                var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
                var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
                    return new bootstrap.Tooltip(tooltipTriggerEl);
                });
            }

            const cards = document.querySelectorAll('.card');
            cards.forEach(function(card, index) {
                setTimeout(function() {
                    card.classList.add('slide-up');
                }, index * 100);
            });
        });

        document.addEventListener('DOMContentLoaded', function () {
            const confirmDeleteModal = document.getElementById('confirmDeleteModal');
            if (confirmDeleteModal) {
                confirmDeleteModal.addEventListener('show.bs.modal', function (event) {
                    const button = event.relatedTarget;
                    const deleteUrl = button.getAttribute('data-url');
                    const confirmationMessage = button.getAttribute('data-message');
        
                    const modalBody = confirmDeleteModal.querySelector('#confirmDeleteModalBody');
                    const deleteForm = confirmDeleteModal.querySelector('#deleteForm');
        
                    deleteForm.setAttribute('action', deleteUrl);
                    modalBody.textContent = confirmationMessage || 'Apakah Anda yakin ingin menghapus item ini? Proses ini tidak dapat diurungkan.';
                });
            }
        });
    </script>
    
    @yield('scripts')
</body>
</html>