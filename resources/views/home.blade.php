@extends('layouts.app')

@section('title', 'Selamat Datang di Sistem Reservasi Ruangan')

@section('content')
<div class="row g-4">
    <div class="col-lg-7 col-12">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-info-circle me-2 text-primary"></i>
                    Tentang Sistem Elektronik Reservasi Ruangan
                </h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-5 col-12 mb-3 mb-md-0">
                        <div class="text-center">
                            <img src="{{ asset('img/Lambang_Kabupaten_Purworejo.png') }}" 
                                 class="img-fluid rounded" 
                                 alt="Logo Kabupaten Purworejo"
                                 style="max-height: 200px; width: auto;">
                        </div>
                    </div>
                    
                    <div class="col-md-7 col-12">
                        <h4 class="card-title fw-bold text-primary">Sistem Elektronik Reservasi Ruangan</h4>
                        <p class="card-text text-muted mb-3">
                            Aplikasi digital untuk memudahkan reservasi ruangan rapat di lingkungan 
                            Pemerintah Kabupaten Purworejo dengan sistem yang terintegrasi dan efisien.
                        </p>
                        
                        <div class="row g-3">
                            <div class="col-12">
                                <h6 class="fw-bold mb-2">
                                    <i class="bi bi-star-fill me-2 text-warning"></i>
                                    Fitur Utama
                                </h6>
                                <ul class="list-unstyled">
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                        <small>Reservasi online 24/7</small>
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                        <small>Kalender ketersediaan real-time</small>
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                        <small>Manajemen multi-instansi</small>
                                    </li>
                                    <li class="mb-1">
                                        <i class="bi bi-check-circle-fill me-2 text-success"></i>
                                        <small>Notifikasi otomatis</small>
                                    </li>
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-5 col-12 mt-4 mt-lg-0">
        <div class="card h-100 bg-gradient-primary text-white border-0">
            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                <div class="mb-3">
                    <i class="bi bi-calendar-check display-4 mb-3"></i>
                    <h5 class="card-title mb-3">Mulai Reservasi Ruangan</h5>
                </div>
                
                @auth
                    <p class="mb-3 opacity-75">
                        Halo, <strong>{{ Auth::user()->name }}</strong>!<br>
                        Siap untuk melakukan reservasi ruangan?
                    </p>
                    
                    <div class="bg-white bg-opacity-10 rounded p-3 mb-3">
                        <h6 class="mb-2">Ruangan Tersedia</h6>
                        <div class="d-flex align-items-center justify-content-center">
                            <i class="bi bi-door-open-fill me-2"></i>
                            <span>{{ $room->nama_ruangan }}</span>
                        </div>
                        <small class="opacity-75">Kapasitas: {{ $room->kapasitas }} orang</small>
                    </div>
                    
                    <div class="d-grid">
                        <a href="{{ route('reservations.create') }}" class="btn btn-light btn-lg py-2">
                            <i class="bi bi-plus-circle-fill me-2"></i>
                            Buat Reservasi Baru
                        </a>
                    </div>
                    
                    <div class="mt-3">
                        <a href="{{ route('user.reservations') }}" class="btn btn-outline-light btn-sm">
                            <i class="bi bi-clock-history me-1"></i>
                            Lihat Riwayat Reservasi
                        </a>
                    </div>
                @else
                    <p class="mb-3 opacity-75">
                        Silakan login terlebih dahulu untuk dapat melakukan reservasi ruangan.
                    </p>
                    
                    <div class="bg-white bg-opacity-10 rounded p-3 mb-3">
                        <h6 class="mb-2">Mengapa Menggunakan Sistem Ini?</h6>
                        <ul class="list-unstyled text-start">
                            <li class="mb-1">
                                <i class="bi bi-shield-check me-2"></i>
                                <small>Aman dan terpercaya</small>
                            </li>
                            <li class="mb-1">
                                <i class="bi bi-lightning-charge me-2"></i>
                                <small>Cepat dan mudah</small>
                            </li>
                            <li class="mb-1">
                                <i class="bi bi-phone me-2"></i>
                                <small>Akses dari mana saja</small>
                            </li>
                        </ul>
                    </div>
                    
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-light py-2">
                            <i class="bi bi-box-arrow-in-right me-2"></i>
                            Login
                        </a>
                        <a href="{{ route('register') }}" class="btn btn-outline-light py-2">
                            <i class="bi bi-person-plus me-2"></i>
                            Daftar Akun Baru
                        </a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h5 class="mb-2 mb-md-0">
                <i class="bi bi-question-circle me-2 text-info"></i>
                Panduan Penggunaan
            </h5>
            <small class="text-muted">Cara menggunakan sistem reservasi</small>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-4">
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="bg-primary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                         style="width: 50px; height: 50px;">
                        <span class="fw-bold">1</span>
                    </div>
                    <h6 class="fw-bold">Login</h6>
                    <p class="text-muted small">Masuk ke akun Anda atau daftar jika belum punya</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="bg-success text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                         style="width: 50px; height: 50px;">
                        <span class="fw-bold">2</span>
                    </div>
                    <h6 class="fw-bold">Pilih Tanggal</h6>
                    <p class="text-muted small">Lihat kalender dan pilih tanggal yang tersedia</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="bg-warning text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                         style="width: 50px; height: 50px;">
                        <span class="fw-bold">3</span>
                    </div>
                    <h6 class="fw-bold">Isi Form</h6>
                    <p class="text-muted small">Lengkapi informasi reservasi Anda</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="bg-info text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                         style="width: 50px; height: 50px;">
                        <span class="fw-bold">4</span>
                    </div>
                    <h6 class="fw-bold">Konfirmasi</h6>
                    <p class="text-muted small">Tunggu konfirmasi dan gunakan ruangan</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="bg-secondary text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                         style="width: 50px; height: 50px;">
                        <span class="fw-bold">5</span>
                    </div>
                    <h6 class="fw-bold">Check Out</h6>
                    <p class="text-muted small">Lakukan check out setelah selesai menggunakan ruangan</p>
                </div>
            </div>
            
            <div class="col-lg-2 col-md-4 col-6">
                <div class="text-center">
                    <div class="bg-dark text-white rounded-circle d-inline-flex align-items-center justify-content-center mb-2" 
                         style="width: 50px; height: 50px;">
                        <span class="fw-bold">6</span>
                    </div>
                    <h6 class="fw-bold">Isi Survey</h6>
                    <p class="text-muted small">Berikan penilaian dan feedback untuk meningkatkan layanan</p>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white">
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h5 class="mb-2 mb-md-0">
                <i class="bi bi-calendar3 me-2 text-primary"></i>
                Kalender Ketersediaan
            </h5>
            <div class="d-flex flex-wrap align-items-center mt-2 mt-md-0">
                <span class="badge bg-danger me-2 mb-1">
                    <i class="bi bi-x-circle me-1"></i>Penuh
                </span>
                <span class="badge bg-secondary me-2 mb-1">
                    <i class="bi bi-x-circle me-1"></i>Not Available
                </span>
                <span class="badge bg-warning me-2 mb-1">
                    <i class="bi bi-check-circle me-1"></i>Ada Reservasi
                </span>
                <span class="badge bg-success me-2 mb-1">
                    <i class="bi bi-check-circle me-1"></i>Tersedia
                </span>
                <small class="text-muted">Klik tanggal untuk detail jadwal</small>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <div class="overflow-auto">
            <div id="calendar" class="p-2" style="min-width: 300px;"></div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/locales/id.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var reservationDates = @json($reservationDates ?? []);
        var manualBlockedDates = @json($manualBlockedDates ?? []);
        var fullDates = @json($fullDates ?? []);
        
        var events = reservationDates.map(function(date) {
            return {
                title: 'Ada Reservasi',
                start: date,
                allDay: true,
                display: 'background',
                color: '#fff3cd'
            };
        });

        manualBlockedDates.forEach(function(date) {
            events.push({
                title: 'Not Available',
                start: date,
                allDay: true,
                display: 'background',
                color: '#e9ecef'
            });
        });

        fullDates.forEach(function(date) {
            events.push({
                title: 'Penuh',
                start: date,
                allDay: true,
                display: 'background',
                color: '#f8d7da'
            });
        });
        
        var reservationsDateRoute = "{{ route('reservations.date', ['date' => ':date']) }}";
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 'auto',
            events: events,
            // Responsif untuk ukuran layar kecil
            contentHeight: 'auto',
            aspectRatio: window.innerWidth < 768 ? 1.2 : 1.35,
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
            // Responsif untuk mobile
            dayMaxEvents: window.innerWidth < 768 ? 2 : 3,
            moreLinkClick: 'popover'
        });
        
        calendar.render();

        // Responsive calendar adjustment
        window.addEventListener('resize', function() {
            calendar.setOption('aspectRatio', window.innerWidth < 768 ? 1.2 : 1.35);
            calendar.setOption('dayMaxEvents', window.innerWidth < 768 ? 2 : 3);
        });

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
    /* Gradient background untuk card aksi */
    .bg-gradient-primary {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }
    
    /* Responsif untuk kalender */
    .fc .fc-toolbar {
        flex-direction: column;
        align-items: flex-start;
        gap: 10px;
    }
    
    .fc-toolbar-chunk {
        margin-bottom: 5px;
    }
    
    @media (min-width: 768px) {
        .fc .fc-toolbar {
            flex-direction: row;
            align-items: center;
            gap: 0;
        }
        
        .fc-toolbar-chunk {
            margin-bottom: 0;
        }
    }
    
    .fc-daygrid-day-frame:hover {
        background-color: #e9ecef;
        cursor: pointer;
        transition: background-color: 0.2s ease;
    }
    
    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    /* Styling untuk statistik */
    .border {
        border: 1px solid #dee2e6 !important;
        transition: all 0.2s ease;
    }
    
    .border:hover {
        border-color: #0d6efd !important;
        box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
    }
    
    /* Padding tambahan untuk mobile */
    @media (max-width: 767px) {
        .card-body {
            padding: 1rem;
        }
        
        .fc-header-toolbar {
            padding: 0 10px;
        }
        
        .fc-toolbar-title {
            font-size: 1.1rem;
        }
        
        .fc-button {
            padding: 0.2rem 0.4rem;
            font-size: 0.875rem;
        }
        
        /* Adjust panduan penggunaan untuk mobile */
        .col-6 .text-center p,
        .col-lg-2 .text-center p {
            font-size: 0.75rem;
        }
        
        /* Responsive grid untuk panduan penggunaan */
        @media (max-width: 991px) {
            .col-lg-2 {
                margin-bottom: 1rem;
            }
        }
    }
    
    /* Animasi hover untuk card */
    .card {
        transition: all 0.3s ease;
    }
    
    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
    }
    
    /* Styling untuk badge dengan ikon */
    .badge {
        font-size: 0.75rem;
        padding: 0.5em 0.75em;
    }
    
    /* Responsive text untuk mobile */
    @media (max-width: 575px) {
        .display-4 {
            font-size: 2rem;
        }
        
        .h5 {
            font-size: 1.1rem;
        }
        
        .small {
            font-size: 0.75rem;
        }
    }
</style>
@endsection