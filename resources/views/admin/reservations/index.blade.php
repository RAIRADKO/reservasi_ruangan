@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">
    <div class="row mb-4">
        <div class="col-12">
            <div class="d-flex flex-column flex-lg-row justify-content-between align-items-start align-items-lg-center gap-3">
                <div>
                    <h2 class="h3 mb-1 text-dark fw-bold">
                        <i class="bi bi-calendar-check me-2 text-primary"></i>
                        Manajemen Reservasi
                    </h2>
                    <p class="text-muted mb-0 small">Kelola semua reservasi ruang rapat</p>
                </div>
                <div class="d-flex flex-wrap gap-2">
                    <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary d-flex align-items-center">
                        <i class="bi bi-plus-circle-fill me-2"></i>
                        <span>Buat Reservasi</span>
                    </a>
                    <a href="{{ route('admin.reservations.export') }}" class="btn btn-success d-flex align-items-center">
                        <i class="bi bi-file-earmark-excel-fill me-2"></i>
                        <span>Export Data</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-bottom-0 py-3">
            <h5 class="mb-0 text-dark fw-semibold">
                <i class="bi bi-funnel me-2"></i>
                Filter Reservasi
            </h5>
        </div>
        <div class="card-body">
            <form action="{{ route('admin.reservations.index') }}" method="GET">
                <div class="row g-3 align-items-end">
                    <div class="col-md-5">
                        <label for="date_filter" class="form-label">Filter Berdasarkan Tanggal</label>
                        <input type="date" class="form-control" id="date_filter" name="date" value="{{ request('date') }}">
                    </div>
                    <div class="col-md-5">
                        <label for="room_filter" class="form-label">Filter Berdasarkan Ruangan</label>
                        <select class="form-select" id="room_filter" name="room_id">
                            <option value="">-- Semua Ruangan --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" {{ request('room_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->nama_ruangan }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-search me-1"></i> Filter
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-dark fw-semibold">
                        <i class="bi bi-table me-2"></i>
                        Daftar Reservasi
                    </h5>
                </div>
            </div>
        </div>
        
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th class="fw-semibold border-0 py-3">
                                <i class="bi bi-calendar-date me-1"></i>
                                Tanggal
                            </th>
                            <th class="fw-semibold border-0 py-3">
                                <i class="bi bi-person me-1"></i>
                                Nama
                            </th>
                            <th class="fw-semibold border-0 py-3 d-none d-sm-table-cell">
                                <i class="bi bi-building me-1"></i>
                                Instansi
                            </th>
                            <th class="fw-semibold border-0 py-3">
                                <i class="bi bi-telephone me-1"></i>
                                Kontak
                            </th>
                            <th class="fw-semibold border-0 py-3 d-none d-md-table-cell">
                                <i class="bi bi-clock me-1"></i>
                                Jam
                            </th>
                            <th class="fw-semibold border-0 py-3 d-none d-lg-table-cell">
                                <i class="bi bi-info-circle me-1"></i>
                                Keperluan
                            </th>
                            <th class="fw-semibold border-0 py-3 text-center">
                                <i class="bi bi-flag me-1"></i>
                                Status
                            </th>
                            <th class="fw-semibold border-0 py-3 text-center">
                                <i class="bi bi-gear me-1"></i>
                                Aksi
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @php
                            $shownBatches = [];
                        @endphp
                        @forelse($reservations as $reservation)
                            @php
                                $batchKey = $reservation->batch_id ?: 'single-'.$reservation->id;
                                if (in_array($batchKey, $shownBatches)) continue;
                                $shownBatches[] = $batchKey;
                                // Ambil semua reservasi dalam batch (atau single)
                                $batchItems = $reservation->batch_id
                                    ? $reservations->where('batch_id', $reservation->batch_id)
                                    : collect([$reservation]);
                                $first = $batchItems->sortBy('tanggal')->first();
                                $last = $batchItems->sortByDesc('tanggal')->first();
                            @endphp
                        <tr class="border-bottom">
                            <td class="py-3">
                                <div class="fw-medium text-dark">
                                    @if($reservation->batch_id && $first->tanggal != $last->tanggal)
                                        {{ $first->tanggal->format('d M Y') }} - {{ $last->tanggal->format('d M Y') }}
                                    @else
                                        {{ $first->tanggal_formatted }}
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium text-dark">{{ $first->nama }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 d-none d-sm-table-cell">
                                <span class="text-muted">{{ $first->dinas->name ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3">
                                <div class="text-muted">{{ $first->kontak }}</div>
                            </td>
                            <td class="py-3 d-none d-md-table-cell">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $first->jam_range }}
                                </span>
                            </td>
                            <td class="py-3 d-none d-lg-table-cell">
                                <div class="text-muted" style="max-width: 200px;">
                                    {{ Str::limit($first->keperluan, 50) }}
                                    @if(strlen($first->keperluan) > 50)
                                        <i class="bi bi-info-circle text-muted ms-1" 
                                           data-bs-toggle="tooltip" 
                                           title="{{ $first->keperluan }}"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3 text-center">
                                <div class="d-flex flex-column align-items-center gap-1">
                                    @php
                                        $statusConfig = [
                                            'pending' => ['class' => 'warning', 'icon' => 'clock', 'text' => 'Pending'],
                                            'approved' => ['class' => 'success', 'icon' => 'check-circle', 'text' => 'Approved'],
                                            'rejected' => ['class' => 'danger', 'icon' => 'x-circle', 'text' => 'Rejected'],
                                            'completed' => ['class' => 'primary', 'icon' => 'check-all', 'text' => 'Completed'],
                                            'canceled' => ['class' => 'secondary', 'icon' => 'dash-circle', 'text' => 'Canceled']
                                        ];
                                        $config = $statusConfig[$first->status] ?? ['class' => 'secondary', 'icon' => 'question', 'text' => 'Unknown'];
                                    @endphp
                                    <span class="badge bg-{{ $config['class'] }} d-flex align-items-center gap-1">
                                        <i class="bi bi-{{ $config['icon'] }}"></i>
                                        {{ $config['text'] }}
                                    </span>
                                    @if($first->status == 'rejected' && $first->rejection_reason)
                                        <i class="bi bi-info-circle text-muted cursor-pointer" 
                                           data-bs-toggle="tooltip" 
                                           title="Alasan: {{ $first->rejection_reason }}"></i>
                                    @endif
                                    @if($first->status == 'canceled')
                                        <i class="bi bi-info-circle text-muted cursor-pointer" 
                                           data-bs-toggle="tooltip" 
                                           title="Dibatalkan oleh user"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                    @if($first->status == 'pending')
                                        <form method="POST" action="{{ route('admin.reservations.update-status', $first->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-success d-flex align-items-center" 
                                                    data-bs-toggle="tooltip" title="Approve Reservasi">
                                                <i class="bi bi-check-lg me-1"></i>
                                                <span class="d-none d-md-inline">Approve</span>
                                            </button>
                                        </form>
                                        <button type="button" class="btn btn-sm btn-danger d-flex align-items-center reject-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal"
                                                data-reservation-id="{{ $first->id }}"
                                                data-reservation-name="{{ $first->nama }}"
                                                title="Reject Reservasi">
                                            <i class="bi bi-x-lg me-1"></i>
                                            <span class="d-none d-md-inline">Reject</span>
                                        </button>
                                    @endif
                                    @if($first->status == 'approved' && $first->admin_id)
                                        <form method="POST" action="{{ route('admin.reservations.checkout', $first->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-info d-flex align-items-center" 
                                                    data-bs-toggle="tooltip" title="Check Out">
                                                <i class="bi bi-box-arrow-right me-1"></i>
                                                <span class="d-none d-md-inline">Check Out</span>
                                            </button>
                                        </form>
                                    @endif
                                    <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#confirmDeleteModal"
                                            data-url="{{ route('admin.reservations.destroy', $first->id) }}"
                                            data-message="Apakah Anda yakin ingin menghapus reservasi oleh {{ $first->nama }} pada tanggal {{ $first->tanggal_formatted }}?"
                                            title="Hapus Reservasi">
                                        <i class="bi bi-trash me-1"></i>
                                        <span class="d-none d-md-inline">Hapus</span>
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="8" class="text-center py-5">
                                <div class="d-flex flex-column align-items-center">
                                    <i class="bi bi-calendar-x text-muted mb-3" style="font-size: 3rem;"></i>
                                    <h5 class="text-muted">Tidak ada reservasi</h5>
                                    <p class="text-muted mb-3">Belum ada reservasi yang dibuat atau sesuai dengan filter Anda.</p>
                                    <a href="{{ route('admin.reservations.index') }}" class="btn btn-secondary">
                                        <i class="bi bi-arrow-clockwise me-2"></i>
                                        Reset Filter
                                    </a>
                                </div>
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    @if(method_exists($reservations, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $reservations->links() }}
        </div>
    @endif
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content border-0 shadow-lg">
            <div class="modal-header border-bottom-0 pb-0">
                <h5 class="modal-title fw-bold" id="rejectModalLabel">
                    <i class="bi bi-x-circle text-danger me-2"></i>
                    Tolak Reservasi
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="status" value="rejected">
                    
                    <div class="alert alert-warning border-0 bg-warning bg-opacity-10" role="alert">
                        <i class="bi bi-exclamation-triangle me-2"></i>
                        <strong>Perhatian:</strong> Anda akan menolak reservasi dari <strong><span id="reservationName"></span></strong>
                    </div>
                    
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label fw-semibold">
                            <i class="bi bi-chat-text me-1"></i>
                            Alasan Penolakan <span class="text-danger">*</span>
                        </label>
                        <textarea class="form-control border-2" 
                                  id="rejection_reason" 
                                  name="rejection_reason" 
                                  rows="4" 
                                  placeholder="Mohon jelaskan alasan penolakan reservasi ini..."
                                  required></textarea>
                        <div class="form-text">
                            <i class="bi bi-info-circle me-1"></i>
                            Alasan ini akan dikirimkan kepada pemohon reservasi
                        </div>
                    </div>
                </div>
                <div class="modal-footer border-top-0 pt-0">
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">
                        <i class="bi bi-x me-2"></i>
                        Batal
                    </button>
                    <button type="submit" class="btn btn-danger">
                        <i class="bi bi-x-circle me-2"></i>
                        Tolak Reservasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    const rejectModal = document.getElementById('rejectModal');
    if (rejectModal) {
        rejectModal.addEventListener('show.bs.modal', function (event) {
            const button = event.relatedTarget;
            const reservationId = button.getAttribute('data-reservation-id');
            const reservationName = button.getAttribute('data-reservation-name');
            const modalBodyReservationName = rejectModal.querySelector('#reservationName');
            const rejectForm = rejectModal.querySelector('#rejectForm');
            const actionUrl = `{{ url('admin/reservations') }}/${reservationId}/update-status`;
            modalBodyReservationName.textContent = reservationName;
            rejectForm.action = actionUrl;
        });

        rejectModal.addEventListener('hidden.bs.modal', function (event) {
            const form = rejectModal.querySelector('#rejectForm');
            const textarea = form.querySelector('#rejection_reason');
            textarea.value = '';
        });
    }

    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
                
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 5000);
            }
        });
    });
});
</script>
@endsection