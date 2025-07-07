@extends('layouts.app')

@section('title', 'Selamat Datang di Sistem Reservasi Ruangan')

@section('content')
<div class="row g-4">
    <!-- Kolom Informasi Ruangan -->
    <div class="col-lg-7 col-12">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Informasi Ruangan</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <!-- Gambar ruangan - di mobile akan full width -->
                    <div class="col-md-5 col-12 mb-3 mb-md-0">
                        <img src="{{ asset('img/Lambang_Kabupaten_Purworejo.png') }}" class="img-fluid rounded w-100" alt="{{ $room->nama_ruangan }}">
                    </div>
                    
                    <!-- Detail ruangan - di mobile akan full width -->
                    <div class="col-md-7 col-12">
                        <h4 class="card-title fw-bold">{{ $room->nama_ruangan }}</h4>
                        <p class="card-text text-muted">{{ $room->deskripsi }}</p>
                        <hr>
                        <div class="row">
                            <!-- Kapasitas - di mobile akan full width -->
                            <div class="col-12 col-sm-6 mb-3 mb-sm-0">
                                <p class="mb-1"><i class="bi bi-people-fill me-2 text-primary"></i><strong>Kapasitas</strong></p>
                                <p>{{ $room->kapasitas }} orang</p>
                            </div>
                            
                            <!-- Fasilitas - di mobile akan full width -->
                            <div class="col-12 col-sm-6">
                                <p class="mb-1"><i class="bi bi-star-fill me-2 text-warning"></i><strong>Fasilitas</strong></p>
                                <ul class="list-unstyled">
                                    @foreach($room->fasilitas_array as $fasilitas)
                                        <li><small>{{ $fasilitas }}</small></li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Kolom Aksi Reservasi -->
    <div class="col-lg-5 col-12 mt-4 mt-lg-0">
        <div class="card h-100 bg-light border-0">
            <div class="card-body text-center d-flex flex-column justify-content-center py-4">
                <h5 class="card-title mb-3">Siap untuk Rapat?</h5>
                @auth
                    <p class="text-muted mb-3">Ruangan tersedia untuk reservasi. Klik tombol di bawah untuk memulai.</p>
                    <div class="d-grid">
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg py-2">
                            <i class="bi bi-plus-circle-fill me-2"></i>Buat Reservasi Baru
                        </a>
                    </div>
                @else
                    <p class="text-muted mb-3">Silakan login terlebih dahulu untuk dapat melakukan reservasi ruangan.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary py-2">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary py-2 mt-2">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<!-- Kalender Ketersediaan -->
<div class="card mt-4">
    <div class="card-header bg-white">
        <!-- Header difleksibelkan untuk mobile -->
        <div class="d-flex flex-column flex-md-row justify-content-between align-items-start align-items-md-center">
            <h5 class="mb-2 mb-md-0">Kalender Ketersediaan</h5>
            <div class="d-flex flex-wrap align-items-center mt-2 mt-md-0">
                <span class="badge bg-danger me-2 mb-1">Penuh</span>
                <span class="badge bg-light text-dark border me-2 mb-1">Tersedia</span>
                <small class="text-muted">Klik tanggal untuk detail jadwal</small>
            </div>
        </div>
    </div>
    <div class="card-body p-0">
        <!-- Wrapper untuk scroll horizontal di mobile -->
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
        var bookedDates = @json($bookedDates);
        
        var events = bookedDates.map(function(date) {
            return {
                title: 'Penuh',
                start: date,
                allDay: true,
                display: 'background',
                color: '#f8d7da'
            };
        });
        
        var reservationsDateRoute = "{{ route('reservations.date', ['date' => ':date']) }}";
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 'auto',
            events: events,
            // Responsif untuk ukuran layar kecil
            contentHeight: 'auto',
            aspectRatio: 1,
            dayCellDidMount: function(info) {
                info.el.setAttribute('data-bs-toggle', 'tooltip');
                info.el.setAttribute('data-bs-placement', 'top');
                info.el.setAttribute('title', 'Lihat Jadwal ' + info.date.toLocaleDateString('id-ID'));
            },
            dateClick: function(info) {
                var url = reservationsDateRoute.replace(':date', info.dateStr);
                window.location.href = url;
            },
        });
        
        calendar.render();

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
    /* Responsif untuk kalender */
    .fc .fc-toolbar {
        flex-direction: column;
        align-items: flex-start;
    }
    .fc-toolbar-chunk {
        margin-bottom: 10px;
    }
    @media (min-width: 768px) {
        .fc .fc-toolbar {
            flex-direction: row;
            align-items: center;
        }
    }
    
    .fc-daygrid-day-frame:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }
    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    
    /* Padding tambahan untuk mobile */
    @media (max-width: 767px) {
        .card-body {
            padding: 1.25rem;
        }
        .fc-header-toolbar {
            padding: 0 10px;
        }
    }
</style>
@endsection