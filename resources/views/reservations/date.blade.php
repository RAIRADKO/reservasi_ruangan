@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-md-row justify-content-between align-items-center bg-gradient-primary text-white p-3 p-md-4 rounded-3 shadow-sm">
                <div class="mb-3 mb-md-0 text-center text-md-start">
                    <h1 class="mb-1 fw-bold h4 h-md-3">
                        <i class="bi bi-calendar-event me-2"></i>
                        Reservasi pada Tanggal
                    </h1>
                    <p class="mb-0 fs-5 opacity-90">
                        {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}
                    </p>
                    @if(isset($room))
                        <small class="opacity-75">
                            <i class="bi bi-door-open me-1"></i>
                            Ruangan: {{ $room->nama_ruangan }}
                        </small>
                    @endif
                </div>
                <a href="{{ route('home') }}" class="btn btn-light btn-sm btn-md-lg shadow-sm mt-2 mt-md-0">
                    <i class="bi bi-arrow-left me-2"></i>
                    Kembali ke Kalender
                </a>
            </div>
        </div>
    </div>

    <!-- Room Filter -->
    @if(isset($rooms) && $rooms->count() > 1)
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="row align-items-center">
                        <div class="col-12 col-md-3 mb-2 mb-md-0">
                            <label for="room-filter" class="form-label fw-bold">
                                <i class="bi bi-funnel me-2"></i>Filter Ruangan:
                            </label>
                        </div>
                        <div class="col-12 col-md-9">
                            <select id="room-filter" class="form-select" onchange="filterByRoom()">
                                <option value="{{ route('reservations.date', ['date' => $date]) }}">Semua Ruangan</option>
                                @foreach($rooms as $roomOption)
                                    <option 
                                        value="{{ route('reservations.date.room', ['date' => $date, 'room' => $roomOption->id]) }}" 
                                        {{ (isset($room) && $room->id == $roomOption->id) ? 'selected' : '' }}>
                                        {{ $roomOption->nama_ruangan }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Room Status Summary -->
    @if(!isset($room) && $reservations->isNotEmpty())
    <div class="row mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-bottom-0 py-3">
                    <h4 class="mb-0 fw-bold h5">
                        <i class="bi bi-door-closed me-2 text-primary"></i>
                        Status Ruangan
                    </h4>
                </div>
                <div class="card-body">
                    <div class="row">
                        @php
                            $roomReservations = $reservations->groupBy('room_info_id');
                            $allRooms = $rooms ?? collect();
                            $workHoursStart = '08:00:00';
                            $workHoursEnd = '16:00:00';
                        @endphp
                        
                        @foreach($allRooms as $roomItem)
                        @php
                            $reservationsForRoom = $roomReservations->get($roomItem->id, collect());
                            
                            // Hitung status ruangan
                            $status = 'available';
                            $statusText = 'Tersedia';
                            $icon = 'bi-door-open-fill';
                            $color = 'success';
                            
                            if ($reservationsForRoom->isNotEmpty()) {
                                $minStart = $reservationsForRoom->min('jam_mulai');
                                $maxEnd = $reservationsForRoom->max('jam_selesai');
                                
                                if ($minStart <= $workHoursStart && $maxEnd >= $workHoursEnd) {
                                    $status = 'full';
                                    $statusText = 'Penuh';
                                    $icon = 'bi-door-closed-fill';
                                    $color = 'danger';
                                } else {
                                    $status = 'partial';
                                    $statusText = 'Sebagian';
                                    $icon = 'bi-door-closed';
                                    $color = 'warning';
                                }
                            }
                        @endphp
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="room-status-card p-3 rounded-3 h-100 
                                bg-{{ $color }}-subtle border-{{ $color }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $roomItem->nama_ruangan }}</h6>
                                        <small class="text-{{ $color }} fw-bold">
                                            <i class="bi {{ $icon }} me-1"></i>
                                            @if($status === 'full')
                                                Penuh ({{ $reservationsForRoom->count() }} Reservasi)
                                            @elseif($status === 'partial')
                                                Sebagian Terpakai ({{ $reservationsForRoom->count() }} Reservasi)
                                            @else
                                                Tersedia Penuh
                                            @endif
                                        </small>
                                    </div>
                                    <div class="status-icon">
                                        <i class="bi {{ $icon }} text-{{ $color }} fs-4"></i>
                                    </div>
                                </div>
                                
                                @if($reservationsForRoom->isNotEmpty())
                                <div class="mt-2">
                                    <small class="text-muted">Waktu reservasi:</small>
                                    <div class="reserved-times mt-1">
                                        @foreach($reservationsForRoom as $res)
                                        <span class="badge bg-{{ $color }} bg-opacity-75 me-1 mb-1">
                                            {{ date('H:i', strtotime($res->jam_mulai)) }}-{{ date('H:i', strtotime($res->jam_selesai)) }}
                                        </span>
                                        @endforeach
                                    </div>
                                </div>
                                @endif
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>
    @endif

    @if ($reservations->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 3rem;"></i>
                    </div>
                    <h3 class="text-muted h5 mb-2">Tidak Ada Reservasi</h3>
                    <p class="text-muted">Tidak ada reservasi yang disetujui pada tanggal ini.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Visual Schedule -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h4 class="mb-0 fw-bold h5">
                            <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                            Jadwal Visual Ruangan
                            @if(isset($room))
                                <span class="badge bg-primary bg-opacity-10 text-primary ms-2">
                                    {{ $room->nama_ruangan }}
                                </span>
                            @endif
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="schedule-timeline-wrapper" style="overflow-x: auto;">
                            <div class="schedule-timeline position-relative" style="min-width: 600px;">
                                @php
                                    $startHour = 8;
                                    $endHour = 17;
                                    $colors = ['bg-primary', 'bg-success', 'bg-info', 'bg-warning', 'bg-danger', 'bg-secondary'];
                                @endphp

                                <!-- Time markers -->
                                <div class="time-markers mb-3">
                                    @for($hour = $startHour; $hour <= $endHour; $hour++)
                                        <div class="time-marker" 
                                             style="left: {{ (($hour - $startHour) / ($endHour - $startHour)) * 100 }}%;">
                                            {{ sprintf('%02d:00', $hour) }}
                                        </div>
                                    @endfor
                                </div>

                                <!-- Hour lines -->
                                <div class="hour-lines position-absolute top-0 start-0 w-100 h-100">
                                    @for($hour = $startHour; $hour <= $endHour; $hour++)
                                        <div class="hour-line position-absolute top-0 bottom-0" 
                                             style="left: {{ (($hour - $startHour) / ($endHour - $startHour)) * 100 }}%;"></div>
                                    @endfor
                                </div>

                                <!-- Reservations timeline -->
                                <div class="reservations-timeline position-relative">
                                    @foreach($reservations as $index => $reservation)
                                    @php
                                        $startTime = \Carbon\Carbon::parse($reservation->jam_mulai);
                                        $endTime = \Carbon\Carbon::parse($reservation->jam_selesai);

                                        $startPosition = (($startTime->hour - $startHour) * 60 + $startTime->minute) / (($endHour - $startHour) * 60);
                                        $duration = $startTime->diffInMinutes($endTime) / (($endHour - $startHour) * 60);
                                        $colorClass = $colors[$index % count($colors)];
                                        
                                        // Hitung lebar minimum untuk menampilkan teks
                                        $minWidthForText = $duration * 100 > 8 ? '' : 'no-text';
                                        $shortLabel = $namaAwal . ' ' . date('H:i', strtotime($reservation->jam_mulai));
                                    @endphp
                                    <div class="reservation-block {{ $colorClass }} shadow-sm mb-3 rounded-3 position-relative {{ $minWidthForText }}" 
                                         style="left: {{ $startPosition * 100 }}%; 
                                                width: {{ $duration * 100 }}%;
                                                height: 60px;"
                                         data-bs-toggle="tooltip" 
                                         data-short-label="{{ $shortLabel }}"
                                         title="{{ $reservation->nama }} - {{ $reservation->keperluan }} | {{ $reservation->roomInfo->nama_ruangan ?? 'N/A' }} | {{ date('H:i', strtotime($reservation->jam_mulai)) }}-{{ date('H:i', strtotime($reservation->jam_selesai)) }}">>
                                        <div class="d-flex flex-column justify-content-center h-100 px-3 text-white position-relative">
                                            @php
                                                $namaWords = explode(' ', $reservation->nama);
                                                $namaAwal = $namaWords[0] ?? '';
                                                $ruanganNama = $reservation->roomInfo->nama_ruangan ?? 'N/A';
                                                $jamMulai = date('H:i', strtotime($reservation->jam_mulai));
                                                $jamSelesai = date('H:i', strtotime($reservation->jam_selesai));
                                            @endphp
                                            
                                            <!-- Label Utama -->
                                            <div class="reservation-main-info text-center">
                                                <div class="fw-bold reservation-title mb-1" style="font-size: 0.9rem; line-height: 1.2;">
                                                    {{ $namaAwal }}
                                                </div>
                                                <div class="reservation-details d-flex justify-content-center align-items-center flex-wrap">
                                                    <small class="opacity-90 me-2 d-none d-lg-inline-block reservation-room" style="font-size: 0.75rem;">
                                                        <i class="bi bi-door-closed-fill me-1"></i>{{ Str::limit($ruanganNama, 8) }}
                                                    </small>
                                                    <small class="fw-bold reservation-time opacity-95" style="font-size: 0.75rem;">
                                                        <i class="bi bi-clock-fill me-1"></i>{{ $jamMulai }}-{{ $jamSelesai }}
                                                    </small>
                                                </div>
                                            </div>
                                            
                                            <!-- Badge Status untuk reservasi lebar -->
                                            <div class="reservation-badge position-absolute top-0 end-0 mt-1 me-1">
                                                <span class="badge bg-light bg-opacity-20 text-white" style="font-size: 0.6rem;">
                                                    <i class="bi bi-person-fill"></i>
                                                </span>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h4 class="mb-0 fw-bold h5">
                            <i class="bi bi-table me-2 text-primary"></i>
                            Detail Reservasi
                        </h4>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th class="border-0 fw-bold py-3">
                                            <i class="bi bi-clock me-2"></i>Waktu
                                        </th>
                                        <th class="border-0 fw-bold py-3 d-none d-md-table-cell">
                                            <i class="bi bi-door-open me-2"></i>Ruangan
                                        </th>
                                        <th class="border-0 fw-bold py-3">
                                            <i class="bi bi-person me-2"></i>Nama
                                        </th>
                                        <th class="border-0 fw-bold py-3 d-none d-md-table-cell">
                                            <i class="bi bi-journal-text me-2"></i>Keperluan
                                        </th>
                                        <th class="border-0 fw-bold py-3">
                                            <i class="bi bi-hourglass-split me-2"></i>Durasi
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($reservations as $index => $reservation)
                                    <tr class="border-bottom">
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <span class="badge {{ $colors[$index % count($colors)] }} me-2" style="width: 4px; height: 20px;"></span>
                                                <strong>
                                                    {{ date('H:i', strtotime($reservation->jam_mulai)) }} - 
                                                    {{ date('H:i', strtotime($reservation->jam_selesai)) }}
                                                </strong>
                                            </div>
                                        </td>
                                        <td class="py-3 d-none d-md-table-cell">
                                            <div class="d-flex align-items-center">
                                                <i class="bi bi-door-closed-fill text-primary me-2"></i>
                                                <span class="badge bg-primary bg-opacity-10 text-primary px-2 py-1">
                                                    {{ $reservation->roomInfo->nama_ruangan ?? 'Tidak diketahui' }}
                                                </span>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($reservation->nama, 0, 1)) }}
                                                </div>
                                                <strong>{{ $reservation->nama }}</strong>
                                            </div>
                                        </td>
                                        <td class="py-3 d-none d-md-table-cell">
                                            <span class="text-muted">{{ $reservation->keperluan }}</span>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $start = \Carbon\Carbon::parse($reservation->jam_mulai);
                                                $end = \Carbon\Carbon::parse($reservation->jam_selesai);
                                                $duration = $start->diff($end);
                                            @endphp
                                            <span class="badge bg-light text-dark">
                                                {{ $duration->h }} jam {{ $duration->i }} menit
                                            </span>
                                        </td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>
