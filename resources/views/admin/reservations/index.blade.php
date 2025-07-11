@extends('layouts.admin')

@section('content')
<div class="container-fluid py-3">
    <!-- Header Section -->
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

    <!-- Stats Cards (Optional - can be added if you have stats data) -->
    <div class="row mb-4 d-none" id="statsCards">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0">
                            <div class="bg-primary bg-opacity-10 p-3 rounded-circle">
                                <i class="bi bi-calendar-event text-primary fs-4"></i>
                            </div>
                        </div>
                        <div class="flex-grow-1 ms-3">
                            <h6 class="card-title mb-1">Total Reservasi</h6>
                            <h4 class="mb-0 text-primary">{{ $reservations->count() }}</h4>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content Card -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-bottom-0 py-3">
            <div class="row align-items-center">
                <div class="col">
                    <h5 class="mb-0 text-dark fw-semibold">
                        <i class="bi bi-table me-2"></i>
                        Daftar Reservasi
                    </h5>
                </div>
                <div class="col-auto">
                    <div class="d-flex gap-2">
                        <!-- Filter buttons could be added here -->
                        <button class="btn btn-outline-secondary btn-sm" type="button">
                            <i class="bi bi-funnel me-1"></i>
                            Filter
                        </button>
                        <button class="btn btn-outline-secondary btn-sm" type="button">
                            <i class="bi bi-arrow-clockwise me-1"></i>
                            Refresh
                        </button>
                    </div>
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
                        @forelse($reservations as $reservation)
                        <tr class="border-bottom">
                            <td class="py-3">
                                <div class="fw-medium text-dark">
                                    {{ $reservation->tanggal_formatted }}
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex align-items-center">
                                    <div class="avatar-sm bg-primary bg-opacity-10 rounded-circle d-flex align-items-center justify-content-center me-2">
                                        <i class="bi bi-person text-primary"></i>
                                    </div>
                                    <div>
                                        <div class="fw-medium text-dark">{{ $reservation->nama }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="py-3 d-none d-sm-table-cell">
                                <span class="text-muted">{{ $reservation->dinas->name ?? 'N/A' }}</span>
                            </td>
                            <td class="py-3">
                                <div class="text-muted">{{ $reservation->kontak }}</div>
                            </td>
                            <td class="py-3 d-none d-md-table-cell">
                                <span class="badge bg-light text-dark border">
                                    <i class="bi bi-clock me-1"></i>
                                    {{ $reservation->jam_range }}
                                </span>
                            </td>
                            <td class="py-3 d-none d-lg-table-cell">
                                <div class="text-muted" style="max-width: 200px;">
                                    {{ Str::limit($reservation->keperluan, 50) }}
                                    @if(strlen($reservation->keperluan) > 50)
                                        <i class="bi bi-info-circle text-muted ms-1" 
                                           data-bs-toggle="tooltip" 
                                           title="{{ $reservation->keperluan }}"></i>
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
                                        $config = $statusConfig[$reservation->status] ?? ['class' => 'secondary', 'icon' => 'question', 'text' => 'Unknown'];
                                    @endphp
                                    
                                    <span class="badge bg-{{ $config['class'] }} d-flex align-items-center gap-1">
                                        <i class="bi bi-{{ $config['icon'] }}"></i>
                                        {{ $config['text'] }}
                                    </span>
                                    
                                    @if($reservation->status == 'rejected' && $reservation->rejection_reason)
                                        <i class="bi bi-info-circle text-muted cursor-pointer" 
                                           data-bs-toggle="tooltip" 
                                           title="Alasan: {{ $reservation->rejection_reason }}"></i>
                                    @endif
                                    
                                    @if($reservation->status == 'canceled')
                                        <i class="bi bi-info-circle text-muted cursor-pointer" 
                                           data-bs-toggle="tooltip" 
                                           title="Dibatalkan oleh user"></i>
                                    @endif
                                </div>
                            </td>
                            <td class="py-3">
                                <div class="d-flex flex-wrap gap-1 justify-content-center">
                                    @if($reservation->status == 'pending')
                                        <!-- Approve Button -->
                                        <form method="POST" action="{{ route('admin.reservations.update-status', $reservation->id) }}" class="d-inline">
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="approved">
                                            <button type="submit" class="btn btn-sm btn-success d-flex align-items-center" 
                                                    data-bs-toggle="tooltip" title="Approve Reservasi">
                                                <i class="bi bi-check-lg me-1"></i>
                                                <span class="d-none d-md-inline">Approve</span>
                                            </button>
                                        </form>

                                        <!-- Reject Button -->
                                        <button type="button" class="btn btn-sm btn-danger d-flex align-items-center reject-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#rejectModal"
                                                data-reservation-id="{{ $reservation->id }}"
                                                data-reservation-name="{{ $reservation->nama }}"
                                                title="Reject Reservasi">
                                            <i class="bi bi-x-lg me-1"></i>
                                            <span class="d-none d-md-inline">Reject</span>
                                        </button>
                                    @endif

                                    @if($reservation->status == 'approved' && $reservation->admin_id)
                                        <!-- Check Out Button -->
                                        <form method="POST" action="{{ route('admin.reservations.checkout', $reservation->id) }}" class="d-inline">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-info d-flex align-items-center" 
                                                    data-bs-toggle="tooltip" title="Check Out">
                                                <i class="bi bi-box-arrow-right me-1"></i>
                                                <span class="d-none d-md-inline">Check Out</span>
                                            </button>
                                        </form>
                                    @endif
                                    
                                    <!-- Delete Button -->
                                    <button type="button" class="btn btn-sm btn-outline-danger d-flex align-items-center" 
                                            data-bs-toggle="modal" 
                                            data-bs-target="#confirmDeleteModal"
                                            data-url="{{ route('admin.reservations.destroy', $reservation->id) }}"
                                            data-message="Apakah Anda yakin ingin menghapus reservasi oleh {{ $reservation->nama }} pada tanggal {{ $reservation->tanggal_formatted }}?"
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
                                    <p class="text-muted mb-3">Belum ada reservasi yang dibuat</p>
                                    <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary">
                                        <i class="bi bi-plus-circle me-2"></i>
                                        Buat Reservasi Pertama
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

    <!-- Pagination (if needed) -->
    @if(method_exists($reservations, 'links'))
        <div class="d-flex justify-content-center mt-4">
            {{ $reservations->links() }}
        </div>
    @endif
</div>

<!-- Reject Modal -->
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

<!-- Custom Styles -->
<style>
    .avatar-sm {
        width: 32px;
        height: 32px;
        font-size: 0.875rem;
    }

    .cursor-pointer {
        cursor: pointer;
    }

    .table-hover tbody tr:hover {
        background-color: rgba(0, 0, 0, 0.02);
    }

    .card {
        border-radius: 12px;
    }

    .btn {
        border-radius: 8px;
        font-weight: 500;
    }

    .badge {
        font-size: 0.75rem;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
    }

    .modal-content {
        border-radius: 16px;
    }

    .btn-sm {
        padding: 0.375rem 0.75rem;
        font-size: 0.8125rem;
    }

    @media (max-width: 768px) {
        .container-fluid {
            padding-left: 1rem;
            padding-right: 1rem;
        }
        
        .card-body {
            padding: 0.5rem;
        }
        
        .table td, .table th {
            padding: 0.5rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    // Initialize tooltips
    const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Reject modal functionality
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

        // Reset form when modal is hidden
        rejectModal.addEventListener('hidden.bs.modal', function (event) {
            const form = rejectModal.querySelector('#rejectForm');
            const textarea = form.querySelector('#rejection_reason');
            textarea.value = '';
        });
    }

    // Add loading state to buttons
    const forms = document.querySelectorAll('form');
    forms.forEach(form => {
        form.addEventListener('submit', function() {
            const submitBtn = form.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = '<i class="bi bi-hourglass-split me-2"></i>Processing...';
                
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }, 5000);
            }
        });
    });

    // Auto-refresh functionality (optional)
    let autoRefreshInterval;
    const refreshBtn = document.querySelector('.btn-outline-secondary');
    
    if (refreshBtn) {
        refreshBtn.addEventListener('click', function() {
            location.reload();
        });
    }
});
</script>
@endsection