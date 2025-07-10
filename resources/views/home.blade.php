@extends('layouts.app')

@section('title', 'Selamat Datang di Sistem Reservasi Ruangan')

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    :root {
        --primary: #0d6efd;
        --secondary: #6c757d;
        --success: #198754;
        --info: #0dcaf0;
        --warning: #ffc107;
        --danger: #dc3545;
        --light: #f8f9fa;
        --dark: #212529;
        --purple: #6f42c1;
    }

    .slideshow-card {
        position: relative;
        overflow: hidden;
    }

    .slideshow-card .carousel,
    .slideshow-card .carousel-inner,
    .slideshow-card .carousel-item,
    .slideshow-card .carousel-item img {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        object-fit: cover;
        z-index: 1;
    }

    .slideshow-card .overlay {
        position: absolute;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(15, 23, 42, 0.7);
        z-index: 2;
    }

    .slideshow-card .card-body {
        position: relative;
        z-index: 3;
    }
    
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%) !important;
        border: 3px solid var(--secondary) !important; 
    }
    
    .bg-purple {
        background-color: var(--purple) !important;
    }
    
    .icon-wrapper {
        transition: all 0.3s ease;
    }
    
    .card {
        border-radius: 12px;
        overflow: hidden;
        transition: transform 0.3s ease, box-shadow 0.3s ease;
        border: 3px solid #0d6efd !important; 
    }
    
    .card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 20px rgba(0,0,0,0.1);
    }
    
    .card-header {
        border-radius: 12px 12px 0 0 !important;
    }
    
    .fc-event-booked {
        background-color: rgba(255, 193, 7, 0.7) !important; 
        border: none;
    }
    
    .fc-event-full {
        background-color: rgba(220, 53, 69, 0.7) !important;
        border: none;
    }
    
    .fc-event-not-available {
        background-color: rgba(108, 117, 125, 0.7) !important;
        border: none;
    }
    
    .fc-event-available {
        background-color: rgba(25, 135, 84, 0.7) !important;
        border: none;
    }
    
    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.15) !important;
        border: 2px solid var(--primary) !important;
    }
    
    .fc-daygrid-day-frame {
        transition: background-color 0.2s ease;
        border-radius: 6px;
        margin: 2px;
    }
    
    .fc-daygrid-day-frame:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }
    
    .fc .fc-button {
        border-radius: 8px !important;
        padding: 6px 12px;
    }
    
    .fc .fc-toolbar-title {
        font-size: 1.25rem;
        font-weight: 600;
    }
    
    .stat-card {
        transition: all 0.3s ease;
        border-radius: 10px;
        border: 1px solid #dee2e6;
    }
    
    .stat-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 5px 15px rgba(0,0,0,0.05);
        border-color: var(--primary);
    }
    
    @media (max-width: 767px) {
        .card-body {
            padding: 1.25rem;
        }
        
        .fc-toolbar-title {
            font-size: 1.1rem;
        }
        
        .fc-button {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .fc .fc-toolbar.fc-header-toolbar {
            flex-direction: column;
            gap: 10px;
        }
        
        .fc-header-toolbar .fc-toolbar-chunk {
            display: flex;
            justify-content: center;
            width: 100%;
        }
    }
    
    .guide-step {
        transition: all 0.3s ease;
    }
    
    .guide-step:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 15px rgba(0,0,0,0.08);
    }
    
    .list-group-item {
        border-left: none;
        border-right: none;
        padding: 0.75rem 0;
        transition: background-color 0.2s ease;
    }
    
    .list-group-item:last-child {
        border-bottom: none;
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    
    .btn-lg {
        border-radius: 50px;
        padding: 0.75rem 1.5rem;
    }
    
    .btn-outline-light:hover {
        color: var(--primary) !important;
        background-color: white;
    }
    
    .badge {
        border-radius: 50px;
        padding: 0.5em 0.9em;
        font-weight: 500;
    }
    
    .fc .fc-col-header-cell-cushion {
        font-weight: 600;
        color: var(--dark);
    }
    
    .fc .fc-daygrid-day-number {
        font-weight: 500;
    }
    
    .fc-event-booked .fc-daygrid-day-number,
    .fc-event-full .fc-daygrid-day-number,
    .fc-event-not-available .fc-daygrid-day-number,
    .fc-event-available .fc-daygrid-day-number {
        color: #000 !important;
        font-weight: 600;
    }
    
    #calendar {
        min-height: 400px;
    }