@endsection

@section('styles')
<style>
    .bg-gradient-primary {
        background: linear-gradient(135deg, #0F172A 0%, #1e293b 100%);
    }

    .schedule-timeline-wrapper {
        overflow-x: auto;
        -webkit-overflow-scrolling: touch;
    }
    
    .schedule-timeline {
        min-height: 200px;
        background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 0.5rem;
        padding: 20px;
        position: relative;
        min-width: 600px;
    }

    .time-markers {
        position: relative;
        height: 30px;
        border-bottom: 2px solid #dee2e6;
        margin-bottom: 20px;
    }

    .time-marker {
        position: absolute;
        top: 0;
        transform: translateX(-50%);
        font-size: 0.8rem;
        color: #6c757d;
        font-weight: 600;
        background: white;
        padding: 2px 8px;
        border-radius: 4px;
        border: 1px solid #dee2e6;
    }

    .reservations-timeline {
        position: relative;
    }

    .reservation-block {
        position: absolute;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .reservation-block:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.15) !important;
    }

    .avatar-circle {
        width: 35px;
        height: 35px;
        border-radius: 50%;
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        color: white;
        font-weight: bold;
        font-size: 0.9rem;
    }

    .card {
        transition: all 0.3s ease;
    }

    .card:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(0,0,0,0.1);
    }

    .table tbody tr:hover {
        background-color: #f8f9fa;
    }

    .room-status-card {
        border: 2px solid;
        transition: all 0.3s ease;
    }

    .room-status-card:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 15px rgba(0,0,0,0.1);
    }

    .reserved-times {
        max-height: 60px;
        overflow-y: auto;
    }

    .reserved-times::-webkit-scrollbar {
        width: 4px;
    }

    .reserved-times::-webkit-scrollbar-track {
        background: #f1f1f1;
        border-radius: 4px;
    }

    .reserved-times::-webkit-scrollbar-thumb {
        background: #888;
        border-radius: 4px;
    }

    .reserved-times::-webkit-scrollbar-thumb:hover {
        background: #555;
    }

    /* Garis vertikal setiap jam */
    .hour-lines {
        pointer-events: none;
    }

    .hour-line {
        width: 1px;
        background-color: rgba(0, 0, 0, 0.1);
    }

    /* Tampilan untuk reservasi sempit */
    .reservation-block.no-text .reservation-main-info {
        display: none;
    }

    .reservation-block.no-text {
        background-image: repeating-linear-gradient(
            45deg,
            transparent,
            transparent 5px,
            rgba(255,255,255,0.3) 5px,
            rgba(255,255,255,0.3) 10px
        ) !important;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .reservation-block.no-text::before {
        content: attr(data-short-label);
        font-size: 0.7rem;
        font-weight: bold;
        color: white;
        text-shadow: 1px 1px 2px rgba(0,0,0,0.5);
    }

    /* Perbaikan layout label */
    .reservation-main-info {
        flex: 1;
        min-width: 0;
    }

    .reservation-details {
        gap: 0.25rem;
    }

    .reservation-badge {
        z-index: 10;
    }

    /* Hover effects untuk label */
    .reservation-block:hover .reservation-title {
        text-shadow: 2px 2px 4px rgba(0,0,0,0.3);
    }

    .reservation-block:hover .reservation-badge {
        transform: scale(1.1);
        transition: transform 0.2s ease;
    }

    /* Responsive label adjustments */
    @media (max-width: 1200px) {
        .reservation-room {
            display: none !important;
        }
    }

    @media (max-width: 768px) {
        .schedule-timeline {
            padding: 15px;
            min-width: 100%;
        }

        .reservation-block {
            height: 50px !important;
        }

        .reservation-block .fw-bold {
            font-size: 0.8rem;
        }

        .time-marker {
            font-size: 0.7rem;
            padding: 1px 4px;
        }
        
        .table th, .table td {
            padding: 0.75rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .avatar-circle {
            width: 30px;
            height: 30px;
            font-size: 0.8rem;
        }
        
        .card-header h4 {
            font-size: 1.1rem;
        }
        
        .bg-gradient-primary h1 {
            font-size: 1.5rem !important;
        }
        
        /* Responsif untuk reservasi timeline */
        .reservation-block .reservation-title {
            font-size: 0.8rem !important;
        }
        
        .reservation-block .reservation-time {
            font-size: 0.65rem !important;
        }
        
        .reservation-block .reservation-room {
            display: none !important;
        }

        .reservation-details {
            justify-content: center !important;
        }

        .reservation-badge {
            display: none;
        }
    }
    
    @media (max-width: 576px) {
        .bg-gradient-primary h1 {
            font-size: 1.3rem !important;
        }
        
        .bg-gradient-primary p {
            font-size: 0.9rem;
        }
        
        .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .room-status-card {
            padding: 15px !important;
        }
        
        .reservation-block {
            height: 40px !important;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    function filterByRoom() {
        const select = document.getElementById('room-filter');
        const url = select.value;
        if (url) {
            window.location.href = url;
        }
    }
    
    // Initialize tooltips
    document.addEventListener('DOMContentLoaded', function() {
        const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    });
</script>
@endsection