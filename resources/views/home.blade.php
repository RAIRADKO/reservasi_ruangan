@extends('layouts.app')

@section('title', 'Selamat Datang di Sistem Reservasi Ruangan')

@section('content')
<!-- Hero Section -->
<div class="hero-section mb-5">
    <div class="hero-content text-center">
        <div class="hero-icon mb-3">
            <i class="bi bi-building text-primary" style="font-size: 3rem;"></i>
        </div>
        <h1 class="hero-title mb-3">Sistem Elektronik Reservasi Ruangan</h1>
        <p class="hero-subtitle text-muted">Kelola reservasi ruangan dengan mudah dan efisien</p>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-7">
        <div class="card room-info-card h-100 shadow-sm">
            <div class="card-header bg-gradient-primary text-white py-4">
                <div class="d-flex align-items-center">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <h5 class="mb-0">Informasi Ruangan</h5>
                </div>
            </div>
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-5">
                        <div class="room-image-container">
                            <img src="{{ asset('img/Lambang_Kabupaten_Purworejo.png') }}" 
                                 class="img-fluid rounded-3 room-image" 
                                 alt="{{ $room->nama_ruangan }}">
                            <div class="image-overlay">
                                <i class="bi bi-eye text-white"></i>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-7">
                        <div class="room-details">
                            <h4 class="card-title fw-bold text-primary mb-3">{{ $room->nama_ruangan }}</h4>
                            <p class="card-text text-muted mb-4">{{ $room->deskripsi }}</p>
                            
                            <div class="room-stats">
                                <div class="row g-3">
                                    <div class="col-12">
                                        <div class="stat-item">
                                            <div class="stat-icon bg-primary-subtle">
                                                <i class="bi bi-people-fill text-primary"></i>
                                            </div>
                                            <div class="stat-content">
                                                <span class="stat-label">Kapasitas</span>
                                                <span class="stat-value">{{ $room->kapasitas }} orang</span>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="col-12">
                                        <div class="stat-item">
                                            <div class="stat-icon bg-warning-subtle">
                                                <i class="bi bi-star-fill text-warning"></i>
                                            </div>
                                            <div class="stat-content">
                                                <span class="stat-label">Fasilitas</span>
                                                <div class="facilities-list">
                                                    @foreach($room->fasilitas_array as $fasilitas)
                                                        <span class="badge bg-light text-dark me-1 mb-1">{{ $fasilitas }}</span>
                                                    @endforeach
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5">
        <div class="card action-card h-100 shadow-sm">
            <div class="card-body text-center d-flex flex-column justify-content-center p-5">
                <div class="action-icon mb-4">
                    <i class="bi bi-calendar-check text-success" style="font-size: 3rem;"></i>
                </div>
                <h3 class="card-title mb-3 text-primary">Siap untuk Rapat?</h3>
                
                @auth
                    <p class="text-muted mb-4">Ruangan tersedia untuk reservasi. Mulai booking sekarang juga!</p>
                    <div class="d-grid gap-3">
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg btn-animated">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            <span>Buat Reservasi Baru</span>
                        </a>
                        <small class="text-muted">
                            <i class="bi bi-clock me-1"></i>
                            Proses reservasi hanya membutuhkan beberapa menit
                        </small>
                    </div>
                @else
                    <p class="text-muted mb-4">Silakan login terlebih dahulu untuk dapat melakukan reservasi ruangan.</p>
                    <div class="d-grid gap-3">
                        <a href="{{ route('login') }}" class="btn btn-primary btn-lg btn-animated">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-primary btn-lg">
                            <i class="bi bi-person-plus me-2"></i>
                            Register
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Calendar Section -->
<div class="card calendar-card mt-5 shadow-sm">
    <div class="card-header bg-gradient-info text-white py-4">
        <div class="d-flex justify-content-between align-items-center">
            <div class="d-flex align-items-center">
                <i class="bi bi-calendar3 me-2"></i>
                <h5 class="mb-0">Kalender Ketersediaan</h5>
            </div>
            <div class="calendar-legend">
                <span class="badge bg-danger me-2">
                    <i class="bi bi-circle-fill me-1"></i>Penuh
                </span>
                <span class="badge bg-light text-dark border me-2">
                    <i class="bi bi-circle me-1"></i>Tersedia
                </span>
                <small class="text-white-50 ms-2">
                    <i class="bi bi-info-circle me-1"></i>
                    Klik pada tanggal untuk melihat detail jadwal
                </small>
            </div>
        </div>
    </div>
    <div class="card-body p-4">
        <div id="calendar"></div>
    </div>