</style>
@endsection

@section('content')
<div class="row g-4">
    <div class="col-lg-7 col-12">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    Tentang CommandBook
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4 align-items-center">
                    <div class="col-md-5 col-12 mb-3 mb-md-0">
                        <div class="text-center p-3 bg-light rounded">
                            <img src="{{ asset('img/Lambang_Kabupaten_Purworejo.png') }}" 
                                 class="img-fluid rounded" 
                                 alt="Logo Kabupaten Purworejo"
                                 style="max-height: 180px; width: auto;">
                        </div>
                    </div>
                    
                    <div class="col-md-7 col-12">
                        <h4 class="card-title fw-bold text-primary mb-3">CommandBook</h4>
                        <p class="card-text mb-4">
                            Aplikasi digital untuk memudahkan reservasi ruangan rapat di lingkungan 
                            Pemerintah Kabupaten Purworejo dengan sistem yang terintegrasi dan efisien.
                        </p>
                        
                        <div class="bg-light p-3 rounded">
                            <h6 class="fw-bold mb-3 d-flex align-items-center">
                                <i class="bi bi-stars me-2 text-warning"></i>
                                Fitur Utama
                            </h6>
                            <div class="row g-2">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                        <span>Reservasi online 24/7</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                        <span>Manajemen multi-instansi</span>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                        <span>Kalender real-time</span>
                                    </div>
                                    <div class="d-flex align-items-center mb-2">
                                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                        <span>Notifikasi otomatis</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 col-12 mt-4 mt-lg-0">
        <div class="card h-100 border-0 shadow-sm slideshow-card">
            <div id="roomSlideshow" class="carousel slide carousel-fade" data-bs-ride="carousel">
                <div class="carousel-inner">
                    @foreach($rooms as $index => $roomPhoto)
                        @if($roomPhoto->foto_url)
                            <div class="carousel-item {{ $index == 0 ? 'active' : '' }}" data-bs-interval="3000">
                                <img src="{{ $roomPhoto->foto_url }}" alt="Foto Ruangan {{ $index + 1 }}">
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
            <div class="overlay"></div>
            <div class="card-body text-center d-flex flex-column justify-content-center py-4 px-3">
                <div class="mb-4">
                    <i class="bi bi-calendar-check text-white display-5"></i>
                    <h5 class="card-title text-white mb-3">Mulai Reservasi Ruangan</h5>
                </div>
                
                @auth
                    <p class="mb-4 text-white text-opacity-85">
                        Halo, <strong class="text-white">{{ Auth::user()->name }}</strong>!<br>
                        Siap untuk melakukan reservasi ruangan?
                    </p>
                                        
                    <div class="d-grid mb-3">
                        <a href="{{ route('reservations.create') }}" class="btn btn-light btn-lg py-2 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            Buat Reservasi Baru
                        </a>
                    </div>
                    
                    <div class="mt-2">
                        <a href="{{ route('user.reservations') }}" class="btn btn-outline-light btn-sm rounded-pill">
                            <i class="bi bi-clock-history me-1"></i>
                            Lihat Riwayat Reservasi
                        </a>
                    </div>
                @else
                    <p class="mb-4 text-white text-opacity-85">
                        Silakan login terlebih dahulu untuk dapat melakukan reservasi ruangan.
                    </p>
                    
                    <div class="bg-white bg-opacity-10 rounded p-3 mb-4">
                        <h6 class="mb-3 text-white">Mengapa Menggunakan Sistem Ini?</h6>
                        <div class="d-flex flex-wrap justify-content-center gap-2">
                            <span class="badge bg-gray bg-opacity-20 text-white py-2">
                                <i class="bi bi-shield-check me-1"></i>Aman
                            </span>
                            <span class="badge bg-gray bg-opacity-20 text-white py-2">
                                <i class="bi bi-lightning me-1"></i>Cepat
                            </span>
                            <span class="badge bg-gray bg-opacity-20 text-white py-2">
                                <i class="bi bi-phone me-1"></i>Akses Mobile
                            </span>
                        </div>
                    </div>
                    
                    <div class="d-grid gap-3">
                        <a href="{{ route('login') }}" class="btn btn-light py-2 fw-bold rounded-pill shadow-sm">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login Sekarang
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light py-2 rounded-pill">
                            <i class="bi bi-person-plus me-2"></i>
                            Daftar Akun Baru
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="row mt-4 g-4">
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-people me-2 text-info"></i>
                    Statistik Pengunjung
                </h5>
            </div>
            <div class="card-body">
                <div class="row text-center g-3">
                    <div class="col-md-4 col-12">
                        <div class="border rounded p-3 bg-light h-100">
                            <div class="text-primary mb-2">
                                <i class="bi bi-sun display-6"></i>
                            </div>
                            <h3 class="text-primary fw-bold">{{ $todayVisitors }}</h3>
                            <p class="mb-0">Hari Ini</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="border rounded p-3 bg-light h-100">
                            <div class="text-success mb-2">
                                <i class="bi bi-calendar display-6"></i>
                            </div>
                            <h3 class="text-success fw-bold">{{ $monthlyVisitors }}</h3>
                            <p class="mb-0">Bulan Ini</p>
                        </div>
                    </div>
                    <div class="col-md-4 col-12">
                        <div class="border rounded p-3 bg-light h-100">
                            <div class="text-info mb-2">
                                <i class="bi bi-graph-up display-6"></i>
                            </div>
                            <h3 class="text-info fw-bold">{{ $totalVisitors }}</h3>
                            <p class="mb-0">Total</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Today's Events - Only show when logged in --}}
    @auth
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-calendar-event me-2 text-success"></i>
                    Acara Hari Ini ({{ now()->format('d F Y') }})
                </h5>
            </div>
            <div class="card-body">
                @if(count($todayEvents) > 0)
                    <div class="list-group">
                        @foreach($todayEvents as $event)
                            <div class="list-group-item border-0 py-2 px-0">
                                <div class="d-flex align-items-center">
                                    <div class="bg-info text-white rounded-circle d-flex align-items-center justify-content-center me-3" style="width: 36px; height: 36px;">
                                        <i class="bi bi-calendar-check"></i>
                                    </div>
                                    <div>
                                        <h6 class="mb-0 fw-bold">{{ $event->roomInfo->nama_ruangan }}</h6>
                                        <small class="text-muted">
                                            {{ date('H:i', strtotime($event->jam_mulai)) }} - {{ date('H:i', strtotime($event->jam_selesai)) }}
                                        </small>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-calendar-x display-4 text-muted mb-3"></i>
                        <p class="text-muted mb-0">Tidak ada acara yang dijadwalkan hari ini</p>
                    </div>
                @endif
            </div>
        </div>
    </div>
    @else
    {{-- Additional content for non-logged in users --}}
    <div class="col-md-6">
        <div class="card h-100 shadow-sm">
            <div class="card-header bg-white py-3 border-bottom">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-shield-check me-2 text-success"></i>
                    Keamanan & Privasi
                </h5>
            </div>
            <div class="card-body">
                <div class="text-center py-3">
                    <i class="bi bi-shield-lock display-4 text-success mb-3"></i>
                    <h6 class="fw-bold mb-3">Data Anda Aman</h6>
                    <p class="text-muted mb-4">
                        Sistem CommandBook menggunakan enkripsi tingkat tinggi untuk melindungi data dan informasi reservasi Anda.
                    </p>
                    <div class="row g-3">
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <i class="bi bi-lock text-success mb-2"></i>
                                <small class="d-block text-muted">SSL Encryption</small>
                            </div>
                        </div>
                        <div class="col-6">
                            <div class="bg-light p-3 rounded">
                                <i class="bi bi-eye-slash text-success mb-2"></i>
                                <small class="d-block text-muted">Private Access</small>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endauth
