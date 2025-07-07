@extends('layouts.app')

@section('title', 'Reservasi Saya')

@section('content')
<div class="card shadow-sm border-0">
    <div class="card-header bg-white py-3 d-flex flex-column flex-md-row justify-content-between align-items-center">
        <h5 class="mb-3 mb-md-0">
            <i class="bi bi-journal-text me-2 text-primary"></i>
            Reservasi Saya
        </h5>
        <a href="{{ route('reservations.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle-fill me-1"></i> 
            <span class="d-none d-md-inline">Buat Reservasi Baru</span>
            <span class="d-md-none">Baru</span>
        </a>
    </div>
    
    <div class="card-body">
        @if ($reservations->isEmpty())
            <div class="text-center py-5">
                <i class="bi bi-calendar-x" style="font-size: 3rem; color: #6c757d;"></i>
                <h5 class="mt-3">Anda Belum Memiliki Reservasi</h5>
                <p class="text-muted">Sepertinya Anda belum pernah membuat reservasi.<br>Ayo buat reservasi pertama Anda!</p>
                <a href="{{ route('reservations.create') }}" class="btn btn-success mt-3">
                    <i class="bi bi-plus-circle"></i> Buat Reservasi Sekarang
                </a>
            </div>
        @else
            {{-- Tampilan Desktop --}}
            <div class="d-none d-md-block">
                <div class="table-responsive">
                    <table class="table table-hover align-middle">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
                                <th>Jam</th>
                                <th>Keperluan</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($reservations as $reservation)
                            @php
                                $isPast = \Carbon\Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->jam_selesai)->isPast();
                            @endphp
                            <tr class='clickable-row' data-href="{{ route('reservations.show', $reservation->id) }}">
                                <td>
                                    <a href="{{ route('reservations.show', $reservation->id) }}" class="text-decoration-none fw-bold">
                                        {{ $reservation->tanggal->isoFormat('dddd, D MMMM Y') }}
                                    </a>
                                </td>
                                <td>{{ $reservation->jam_range }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($reservation->keperluan, 60) }}</td>
                                <td class="text-center">
                                    <span class="badge rounded-pill
                                        @if($reservation->status == 'approved') bg-success-subtle text-success-emphasis border border-success-subtle
                                        @elseif($reservation->status == 'pending') bg-warning-subtle text-warning-emphasis border border-warning-subtle
                                        @elseif($reservation->status == 'rejected') bg-danger-subtle text-danger-emphasis border border-danger-subtle
                                        @elseif($reservation->status == 'completed') bg-primary-subtle text-primary-emphasis border border-primary-subtle
                                        @else bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                    @if($reservation->status == 'rejected' && $reservation->rejection_reason)
                                        <i class="bi bi-info-circle text-danger ms-1" 
                                           data-bs-toggle="tooltip" 
                                           data-bs-placement="top"
                                           title="Alasan: {{ $reservation->rejection_reason }}">
                                        </i>
                                    @endif
                                </td>
                                <td class="text-center">
                                    @if($reservation->status == 'approved' && $isPast)
                                        <a href="{{ route('user.reservations.checkout', $reservation->id) }}" class="btn btn-sm btn-info" data-bs-toggle="tooltip" title="Lakukan Check Out" onclick="event.stopPropagation()">
                                            <i class="bi bi-box-arrow-right"></i>
                                        </a>
                                    @elseif($reservation->status == 'pending' || ($reservation->status == 'approved' && !$isPast))
                                        <button type="button" class="btn btn-sm btn-outline-danger cancel-btn" 
                                                data-bs-toggle="modal" 
                                                data-bs-target="#cancelModal"
                                                data-reservation-id="{{ $reservation->id }}"
                                                data-reservation-date="{{ $reservation->tanggal->isoFormat('dddd, D MMMM Y') }}"
                                                data-reservation-time="{{ $reservation->jam_range }}"
                                                data-reservation-purpose="{{ $reservation->keperluan }}"
                                                data-reservation-status="{{ $reservation->status }}"
                                                data-cancel-url="{{ route('user.reservations.cancel', $reservation->id) }}"
                                                data-bs-toggle="tooltip" 
                                                title="Batalkan Reservasi"
                                                onclick="event.stopPropagation()">
                                            <i class="bi bi-x-circle"></i>
                                        </button>
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
            
            {{-- Tampilan Mobile --}}
            <div class="d-block d-md-none">
                <div class="list-group">
                    @foreach($reservations as $reservation)
                    @php
                        $isPast = \Carbon\Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->jam_selesai)->isPast();
                    @endphp
                    <div class="list-group-item list-group-item-action p-3">
                        <a href="{{ route('reservations.show', $reservation->id) }}" class="text-decoration-none text-dark d-block">
                            <div class="d-flex justify-content-between">
                                <div class="fw-bold">{{ $reservation->tanggal->isoFormat('D MMM Y') }}</div>
                                <div>
                                    <span class="badge rounded-pill
                                        @if($reservation->status == 'approved') bg-success-subtle text-success-emphasis border border-success-subtle
                                        @elseif($reservation->status == 'pending') bg-warning-subtle text-warning-emphasis border border-warning-subtle
                                        @elseif($reservation->status == 'rejected') bg-danger-subtle text-danger-emphasis border border-danger-subtle
                                        @else bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </div>
                            </div>
                            <div class="mt-2">
                                <i class="bi bi-clock me-1"></i> {{ $reservation->jam_range }}
                            </div>
                            <div class="mt-2 text-truncate">
                                <i class="bi bi-card-text me-1"></i> {{ $reservation->keperluan }}
                            </div>
                        </a>
                        <div class="mt-3 d-flex justify-content-end">
                             @if($reservation->status == 'approved' && $isPast)
                                <a href="{{ route('user.reservations.checkout', $reservation->id) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-box-arrow-right me-1"></i> Check Out
                                </a>
                            @elseif($reservation->status == 'pending' || ($reservation->status == 'approved' && !$isPast))
                                <button type="button" class="btn btn-sm btn-outline-danger cancel-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#cancelModal"
                                        data-reservation-id="{{ $reservation->id }}"
                                        data-reservation-date="{{ $reservation->tanggal->isoFormat('dddd, D MMMM Y') }}"
                                        data-reservation-time="{{ $reservation->jam_range }}"
                                        data-reservation-purpose="{{ $reservation->keperluan }}"
                                        data-reservation-status="{{ $reservation->status }}"
                                        data-cancel-url="{{ route('user.reservations.cancel', $reservation->id) }}">
                                    <i class="bi bi-x-circle me-1"></i> Batalkan
                                </button>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            
            <div class="d-flex justify-content-center justify-content-md-end mt-4">
                {{ $reservations->links('pagination::bootstrap-5') }}
            </div>
        @endif
    </div>
