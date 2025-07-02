@extends('layouts.app')

@section('title', 'Selamat Datang di Sistem Reservasi Ruangan')

@section('content')
<div class="row g-4">
    <div class="col-lg-7">
        <div class="card h-100">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">Informasi Ruangan</h5>
            </div>
            <div class="card-body">
                <div class="row g-4">
                    <div class="col-md-5">
                        <img src="{{ asset('img/Lambang_Kabupaten_Purworejo.png') }}" class="img-fluid rounded" alt="{{ $room->nama_ruangan }}">
                    </div>
                    <div class="col-md-7">
                        <h4 class="card-title fw-bold">{{ $room->nama_ruangan }}</h4>
                        <p class="card-text text-muted">{{ $room->deskripsi }}</p>
                        <hr>
                        <div class="row">
                            <div class="col-6">
                                <p class="mb-1"><i class="bi bi-people-fill me-2 text-primary"></i><strong>Kapasitas</strong></p>
                                <p>{{ $room->kapasitas }} orang</p>
                            </div>
                            <div class="col-6">
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

    <div class="col-lg-5">
        <div class="card h-100 bg-light border-0">
             <div class="card-body text-center d-flex flex-column justify-content-center">
                 <h5 class="card-title mb-3">Siap untuk Rapat?</h5>
                 @auth
                    <p class="text-muted">Ruangan tersedia untuk reservasi. Klik tombol di bawah untuk memulai.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary btn-lg">
                            <i class="bi bi-plus-circle-fill me-2"></i>Buat Reservasi Baru
                        </a>
                    </div>
                @else
                    <p class="text-muted">Silakan login terlebih dahulu untuk dapat melakukan reservasi ruangan.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                        <a href="{{ route('register') }}" class="btn btn-outline-secondary mt-2">Register</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header bg-white d-flex justify-content-between align-items-center">
        <h5 class="mb-0">Kalender Ketersediaan</h5>
        <small class="text-muted">
            <span class="badge bg-danger me-2">Penuh</span>
            <span class="badge bg-light text-dark border me-2">Tersedia</span>
            Klik pada tanggal untuk melihat detail jadwal.
        </small>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
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
                color: '#f8d7da' // Warna merah muda untuk menandai hari penuh
            };
        });
        
        var reservationsDateRoute = "{{ route('reservations.date', ['date' => ':date']) }}";
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 'auto',
            events: events,
            dayCellDidMount: function(info) {
                // Tambahkan tooltip
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

        // Inisialisasi tooltip Bootstrap
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
    .fc-daygrid-day-frame:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }
    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
</style>
@endsection