</div>

<div class="card mt-4 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h5 class="mb-2 mb-md-0 fw-bold">
                <i class="bi bi-question-circle me-2 text-info"></i>
                Panduan Penggunaan
            </h5>
            <small class="text-muted">Langkah-langkah menggunakan sistem</small>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4 justify-content-center">
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center p-3 bg-light rounded h-100">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-5">1</span>
                    </div>
                    <h6 class="fw-bold mb-2">Login</h6>
                    <p class="text-muted small mb-0">Masuk ke akun Anda</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center p-3 bg-light rounded h-100">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-5">2</span>
                    </div>
                    <h6 class="fw-bold mb-2">Pilih Tanggal</h6>
                    <p class="text-muted small mb-0">Cari tanggal tersedia</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center p-3 bg-light rounded h-100">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-5">3</span>
                    </div>
                    <h6 class="fw-bold mb-2">Isi Form</h6>
                    <p class="text-muted small mb-0">Lengkapi data reservasi</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center p-3 bg-light rounded h-100">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-5">4</span>
                    </div>
                    <h6 class="fw-bold mb-2">Konfirmasi</h6>
                    <p class="text-muted small mb-0">Tunggu konfirmasi</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center p-3 bg-light rounded h-100">
                    <div class="bg-purple text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-5">5</span>
                    </div>
                    <h6 class="fw-bold mb-2">Check Out</h6>
                    <p class="text-muted small mb-0">Setelah selesai</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center p-3 bg-light rounded h-100">
                    <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-3" 
                         style="width: 60px; height: 60px;">
                        <span class="fw-bold fs-5">6</span>
                    </div>
                    <h6 class="fw-bold mb-2">Survey</h6>
                    <p class="text-muted small mb-0">Berikan penilaian</p>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Calendar - Only show when logged in --}}
