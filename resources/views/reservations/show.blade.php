@extends('layouts.app')

@section('title', 'Detail Reservasi')

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card shadow-sm mb-4">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-journal-richtext me-2 text-primary"></i>
                    Detail Reservasi
                </h5>
                <a href="{{ route('user.reservations') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                </a>
            </div>
            <div class="card-body p-4">
                {{-- Status Alert --}}
                <div class="alert 
                    @if($reservation->status == 'approved') alert-success @elseif($reservation->status == 'pending') alert-warning @elseif($reservation->status == 'rejected') alert-danger @else alert-secondary @endif" 
                    role="alert">
                    <h5 class="alert-heading fw-bold">
                        @if($reservation->status == 'approved') <i class="bi bi-check-circle-fill me-2"></i>Disetujui @endif
                        @if($reservation->status == 'pending') <i class="bi bi-hourglass-split me-2"></i>Menunggu Persetujuan @endif
                        @if($reservation->status == 'rejected') <i class="bi bi-x-circle-fill me-2"></i>Ditolak @endif
                        @if($reservation->status == 'canceled') <i class="bi bi-slash-circle-fill me-2"></i>Dibatalkan @endif
                    </h5>
                    <p class="mb-0">
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
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted"><i class="bi bi-person-fill me-2"></i>Informasi Pemohon</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">Nama: <strong>{{ $reservation->nama }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Kontak: <strong>{{ $reservation->kontak }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Instansi: <strong>{{ $reservation->dinas->name ?? 'N/A' }}</strong></li>
                        </ul>
                    </div>
                    <div class="col-md-6 mb-3">
                        <h6 class="text-muted"><i class="bi bi-calendar-event-fill me-2"></i>Detail Jadwal</h6>
                        <ul class="list-group list-group-flush">
                            <li class="list-group-item d-flex justify-content-between align-items-center">Tanggal: <strong>{{ $reservation->tanggal->isoFormat('dddd, D MMMM Y') }}</strong></li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">Waktu: <strong>{{ $reservation->jam_range }}</strong></li>
                        </ul>
                    </div>
                </div>
                
                <hr>

                <h6 class="text-muted"><i class="bi bi-card-text me-2"></i>Keperluan</h6>
                <p class="lead">{{ $reservation->keperluan }}</p>

            </div>
        </div>
    </div>
    <div class="col-lg-4">
        <div class="card shadow-sm">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-building me-2 text-primary"></i>
                    Informasi Ruangan
                </h5>
            </div>
            @if($reservation->roomInfo)
                <img src="{{ $reservation->roomInfo->foto_url }}" class="card-img-top" alt="{{ $reservation->roomInfo->nama_ruangan }}">
                <div class="card-body">
                    <h5 class="card-title fw-bold">{{ $reservation->roomInfo->nama_ruangan }}</h5>
                    <p class="card-text text-muted">{{ $reservation->roomInfo->deskripsi }}</p>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item"><i class="bi bi-people-fill me-2"></i><strong>Kapasitas:</strong> {{ $reservation->roomInfo->kapasitas }} orang</li>
                        <li class="list-group-item"><i class="bi bi-star-fill me-2"></i><strong>Fasilitas:</strong> {{ $reservation->roomInfo->fasilitas }}</li>
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