</div>

<!-- Cancel Confirmation Modal -->
<div class="modal fade" id="cancelModal" tabindex="-1" aria-labelledby="cancelModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header border-0">
                <h5 class="modal-title" id="cancelModalLabel">
                    <i class="bi bi-exclamation-triangle text-warning me-2"></i>
                    Konfirmasi Pembatalan
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="alert alert-warning d-flex align-items-center mb-3">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    <div>
                        <strong>Perhatian!</strong> Tindakan ini tidak dapat dibatalkan.
                    </div>
                </div>
                
                <p class="mb-3">Apakah Anda yakin ingin membatalkan reservasi berikut?</p>
                
                <div class="card bg-light">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-4 text-muted">Tanggal:</div>
                            <div class="col-8 fw-bold" id="modal-date">-</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4 text-muted">Jam:</div>
                            <div class="col-8" id="modal-time">-</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4 text-muted">Keperluan:</div>
                            <div class="col-8" id="modal-purpose">-</div>
                        </div>
                        <div class="row mt-2">
                            <div class="col-4 text-muted">Status:</div>
                            <div class="col-8">
                                <span class="badge" id="modal-status">-</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-3">
                    <p class="text-muted small mb-0" id="admin-notification" style="display: none;">
                        <i class="bi bi-bell text-info me-1"></i>
                        Admin akan diberitahu tentang pembatalan ini.
                    </p>
                </div>
            </div>
            <div class="modal-footer border-0">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                    <i class="bi bi-x-circle me-1"></i> Batal
                </button>
                <button type="button" class="btn btn-danger" id="confirmCancelBtn">
                    <i class="bi bi-trash me-1"></i> Ya, Batalkan Reservasi
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Form untuk submit cancel (hidden) -->
<form id="cancelForm" method="POST" style="display: none;">
    @csrf
    @method('PATCH')
