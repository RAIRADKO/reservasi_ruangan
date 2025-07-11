@extends('layouts.admin')

@section('content')
<div class="container py-2 py-md-3">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3 mb-md-4 gap-3">
        <h2 class="mb-0 h4">Manajemen Reservasi</h2>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.reservations.create') }}" class="btn btn-primary btn-sm">
                <i class="bi bi-plus-circle-fill me-1"></i>
                Buat Reservasi
            </a>
            <a href="{{ route('admin.reservations.export') }}" class="btn btn-success btn-sm">
                <i class="bi bi-file-earmark-excel-fill me-1"></i>
                Export
            </a>
        </div>
    </div>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th class="d-none d-sm-table-cell">Instansi</th>
                    <th>Kontak</th>
                    <th class="d-none d-md-table-cell">Jam</th>
                    <th class="d-none d-lg-table-cell">Keperluan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                <tr>
                    <td class="small">{{ $reservation->tanggal_formatted }}</td>
                    <td class="small">{{ $reservation->nama }}</td>
                    <td class="d-none d-sm-table-cell small">{{ $reservation->dinas->name ?? 'N/A' }}</td>
                    <td class="small">{{ $reservation->kontak }}</td>
                    <td class="d-none d-md-table-cell small">{{ $reservation->jam_range }}</td>
                    <td class="d-none d-lg-table-cell small">{{ $reservation->keperluan }}</td>
                    <td>
                        <span class="badge bg-{{ $reservation->status == 'approved' ? 'success' : ($reservation->status == 'pending' ? 'warning' : ($reservation->status == 'completed' ? 'primary' : ($reservation->status == 'canceled' ? 'secondary' : 'danger'))) }} small">
                            {{ ucfirst($reservation->status == 'canceled' ? 'Canceled' : $reservation->status) }}
                        </span>
                        @if($reservation->status == 'rejected' && $reservation->rejection_reason)
                            <i class="bi bi-info-circle text-muted" data-bs-toggle="tooltip" title="Alasan: {{ $reservation->rejection_reason }}"></i>
                        @endif
                        @if($reservation->status == 'canceled')
                            <i class="bi bi-info-circle text-muted" data-bs-toggle="tooltip" title="Dibatalkan oleh user"></i>
                        @endif
                    </td>
                    <td>
                        <div class="d-flex flex-column flex-md-row gap-1 justify-content-center">
                            @php
                                $isPast = \Carbon\Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->jam_selesai)->isPast();
                            @endphp

                            @if($reservation->status == 'approved' && $isPast && is_null($reservation->checked_out_at))
                                {{-- Tombol Check Out --}}
                                <button type="button" class="btn btn-sm btn-info checkout-btn"
                                        data-bs-toggle="modal"
                                        data-bs-target="#checkoutModal"
                                        data-reservation-id="{{ $reservation->id }}"
                                        data-reservation-name="{{ $reservation->nama }}">
                                    <span class="d-none d-md-inline">Check Out</span>
                                    <i class="bi bi-box-arrow-right d-md-none"></i>
                                </button>
                            @endif

                            @if($reservation->status == 'pending')
                                {{-- Tombol Approve --}}
                                <form method="POST" action="{{ route('admin.reservations.update-status', $reservation->id) }}" class="d-grid">
                                    @csrf
                                    @method('PUT')
                                    <input type="hidden" name="status" value="approved">
                                    <button type="submit" class="btn btn-sm btn-success">
                                        <span class="d-none d-md-inline">Approve</span>
                                        <i class="bi bi-check-lg d-md-none"></i>
                                    </button>
                                </form>

                                {{-- Tombol Reject --}}
                                <button type="button" class="btn btn-sm btn-danger reject-btn" 
                                        data-bs-toggle="modal" 
                                        data-bs-target="#rejectModal"
                                        data-reservation-id="{{ $reservation->id }}"
                                        data-reservation-name="{{ $reservation->nama }}">
                                    <span class="d-none d-md-inline">Reject</span>
                                    <i class="bi bi-x-lg d-md-none"></i>
                                </button>
                            @endif
                            
                            {{-- Tombol Hapus --}}
                            <button type="button" class="btn btn-sm btn-outline-danger" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#confirmDeleteModal"
                                    data-url="{{ route('admin.reservations.destroy', $reservation->id) }}"
                                    data-message="Apakah Anda yakin ingin menghapus reservasi oleh {{ $reservation->nama }} pada tanggal {{ $reservation->tanggal_formatted }}?">
                                <span class="d-none d-md-inline">Hapus</span>
                                <i class="bi bi-trash d-md-none"></i>
                            </button>
                        </div>
{{-- Modal Checkout BARU --}}
<div class="modal fade" id="checkoutModal" tabindex="-1" aria-labelledby="checkoutModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="checkoutModalLabel">Konfirmasi Check Out</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="checkoutForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menyelesaikan (check out) reservasi untuk <strong id="checkoutReservationName"></strong>?</p>
                    <p class="text-muted small">Tindakan ini akan mengubah status reservasi menjadi "Completed".</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-info">Ya, Check Out</button>
                </div>
            </form>
        </div>
    </div>
</div>
@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  // ... (script untuk reject modal yang sudah ada) ...

  // Script BARU untuk modal checkout
  const checkoutModal = document.getElementById('checkoutModal');
  if (checkoutModal) {
      checkoutModal.addEventListener('show.bs.modal', function (event) {
          const button = event.relatedTarget;
          const reservationId = button.getAttribute('data-reservation-id');
          const reservationName = button.getAttribute('data-reservation-name');
          
          const modalReservationName = checkoutModal.querySelector('#checkoutReservationName');
          const checkoutForm = checkoutModal.querySelector('#checkoutForm');
          
          const actionUrl = `{{ url('admin/reservations') }}/${reservationId}/checkout`;
          
          modalReservationName.textContent = reservationName;
          checkoutForm.action = actionUrl;
      });
  }

  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });
});
</script>
@endsection
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

{{-- Reject Modal (anda bisa meletakkan modal di sini atau di layout utama) --}}
<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan untuk <span id="reservationName"></span></h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="rejectForm" method="POST" action="">
                @csrf
                @method('PUT')
                <div class="modal-body">
                    <input type="hidden" name="status" value="rejected">
                    <div class="mb-3">
                        <label for="rejection_reason" class="form-label">Mohon sebutkan alasan penolakan reservasi:</label>
                        <textarea class="form-control" id="rejection_reason" name="rejection_reason" rows="3" required></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-danger">Tolak Reservasi</button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
  const rejectModal = document.getElementById('rejectModal');
  if (rejectModal) {
      rejectModal.addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        const reservationId = button.getAttribute('data-reservation-id');
        const reservationName = button.getAttribute('data-reservation-name');
        
        const modalTitle = rejectModal.querySelector('.modal-title');
        const modalBodyReservationName = rejectModal.querySelector('#reservationName');
        const rejectForm = rejectModal.querySelector('#rejectForm');
        
        const actionUrl = `{{ url('admin/reservations') }}/${reservationId}/update-status`;
        
        modalTitle.textContent = 'Alasan Penolakan';
        modalBodyReservationName.textContent = reservationName;
        rejectForm.action = actionUrl;
      });
  }

  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });
});
</script>
@endsection