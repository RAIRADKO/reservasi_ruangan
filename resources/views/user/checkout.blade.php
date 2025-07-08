@extends('layouts.app')

@section('title', 'Check Out & Beri Masukan')

@section('content')
<div class="row justify-content-center">
    <div class="col-12 col-md-10 col-lg-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0 fw-bold">
                    <i class="bi bi-check2-square me-2 text-primary"></i>
                    Check Out & Beri Masukan
                </h5>
            </div>
            <div class="card-body p-4">
                <div class="alert alert-info" role="alert">
                    <h6 class="alert-heading">Selesaikan Reservasi Anda</h6>
                    <p>Terima kasih telah menggunakan ruangan. Mohon berikan masukan Anda untuk membantu kami meningkatkan kualitas layanan.</p>
                </div>

                <form method="POST" action="{{ route('user.reservations.complete', $reservation->id) }}">
                    @csrf
                    
                    {{-- Form Kepuasan --}}
                    <div class="my-4">
                        <h6 class="text-muted mb-3">Bagaimana tingkat kepuasan Anda terhadap fasilitas dan layanan?</h6>
                        <div class="d-flex justify-content-center flex-wrap gap-2 satisfaction-rating">
                            <input type="radio" class="btn-check" name="satisfaction_rating" id="rating-1" value="1" required>
                            <label class="btn btn-outline-danger" for="rating-1" data-bs-toggle="tooltip" title="Sangat Buruk">😠</label>

                            <input type="radio" class="btn-check" name="satisfaction_rating" id="rating-2" value="2">
                            <label class="btn btn-outline-warning" for="rating-2" data-bs-toggle="tooltip" title="Buruk">😟</label>

                            <input type="radio" class="btn-check" name="satisfaction_rating" id="rating-3" value="3">
                            <label class="btn btn-outline-secondary" for="rating-3" data-bs-toggle="tooltip" title="Cukup">😐</label>

                            <input type="radio" class="btn-check" name="satisfaction_rating" id="rating-4" value="4">
                            <label class="btn btn-outline-info" for="rating-4" data-bs-toggle="tooltip" title="Baik">😊</label>
                            
                            <input type="radio" class="btn-check" name="satisfaction_rating" id="rating-5" value="5">
                            <label class="btn btn-outline-success" for="rating-5" data-bs-toggle="tooltip" title="Sangat Baik">😍</label>
                        </div>
                        @error('satisfaction_rating')
                            <div class="text-danger text-center mt-2 small">{{ $message }}</div>
                        @enderror
                    </div>

                    {{-- Form Masukan --}}
                    <div class="mb-4">
                        <label for="feedback" class="form-label text-muted">Saran dan Masukan (Opsional)</label>
                        <textarea class="form-control" id="feedback" name="feedback" rows="4" placeholder="Sampaikan saran atau masukan Anda di sini..."></textarea>
                        @error('feedback')
                            <div class="text-danger mt-1 small">{{ $message }}</div>
                        @enderror
                    </div>
                    
                    <hr>

                    <div class="text-center my-4">
                        <h6 class="text-muted mb-3">Konfirmasi Check Out</h6>
                        <p>Dengan menekan tombol di bawah, Anda mengonfirmasi telah selesai menggunakan ruangan.</p>
                        
                        <button type="submit" class="btn btn-success btn-lg">
                            <i class="bi bi-check2-circle-fill me-2"></i> Konfirmasi Check Out
                        </button>
                    </div>
                </form>

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

@section('styles')
<style>
.satisfaction-rating .btn {
    font-size: 1.5rem;
    width: 60px;
    height: 60px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
}
.satisfaction-rating .btn:hover {
    transform: scale(1.1);
}
.btn-check:checked + .btn {
    transform: scale(1.1);
    box-shadow: 0 0 0 0.25rem var(--bs-btn-border-color);
}
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });
});
</script>
@endsection