</div>

<!-- Quick Stats -->
<div class="row g-4 mt-4">
    <div class="col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <div class="stat-icon-large bg-primary-subtle mb-3">
                    <i class="bi bi-calendar-date text-primary"></i>
                </div>
                <h4 class="fw-bold text-primary">{{ date('d') }}</h4>
                <p class="text-muted mb-0">Hari ini</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <div class="stat-icon-large bg-success-subtle mb-3">
                    <i class="bi bi-check-circle text-success"></i>
                </div>
                <h4 class="fw-bold text-success">{{ count($bookedDates) }}</h4>
                <p class="text-muted mb-0">Hari Terbooked</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <div class="stat-icon-large bg-warning-subtle mb-3">
                    <i class="bi bi-people text-warning"></i>
                </div>
                <h4 class="fw-bold text-warning">{{ $room->kapasitas }}</h4>
                <p class="text-muted mb-0">Kapasitas</p>
            </div>
        </div>
    </div>
    <div class="col-md-3">
        <div class="card stat-card text-center">
            <div class="card-body">
                <div class="stat-icon-large bg-info-subtle mb-3">
                    <i class="bi bi-star text-info"></i>
                </div>
                <h4 class="fw-bold text-info">{{ count($room->fasilitas_array) }}</h4>
                <p class="text-muted mb-0">Fasilitas</p>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Animate elements on scroll
        const animateOnScroll = () => {
            const elements = document.querySelectorAll('.card, .stat-card');
            elements.forEach(element => {
                const elementTop = element.getBoundingClientRect().top;
                const elementVisible = 150;
                
                if (elementTop < window.innerHeight - elementVisible) {
                    element.classList.add('animate-in');
                }
            });
        };
        
        window.addEventListener('scroll', animateOnScroll);
        animateOnScroll(); // Run once on load
        
        // Calendar initialization
        var calendarEl = document.getElementById('calendar');
        var bookedDates = @json($bookedDates);
        
        var events = bookedDates.map(function(date) {
            return {
                title: 'Penuh',
                start: date,
                allDay: true,
                display: 'background',
                color: '#f8d7da',
                className: 'booked-date'
            };
        });
        
        var reservationsDateRoute = "{{ route('reservations.date', ['date' => ':date']) }}";
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 'auto',
            events: events,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth,dayGridWeek'
            },
            buttonText: {
                today: 'Hari Ini',
                month: 'Bulan',
                week: 'Minggu'
            },
            dayCellDidMount: function(info) {
                info.el.setAttribute('data-bs-toggle', 'tooltip');
                info.el.setAttribute('data-bs-placement', 'top');
                info.el.setAttribute('title', 'Lihat Jadwal ' + info.date.toLocaleDateString('id-ID'));
            },
            dateClick: function(info) {
                var url = reservationsDateRoute.replace(':date', info.dateStr);
                window.location.href = url;
            },
            eventDidMount: function(info) {
                info.el.style.borderRadius = '8px';
            }
        });
        
        calendar.render();

        // Initialize tooltips
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    /* Hero Section */
    .hero-section {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        padding: 4rem 0;
        border-radius: 1rem;
        margin-bottom: 2rem;
        position: relative;
        overflow: hidden;
    }
    
    .hero-section::before {
        content: '';
        position: absolute;
        top: -50%;
        left: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        animation: float 20s ease-in-out infinite;
    }
    
    .hero-content {
        position: relative;
        z-index: 1;
    }
    
    .hero-title {
        font-size: 2.5rem;
        font-weight: 700;
        margin-bottom: 1rem;
        text-shadow: 0 2px 4px rgba(0,0,0,0.1);
    }
    
    .hero-subtitle {
        font-size: 1.1rem;
        opacity: 0.9;
    }
    
    .hero-icon {
        animation: bounce 2s ease-in-out infinite;
    }
    
    /* Card Styles */
    .room-info-card, .action-card, .calendar-card {
        border: none;
        border-radius: 1rem;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(30px);
    }
    
    .room-info-card:hover, .action-card:hover, .calendar-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 15px 35px rgba(0,0,0,0.1) !important;
    }
    
    .animate-in {
        opacity: 1;
        transform: translateY(0);
    }
    
    /* Gradient Headers */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);
    }
    
    .bg-gradient-info {
        background: linear-gradient(135deg, #17a2b8 0%, #138496 100%);
    }
    
    /* Room Image */
    .room-image-container {
        position: relative;
        overflow: hidden;
        border-radius: 1rem;
    }
    
    .room-image {
        transition: transform 0.3s ease;
    }
    
    .room-image-container:hover .room-image {
        transform: scale(1.05);
    }
    
    .image-overlay {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0,0,0,0.5);
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0;
        transition: opacity 0.3s ease;
    }
    
    .room-image-container:hover .image-overlay {
        opacity: 1;
    }
    
    .image-overlay i {
        font-size: 2rem;
    }
    
    /* Room Stats */
    .stat-item {
        display: flex;
        align-items: center;
        padding: 1rem;
        background: #f8f9fa;
        border-radius: 0.5rem;
        margin-bottom: 0.5rem;
    }
    
    .stat-icon {
        width: 50px;
        height: 50px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin-right: 1rem;
    }
    
    .stat-content {
        flex: 1;
    }
    
    .stat-label {
        display: block;
        font-size: 0.9rem;
        color: #6c757d;
        margin-bottom: 0.25rem;
    }
    
    .stat-value {
        display: block;
        font-weight: 600;
        color: #212529;
    }
    
    /* Facilities */
    .facilities-list {
        display: flex;
        flex-wrap: wrap;
        gap: 0.25rem;
    }
    
    /* Action Card */
    .action-icon {
        animation: pulse 2s ease-in-out infinite;
    }
    
    /* Animated Buttons */
    .btn-animated {
        position: relative;
        overflow: hidden;
        transition: all 0.3s ease;
    }
    
    .btn-animated::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255,255,255,0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .btn-animated:hover::before {
        left: 100%;
    }
    
    .btn-animated:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    
    /* Calendar Styles */
    .fc-header-toolbar {
        margin-bottom: 1.5rem;
    }
    
    .fc-button {
        border-radius: 0.5rem !important;
        border: none !important;
        font-weight: 500 !important;
    }
    
    .fc-button-primary {
        background: #007bff !important;
    }
    
    .fc-button-primary:hover {
        background: #0056b3 !important;
    }
    
    .fc-daygrid-day-frame {
        border-radius: 0.5rem;
        transition: all 0.2s ease;
    }
    
    .fc-daygrid-day-frame:hover {
        background-color: #e9ecef !important;
        cursor: pointer;
        transform: scale(1.02);
    }
    
    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.1) !important;
        border: 2px solid #007bff !important;
    }
    
    .fc-daygrid-day-number {
        font-weight: 600;
        color: #495057;
    }
    
    /* Stats Cards */
    .stat-card {
        border: none;
        border-radius: 1rem;
        transition: all 0.3s ease;
        opacity: 0;
        transform: translateY(20px);
    }
    
    .stat-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(0,0,0,0.1);
    }
    
    .stat-icon-large {
        width: 70px;
        height: 70px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        margin: 0 auto;
    }
    
    .stat-icon-large i {
        font-size: 2rem;
    }
    
    /* Calendar Legend */
    .calendar-legend .badge {
        font-size: 0.8rem;
        padding: 0.5rem 0.75rem;
    }
    
    /* Background Colors */
    .bg-primary-subtle {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    .bg-success-subtle {
        background-color: rgba(25, 135, 84, 0.1) !important;
    }
    
    .bg-warning-subtle {
        background-color: rgba(255, 193, 7, 0.1) !important;
    }
    
    .bg-info-subtle {
        background-color: rgba(13, 202, 240, 0.1) !important;
    }
    
    /* Animations */
    @keyframes float {
        0%, 100% { transform: translateY(0px) rotate(0deg); }
        50% { transform: translateY(-20px) rotate(180deg); }
    }
    
    @keyframes bounce {
        0%, 20%, 50%, 80%, 100% { transform: translateY(0); }
        40% { transform: translateY(-10px); }
        60% { transform: translateY(-5px); }
    }
    
    @keyframes pulse {
        0% { transform: scale(1); }
        50% { transform: scale(1.05); }
        100% { transform: scale(1); }
    }
    
    /* Responsive */
    @media (max-width: 768px) {
        .hero-title {
            font-size: 2rem;
        }
        
        .hero-section {
            padding: 2rem 0;
        }
        
        .calendar-legend {
            flex-direction: column;
            gap: 0.5rem;
        }
        
        .stat-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endsection