</form>

@endsection

@section('styles')
<style>
    .clickable-row {
        cursor: pointer;
    }
    
    .list-group-item {
        border-radius: 0.5rem !important;
        margin-bottom: 0.75rem;
        box-shadow: 0 0.125rem 0.25rem rgba(0,0,0,0.075);
        border: 1px solid rgba(0,0,0,0.05);
    }
    
    .list-group-item:hover {
        background-color: #f8f9fa;
    }
    
    .modal-content {
        border-radius: 1rem;
        box-shadow: 0 1rem 3rem rgba(0,0,0,0.175);
    }
    
    .modal-header {
        background: linear-gradient(135deg, #fff 0%, #f8f9fa 100%);
        border-top-left-radius: 1rem;
        border-top-right-radius: 1rem;
    }
    
    .modal-footer {
        background-color: #f8f9fa;
        border-bottom-left-radius: 1rem;
        border-bottom-right-radius: 1rem;
    }
    
    @media (max-width: 767.98px) {
        .card-header h5 {
            font-size: 1.1rem;
        }
        
        .card-header .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .modal-dialog {
            margin: 1rem;
        }
    }
    
    @media (max-width: 575.98px) {
        .card-header h5 {
            font-size: 1rem;
        }
        
        .card-header .btn {
            padding: 0.2rem 0.4rem;
            font-size: 0.8rem;
        }
        
        .modal-dialog {
            margin: 0.5rem;
        }
    }
</style>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Inisialisasi tooltip Bootstrap
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
          return new bootstrap.Tooltip(tooltipTriggerEl)
        });

        // Membuat seluruh baris dapat diklik (desktop)
        const rows = document.querySelectorAll('.clickable-row');
        rows.forEach(row => {
            row.addEventListener('click', function() {
                window.location.href = row.dataset.href;
            });
        });

        // Handler untuk tombol cancel
        const cancelButtons = document.querySelectorAll('.cancel-btn');
        cancelButtons.forEach(button => {
            button.addEventListener('click', function() {
                const reservationId = this.dataset.reservationId;
                const reservationDate = this.dataset.reservationDate;
                const reservationTime = this.dataset.reservationTime;
                const reservationPurpose = this.dataset.reservationPurpose;
                const reservationStatus = this.dataset.reservationStatus;
                const cancelUrl = this.dataset.cancelUrl;
                
                // Populate modal dengan data reservasi
                document.getElementById('modal-date').textContent = reservationDate;
                document.getElementById('modal-time').textContent = reservationTime;
                document.getElementById('modal-purpose').textContent = reservationPurpose;
                
                // Set status badge
                const statusBadge = document.getElementById('modal-status');
                statusBadge.textContent = reservationStatus.charAt(0).toUpperCase() + reservationStatus.slice(1);
                statusBadge.className = 'badge ';
                
                if (reservationStatus === 'approved') {
                    statusBadge.className += 'bg-success-subtle text-success-emphasis border border-success-subtle';
                } else if (reservationStatus === 'pending') {
                    statusBadge.className += 'bg-warning-subtle text-warning-emphasis border border-warning-subtle';
                } else {
                    statusBadge.className += 'bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle';
                }
                
                // Show/hide admin notification
                const adminNotification = document.getElementById('admin-notification');
                if (reservationStatus === 'approved') {
                    adminNotification.style.display = 'block';
                } else {
                    adminNotification.style.display = 'none';
                }
                
                // Set form action - menggunakan URL yang sudah digenerate Laravel
                const cancelForm = document.getElementById('cancelForm');
                cancelForm.action = cancelUrl;
                
                // Handler untuk tombol konfirmasi
                document.getElementById('confirmCancelBtn').onclick = function() {
                    // Tampilkan loading state
                    this.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status"></span>Membatalkan...';
                    this.disabled = true;
                    
                    // Submit form
                    cancelForm.submit();
                };
            });
        });
        
        // Reset modal saat ditutup
        document.getElementById('cancelModal').addEventListener('hidden.bs.modal', function () {
            const confirmBtn = document.getElementById('confirmCancelBtn');
            confirmBtn.innerHTML = '<i class="bi bi-trash me-1"></i> Ya, Batalkan Reservasi';
            confirmBtn.disabled = false;
        });
    });
</script>
@endsection