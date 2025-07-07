@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')
<div class="row">
    <div class="col-12 col-lg-8 mb-4 mb-lg-0">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
                <h5 class="mb-2 mb-md-0 fw-bold h6 h5-md">
                    <i class="bi bi-journal-richtext me-2 text-primary"></i>
                    Detail Reservasi
                </h5>
                <div class="d-flex gap-2"> {{-- Wrapper untuk tombol --}}
                    @auth
                        {{-- Logika untuk menampilkan tombol Check Out --}}
                        @if(Auth::id() == $reservation->user_id)
                            @php
                                $isPast = \Carbon\Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->jam_selesai)->isPast();
                            @endphp

                            @if($reservation->status == 'approved' && $isPast)
                                <a href="{{ route('user.reservations.checkout', $reservation->id) }}" class="btn btn-sm btn-success mt-2 mt-md-0">
                                    <i class="bi bi-check2-square"></i> Check Out
                                </a>
                            @endif
                        @endif
                    @endauth

                    <a href="{{ route('user.reservations') }}" class="btn btn-sm btn-outline-secondary mt-2 mt-md-0">
                        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>
            <div class="card-body p-3 p-md-4">
                {{-- Status Alert --}}
                <div class="alert 
                    @if($reservation->status == 'approved') alert-success @elseif($reservation->status == 'pending') alert-warning @elseif($reservation->status == 'rejected') alert-danger @else alert-secondary @endif" 
                    role="alert">
                    <h5 class="alert-heading fw-bold h6">
                        @if($reservation->status == 'approved') <i class="bi bi-check-circle-fill me-2"></i>Disetujui @endif
                        @if($reservation->status == 'pending') <i class="bi bi-hourglass-split me-2"></i>Menunggu Persetujuan @endif
                        @if($reservation->status == 'rejected') <i class="bi bi-x-circle-fill me-2"></i>Ditolak @endif
                        @if($reservation->status == 'canceled') <i class="bi bi-slash-circle-fill me-2"></i>Dibatalkan @endif
                    </h5>
                    <p class="mb-0 fs-6">
                        @if($reservation->status == 'rejected')
                            Reservasi ini ditolak oleh admin pada: <strong class="text-dark">{{ $reservation->updated_at->isoFormat('dddd, D MMMM YYYY, HH:mm') }}</strong>.
                        @elseif($reservation->status == 'approved')
                            Reservasi ini telah disetujui dan dijadwalkan.
                        @elseif($reservation->status == 'pending')
                            Reservasi ini sedang menunggu persetujuan dari admin.
                        @elseif($reservation->status == 'canceled')
                            Reservasi ini telah Anda batalkan pada {{ $reservation->updated_at->isoFormat('D MMMM YYYY, HH:mm') }}.
                        @endif
                    </p>
                    @if($reservation->status == 'rejected' && $reservation->rejection_reason)
                        <hr>
                        <p class="mb-0"><strong>Alasan Penolakan:</strong><br>{{ $reservation->rejection_reason }}</p>
                    @endif
                </div>

                <div class="row mt-4">
                    <div class="col-12 col-md-6 mb-3">
                        <h6 class="text-muted h6"><i class="bi bi-person-fill me-2"></i>Informasi Pemohon</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">Nama: <strong>{{ $reservation->nama }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">Kontak: <strong>{{ $reservation->kontak }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">Instansi: <strong>{{ $reservation->dinas->name ?? 'N/A' }}</strong></li>
                        </ul>
                    </div>
                    <div class="col-12 col-md-6 mb-3">
                        <h6 class="text-muted h6"><i class="bi bi-calendar-event-fill me-2"></i>Detail Jadwal</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">Tanggal: <strong>{{ $reservation->tanggal->isoFormat('dddd, D MMMM Y') }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center px-0">Waktu: <strong>{{ $reservation->jam_range }}</strong></li>
                        </ul>
                    </div>
                </div>
                
                <hr>

                <h6 class="text-muted h6"><i class="bi bi-card-text me-2"></i>Keperluan</h6>
                <p class="lead fs-6">{{ $reservation->keperluan }}</p>

                @auth
                    @if(Auth::id() == $reservation->user_id)
                        @php
                            $currentTime = \Carbon\Carbon::now();
                            $startTime = \Carbon\Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->jam_mulai);
                            $endTime = \Carbon\Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->jam_selesai);
                            $isPast = $endTime->isPast();
                            $isOngoing = $startTime->isPast() && !$endTime->isPast();
                        @endphp

                        @if($reservation->status == 'approved')
                            <div class="mt-4 p-3 bg-light rounded">
                                <div class="d-flex justify-content-between align-items-center">
                                    <div>
                                        <h6 class="mb-1 fw-bold text-primary">
                                            <i class="bi bi-clock-history me-2"></i>Status Kehadiran
                                        </h6>
                                        <p class="mb-0 text-muted small">
                                            @if($isPast)
                                                Waktu reservasi telah berakhir. Silakan lakukan check out.
                                            @elseif($isOngoing)
                                                Reservasi sedang berlangsung. Anda dapat melakukan check out sekarang.
                                            @elseif($canCheckout)
                                                Reservasi akan dimulai dalam 1 jam. Anda sudah dapat melakukan check out.
                                            @else
                                                Reservasi akan dimulai pada {{ $reservation->jam_mulai }}.
                                            @endif
                                        </p>
                                    </div>
                                    <div>
                                        @if($canCheckout)
                                            <a href="{{ route('user.reservations.checkout', $reservation->id) }}" 
                                               class="btn btn-success btn-sm" 
                                               onclick="return confirm('Apakah Anda yakin ingin melakukan check out?')">
                                                <i class="bi bi-check2-square me-1"></i> Check Out
                                            </a>
                                        @else
                                            <span class="badge bg-info">Belum Waktunya</span>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        @endif
                    @endif
                @endauth
            </div>
        </div>
    </div>
    <div class="col-12 col-lg-4 mt-4 mt-lg-0">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold h6 h5-md">
                    <i class="bi bi-building me-2 text-primary"></i>
                    Informasi Ruangan
                </h5>
            </div>
            @if($reservation->roomInfo)
                <img src="{{ $reservation->roomInfo->foto_url }}" class="card-img-top" alt="{{ $reservation->roomInfo->nama_ruangan }}">
                <div class="card-body">
                    <h5 class="card-title fw-bold h6">{{ $reservation->roomInfo->nama_ruangan }}</h5>
                    <p class="card-text text-muted fs-6">{{ $reservation->roomInfo->deskripsi }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0"><i class="bi bi-people-fill me-2"></i><strong>Kapasitas:</strong> {{ $reservation->roomInfo->kapasitas }} orang</li>
                        <li class="list-group-item px-0"><i class="bi bi-star-fill me-2"></i><strong>Fasilitas:</strong> {{ $reservation->roomInfo->fasilitas }}</li>
                    </ul>
                </div>
            @else
                <div class="card-body">
                    <p class="text-muted">Informasi ruangan tidak tersedia.</p>
                </div>
            @endif
        </div>
    </div>
</div>
@endsection