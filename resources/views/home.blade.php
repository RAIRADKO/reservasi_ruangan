@extends('layouts.app')

@section('content')
<div class="row">
    <div class="col-md-8">
        <div class="card mb-4">
            <div class="card-header">
                Informasi Ruangan
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-4">
                        <img src="{{ $room->foto_url }}" class="img-fluid rounded" alt="{{ $room->nama_ruangan }}">
                    </div>
                    <div class="col-md-8">
                        <h5 class="card-title">{{ $room->nama_ruangan }}</h5>
                        <p class="card-text">{{ $room->deskripsi }}</p>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item"><strong>Kapasitas:</strong> {{ $room->kapasitas }} orang</li>
                            <li class="list-group-item"><strong>Fasilitas:</strong>
                                <ul>
                                    @foreach($room->fasilitas_array as $fasilitas)
                                        <li>{{ $fasilitas }}</li>
                                    @endforeach
                                </ul>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card">
            <div class="card-header">
                Buat Reservasi
            </div>
            <div class="card-body">
                @auth
                    <p>Silakan isi form untuk melakukan reservasi ruangan.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('reservations.create') }}" class="btn btn-primary">Buat Reservasi</a>
                    </div>
                @else
                    <p>Silakan login untuk melakukan reservasi ruangan.</p>
                    <div class="d-grid gap-2">
                        <a href="{{ route('login') }}" class="btn btn-primary">Login</a>
                    </div>
                @endauth
            </div>
        </div>
    </div>
</div>

<div class="card mt-4">
    <div class="card-header d-flex justify-content-between align-items-center">
        <span>Kalender Ketersediaan</span>
        <small class="text-muted">Klik tanggal untuk melihat detail reservasi</small>
    </div>
    <div class="card-body">
        <div id="calendar"></div>
    </div>
</div>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/locales/id.min.js"></script>
<script>
    document.addEventListener('DOMContentLoaded', function() {
        var calendarEl = document.getElementById('calendar');
        var bookedDates = @json($bookedDates);
        
        var events = bookedDates.map(function(date) {
            return {
                title: 'Penuh',
                start: date,
                allDay: true,
                backgroundColor: '#dc3545',
                borderColor: '#dc3545'
            };
        });
        
        // Perbaikan di sini: Simpan URL route dengan placeholder
        var reservationsDateRoute = "{{ route('reservations.date', ['date' => ':date']) }}";
        
        var calendar = new FullCalendar.Calendar(calendarEl, {
            initialView: 'dayGridMonth',
            locale: 'id',
            height: 'auto',
            events: events,
            dateClick: function(info) {
                // Ganti placeholder dengan tanggal sebenarnya
                var url = reservationsDateRoute.replace(':date', info.dateStr);
                window.location.href = url;
            },
            eventClick: function(info) {
                // Ganti placeholder dengan tanggal sebenarnya
                var url = reservationsDateRoute.replace(':date', info.event.startStr);
                window.location.href = url;
            }
        });
        
        calendar.render();
    });
</script>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.10.1/main.min.css" rel="stylesheet">
<style>
    .fc-daygrid-event {
        cursor: pointer;
        font-size: 0.9em;
        padding: 2px 4px;
    }
    .fc-event-title {
        font-weight: bold;
    }
    .fc-day-today {
        background-color: rgba(13, 110, 253, 0.1) !important;
    }
    .fc-daygrid-day:hover {
        background-color: #f8f9fa;
    }
</style>
@endsection
