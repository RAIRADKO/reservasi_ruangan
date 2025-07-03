@extends('layouts.admin')

@section('title', 'Manajemen Kalender')

@section('content')
<div class="container">
    <h2>Manajemen Kalender Ketersediaan</h2>
    <p class="text-muted">Klik pada tanggal untuk memblokir atau membuka blokir. Tanggal yang diblokir akan tampil sebagai "Penuh" di halaman utama.</p>

    <div class="alert alert-info">
        <i class="bi bi-info-circle-fill"></i>
        Tanggal yang sudah memiliki reservasi dan ditandai "Penuh" secara otomatis tidak akan terpengaruh oleh fitur blokir manual ini.
    </div>

    <div id="calendar-management" class="mt-4"></div>
</div>
@endsection

@section('styles')
<link href="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.css" rel="stylesheet">
<style>
    .fc-daygrid-day-frame:hover {
        background-color: #e9ecef;
        cursor: pointer;
    }
    .fc-day-blocked {
        background-color: #dc3545 !important;
        color: white;
    }
    .fc-day-blocked a {
        color: white !important;
    }
</style>
@endsection

@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/fullcalendar@5.11.3/main.min.js"></script>
<script>
document.addEventListener('DOMContentLoaded', function() {
    var calendarEl = document.getElementById('calendar-management');
    var manuallyBlockedDates = @json($blockedDates);

    function getEventSources() {
        return [{
            events: manuallyBlockedDates.map(function(date) {
                return {
                    title: 'Manual Block',
                    start: date,
                    allDay: true,
                    display: 'background',
                    color: '#dc3545'
                };
            }),
        }];
    }

    var calendar = new FullCalendar.Calendar(calendarEl, {
        initialView: 'dayGridMonth',
        height: 'auto',
        eventSources: getEventSources(),
        dateClick: function(info) {
            const dateStr = info.dateStr;
            const isBlocked = manuallyBlockedDates.includes(dateStr);
            const url = isBlocked ? '{{ route("admin.blocked-dates.destroy") }}' : '{{ route("admin.blocked-dates.store") }}';
            const method = isBlocked ? 'DELETE' : 'POST';

            if (!confirm(`Apakah Anda yakin ingin ${isBlocked ? 'MEMBUKA BLOKIR' : 'MEMBLOKIR'} tanggal ${dateStr}?`)) {
                return;
            }

            fetch(url, {
                method: method,
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ date: dateStr })
            })
            .then(response => response.json())
            .then(data => {
                if (data.error) {
                    alert('Error: ' + data.error);
                } else {
                    alert(data.message);
                    if (isBlocked) {
                        manuallyBlockedDates = manuallyBlockedDates.filter(d => d !== dateStr);
                    } else {
                        manuallyBlockedDates.push(dateStr);
                    }
                    calendar.removeAllEventSources();
                    calendar.addEventSource(getEventSources()[0]);
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Terjadi kesalahan. Silakan coba lagi.');
            });
        }
    });

    calendar.render();
});
</script>
@endsection