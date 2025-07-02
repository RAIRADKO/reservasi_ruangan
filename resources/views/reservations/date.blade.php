@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1>Reservasi pada Tanggal {{ \Carbon\Carbon::parse($date)->isoFormat('dddd, D MMMM Y') }}</h1>
        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
            <i class="bi bi-arrow-left"></i> Kembali ke Kalender
        </a>
    </div>
    
    @if ($reservations->isEmpty())
        <div class="alert alert-info">
            Tidak ada reservasi yang disetujui pada tanggal ini.
        </div>
    @else
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Waktu</th>
                                <th>Nama</th>
                                <th>Keperluan</th>
                                <th>Durasi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                            <tr>
                                <td>
                                    {{ date('H:i', strtotime($reservation->jam_mulai)) }} - 
                                    {{ date('H:i', strtotime($reservation->jam_selesai)) }}
                                </td>
                                <td>{{ $reservation->nama }}</td>
                                <td>{{ $reservation->keperluan }}</td>
                                <td>
                                    @php
                                        $start = \Carbon\Carbon::parse($reservation->jam_mulai);
                                        $end = \Carbon\Carbon::parse($reservation->jam_selesai);
                                        $duration = $start->diff($end);
                                    @endphp
                                    {{ $duration->h }} jam {{ $duration->i }} menit
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <!-- Jadwal Visual -->
                <div class="mt-5">
                    <h4>Jadwal Visual</h4>
                    <div class="schedule-timeline mt-3">
                        @php
                            // Buat timeline dari jam 8 pagi sampai 5 sore
                            $startHour = 8;
                            $endHour = 17;
                        @endphp
                        
                        <div class="position-absolute bg-primary text-white p-1 rounded" 
                            style="left: {{ $startPosition / ($endHour - $startHour) * 100 }}%; 
                                    width: {{ $duration / ($endHour - $startHour) * 100 }}%;
                                    height: 100%;">
                            <div class="d-flex justify-content-between align-items-center h-100 px-2">
                                <span class="fw-bold">{{ $reservation->nama }}</span>
                                <small>
                                    {{ date('H:i', strtotime($reservation->jam_mulai)) }}-{{ date('H:i', strtotime($reservation->jam_selesai)) }}
                                </small>
                            </div>
                        </div>                        
                        <!-- Tampilkan setiap reservasi sebagai baris visual -->
                        @foreach($reservations as $reservation)
                        @php
                            $startTime = \Carbon\Carbon::parse($reservation->jam_mulai);
                            $endTime = \Carbon\Carbon::parse($reservation->jam_selesai);
                            
                            $startPosition = (($startTime->hour - $startHour) * 60 + $startTime->minute) / 60;
                            $duration = $startTime->diffInMinutes($endTime) / 60;
                        @endphp
                        <div class="d-flex mt-2 position-relative" style="height: 30px;">
                            <div class="position-absolute bg-primary text-white p-1 rounded" 
                                style="left: {{ $startPosition / ($endHour - $startHour) * 100 }}%; 
                                       width: {{ $duration / ($endHour - $startHour) * 100 }}%;
                                       height: 100%;">
                                <div class="d-flex justify-content-between align-items-center h-100 px-2">
                                    <span>{{ $reservation->nama }}</span>
                                    <small>
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
    @endif
</div>
@endsection

@section('styles')
<style>
    .schedule-timeline {
        position: relative;
        border: 1px solid #dee2e6;
        border-radius: 0.25rem;
        padding: 15px;
    }
    .timeline-hour {
        position: absolute;
        top: -25px;
        font-size: 0.8rem;
        color: #6c757d;
    }
</style>
@endsection