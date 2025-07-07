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
                        @endphp
                        
                        @foreach($allRooms as $roomItem)
                        <div class="col-12 col-md-6 col-lg-4 mb-3">
                            <div class="room-status-card p-3 rounded-3 h-100 
                                {{ $roomReservations->has($roomItem->id) ? 'bg-danger bg-opacity-10 border-danger' : 'bg-success bg-opacity-10 border-success' }}">
                                <div class="d-flex align-items-center justify-content-between">
                                    <div>
                                        <h6 class="mb-1 fw-bold">{{ $roomItem->nama_ruangan }}</h6>
                                        @if($roomReservations->has($roomItem->id))
                                            <small class="text-danger fw-bold">
                                                <i class="bi bi-exclamation-circle me-1"></i>
                                                {{ $roomReservations[$roomItem->id]->count() }} Reservasi
                                            </small>
                                        @else
                                            <small class="text-success fw-bold">
                                                <i class="bi bi-check-circle me-1"></i>
                                                Tersedia
                                            </small>
                                        @endif
                                    </div>
                                    <div class="status-icon">
                                        @if($roomReservations->has($roomItem->id))
                                            <i class="bi bi-door-closed-fill text-danger fs-4"></i>
                                        @else
                                            <i class="bi bi-door-open-fill text-success fs-4"></i>
                                        @endif
                                    </div>
                                </div>
                                
                                @if($roomReservations->has($roomItem->id))
                                <div class="mt-2">
                                    <small class="text-muted">Waktu terreservasi:</small>
                                    <div class="reserved-times mt-1">
                                        @foreach($roomReservations[$roomItem->id] as $res)
                                        <span class="badge bg-danger bg-opacity-75 me-1 mb-1">
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
                            Jadwal Visual
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

                                <!-- Reservations timeline -->
                                <div class="reservations-timeline">
                                    @foreach($reservations as $index => $reservation)
                                    @php
                                        $startTime = \Carbon\Carbon::parse($reservation->jam_mulai);
                                        $endTime = \Carbon\Carbon::parse($reservation->jam_selesai);

                                        $startPosition = (($startTime->hour - $startHour) * 60 + $startTime->minute) / (($endHour - $startHour) * 60);
                                        $duration = $startTime->diffInMinutes($endTime) / (($endHour - $startHour) * 60);
                                        $colorClass = $colors[$index % count($colors)];
                                    @endphp
                                    <div class="reservation-block {{ $colorClass }} shadow-sm mb-3 rounded-3 position-relative" 
                                         style="left: {{ $startPosition * 100 }}%; 
                                                width: {{ $duration * 100 }}%;
                                                height: 60px;">
                                        <div class="d-flex justify-content-between align-items-center h-100 px-3 text-white">
                                            <div>
                                                <div class="fw-bold">{{ $reservation->nama }}</div>
                                                <small class="opacity-75 d-none d-md-block">{{ Str::limit($reservation->keperluan, 20) }}</small>
                                            </div>
                                            <div class="text-end">
                                                <small class="fw-bold">
                                                    {{ date('H:i', strtotime($reservation->jam_mulai)) }}-{{ date('H:i', strtotime($reservation->jam_selesai)) }}
                                                </small>
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
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
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
</script>
@endsection