@extends('layouts.app')

@section('content')
<div class="container">
    <!-- Header Section -->
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex justify-content-between align-items-center bg-gradient-primary text-white p-4 rounded-3 shadow-sm">
                <div>
                    <h1 class="mb-1 fw-bold">
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
                <a href="{{ route('home') }}" class="btn btn-light btn-lg shadow-sm">
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
                        <div class="col-md-3">
                            <label for="room-filter" class="form-label fw-bold">
                                <i class="bi bi-funnel me-2"></i>Filter Ruangan:
                            </label>
                        </div>
                        <div class="col-md-9">
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

    @if ($reservations->isEmpty())
        <div class="row">
            <div class="col-12">
                <div class="text-center py-5">
                    <div class="mb-4">
                        <i class="bi bi-calendar-x text-muted" style="font-size: 4rem;"></i>
                    </div>
                    <h3 class="text-muted mb-2">Tidak Ada Reservasi</h3>
                    <p class="text-muted fs-5">Tidak ada reservasi yang disetujui pada tanggal ini.</p>
                </div>
            </div>
        </div>
    @else
        <!-- Visual Schedule -->
        <div class="row mb-4">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h4 class="mb-0 fw-bold">
                            <i class="bi bi-bar-chart-line me-2 text-primary"></i>
                            Jadwal Visual
                        </h4>
                    </div>
                    <div class="card-body">
                        <div class="schedule-timeline position-relative">
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
                                            <small class="opacity-75">{{ Str::limit($reservation->keperluan, 20) }}</small>
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

        <!-- Detailed Table -->
        <div class="row">
            <div class="col-12">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-white border-bottom-0 py-3">
                        <h4 class="mb-0 fw-bold">
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
                                        <th class="border-0 fw-bold py-3">
                                            <i class="bi bi-person me-2"></i>Nama
                                        </th>
                                        <th class="border-0 fw-bold py-3">
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
                                        <td class="py-3">
                                            <div class="d-flex align-items-center">
                                                <div class="avatar-circle me-2">
                                                    {{ strtoupper(substr($reservation->nama, 0, 1)) }}
                                                </div>
                                                <strong>{{ $reservation->nama }}</strong>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="text-muted">{{ $reservation->keperluan }}</span>
                                        </td>
                                        <td class="py-3">
                                            @php
                                                $start = \Carbon\Carbon::parse($reservation->jam_mulai);
                                                $end = \Carbon\Carbon::parse($reservation->jam_selesai);
                                                $duration = $start->diff($end);
                                            @endphp
                                            <span class="badge bg-light text-dark fs-6">
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

    .schedule-timeline {
        min-height: 200px;
        background: linear-gradient(90deg, #f8f9fa 0%, #ffffff 100%);
        border-radius: 0.5rem;
        padding: 20px;
        position: relative;
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

    @media (max-width: 768px) {
        .schedule-timeline {
            padding: 15px;
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