@auth
<div class="card mt-4 shadow-sm">
    <div class="card-header bg-white py-3 border-bottom">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h5 class="mb-2 mb-md-0 fw-bold">
                <i class="bi bi-calendar3 me-2 text-primary"></i>
                Kalender Ketersediaan
            </h5>
            <div class="d-flex flex-wrap align-items-center gap-2 mt-2 mt-md-0">
                <span class="badge bg-danger">
                    <i class="bi bi-x-circle me-1"></i>Penuh
                </span>
                <span class="badge bg-secondary">
                    <i class="bi bi-x-circle me-1"></i>Tidak Tersedia
                </span>
                <span class="badge bg-warning">
                    <i class="bi bi-check-circle me-1"></i>Terisi
                </span>
                <span class="badge bg-success">
                    <i class="bi bi-check-circle me-1"></i>Tersedia
                </span>
            </div>
        </div>
    </div>
    
    <div class="card-body p-0">
        <div class="overflow-auto p-2">
            <div id="calendar" style="min-width: 300px;"></div>
        </div>
    </div>
</div>
@endauth
@endsection

@section('scripts')
@auth
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var reservationDates = @json($reservationDates ?? []);
        var manualBlockedDates = @json($manualBlockedDates ?? []);
        var fullDates = @json($fullDates ?? []);
        var availableDates = @json($availableDates ?? []);
        
        var events = reservationDates.map(function(date) {
            return {
                title: 'Ada Reservasi',
                start: date,
                allDay: true,
                display: 'background',
                className: 'fc-event-booked'
            };
        });

        manualBlockedDates.forEach(function(date) {
            events.push({
                title: 'Tidak Tersedia',
                start: date,
                allDay: true,
                display: 'background',
                className: 'fc-event-not-available'
            });
        });

        fullDates.forEach(function(date) {
            events.push({
                title: 'Penuh',
                start: date,
                allDay: true,
                display: 'background',
                className: 'fc-event-full'
            });
        });

        availableDates.forEach(function(date) {
            events.push({
                title: 'Tersedia',
                start: date,
                allDay: true,
                display: 'background',
                className: 'fc-event-available'
            });
        });
        
        var reservationsDateRoute = "{{ route('reservations.date', ['date' => ':date']) }}";
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 'auto',
            events: events,
            contentHeight: 'auto',
            aspectRatio: window.innerWidth < 768 ? 1 : 1.5,
            headerToolbar: {
                left: 'prev,next today',
                center: 'title',
                right: 'dayGridMonth'
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
            dayMaxEvents: window.innerWidth < 768 ? 2 : 3,
            moreLinkClick: 'popover'
        });
        
        calendar.render();

        window.addEventListener('resize', function() {
            calendar.setOption('aspectRatio', window.innerWidth < 768 ? 1 : 1.5);
            calendar.setOption('dayMaxEvents', window.innerWidth < 768 ? 2 : 3);
        });

        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endauth
@endsection