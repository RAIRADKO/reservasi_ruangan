@extends('layouts.app')

@section('title', 'Check Out Reservasi')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-qr-code-scan me-2 text-primary"></i>
                    Langkah Terakhir: Survei & Check Out
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info" role="alert">
                    <h6 class="alert-heading">Selesaikan Reservasi Anda</h6>
                    <p>Terima kasih telah menggunakan ruangan. Untuk menyelesaikan proses reservasi, Anda diwajibkan untuk mengisi survei singkat sebagai masukan bagi kami.</p>
                </div>

                <div class="text-center my-4">
                    <h6 class="text-muted mb-3">Langkah 1: Pindai QR Code Survei</h6>
                    <p>Silakan pindai QR Code yang telah disediakan di dalam ruangan menggunakan kamera ponsel Anda untuk mengisi survei kepuasan.</p>
                    {{-- The QR code is physically in the room, so we show a placeholder icon --}}
                    <i class="bi bi-qr-code" style="font-size: 8rem; color: #6c757d;"></i>
                    <p class="mt-2 text-muted small">QR Code terletak di dekat pintu keluar.</p>
                </div>

                <hr>

                <div class="text-center my-4">
                     <h6 class="text-muted mb-3">Langkah 2: Konfirmasi Check Out</h6>
                    <p>Setelah Anda selesai mengisi survei, klik tombol di bawah ini untuk mengonfirmasi check-out dan menyelesaikan reservasi Anda secara resmi.</p>
                    
                    <form method="POST" action="{{ route('user.reservations.complete', $reservation->id) }}">
                        @csrf
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check2-circle-fill me-2"></i> Saya Sudah Mengisi Survei, Lanjutkan Check Out
                        </button>
                    </form>
                </div>

                <div class="card mt-4 bg-light border-0">
                    <div class="card-body">
                        <h6 class="card-title">Detail Reservasi Anda</h6>
                         <ul class="list-group list-group-flush">
                            <li class="list-group-item bg-transparent px-0"><strong>Ruangan:</strong> {{ $reservation->roomInfo->nama_ruangan }}</li>
                            <li class="list-group-item bg-transparent px-0"><strong>Tanggal:</strong> {{ $reservation->tanggal->isoFormat('dddd, D MMMM Y') }}</li>
                            <li class="list-group-item bg-transparent px-0"><strong>Waktu:</strong> {{ $reservation->jam_range }}</li>
                        </ul>
                    </div>
                </div>

                 <div class="text-center mt-4">
                    <a href="{{ route('user.reservations') }}" class="btn btn-sm btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Riwayat
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection