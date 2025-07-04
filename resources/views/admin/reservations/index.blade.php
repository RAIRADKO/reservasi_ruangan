@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Manajemen Reservasi</h2>
    
    <div class="table-responsive">
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Tanggal</th>
                    <th>Nama</th>
                    <th>Instansi</th>
                    <th>Kontak</th>
                    <th>Jam</th>
                    <th>Keperluan</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @foreach($reservations as $reservation)
                <tr>
                    <td>{{ $reservation->tanggal_formatted }}</td>
                    <td>{{ $reservation->nama }}</td>
                    <td>{{ $reservation->dinas->name ?? 'N/A' }}</td>
                    <td>{{ $reservation->kontak }}</td>
                    <td>{{ $reservation->jam_range }}</td>
                    <td>{{ $reservation->keperluan }}</td>
                    <td>
                        <span class="badge bg-{{ $reservation->status == 'approved' ? 'success' : ($reservation->status == 'pending' ? 'warning' : 'danger') }}">
                            {{ $reservation->status }}
                        </span>
                        {{-- Menampilkan ikon info jika ada alasan penolakan --}}
                        @if($reservation->status == 'rejected' && $reservation->rejection_reason)
                            <i class="bi bi-info-circle text-muted" data-bs-toggle="tooltip" title="Alasan: {{ $reservation->rejection_reason }}"></i>
                        @endif
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            {{-- Tombol Approve --}}
                            <form method="POST" action="{{ route('admin.reservations.update-status', $reservation->id) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-sm btn-success" {{ $reservation->status == 'approved' ? 'disabled' : '' }}>Approve</button>
                            </form>

                            {{-- Tombol Reject (Membuka Modal) --}}
                            <button type="button" class="btn btn-sm btn-danger reject-btn" 
                                    data-bs-toggle="modal" 
                                    data-bs-target="#rejectModal"
                                    data-reservation-id="{{ $reservation->id }}"
                                    data-reservation-name="{{ $reservation->nama }}"
                                    {{ $reservation->status == 'rejected' ? 'disabled' : '' }}>
                                Reject
                            </button>
                            
                            {{-- Tombol Hapus --}}
                            <form method="POST" action="{{ route('admin.reservations.destroy', $reservation->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus reservasi ini?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-outline-danger">Hapus</button>
                            </form>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
</div>

<div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
      </div>
      <form id="rejectForm" method="POST">
          @csrf
          @method('PUT')
          <div class="modal-body">
            <p>Anda akan menolak reservasi untuk <strong id="reservationName"></strong>.</p>
            <input type="hidden" name="status" value="rejected">
            <div class="mb-3">
              <label for="rejection_reason" class="col-form-label">Mohon berikan alasan penolakan:</label>
              <textarea class="form-control" id="rejection_reason" name="rejection_reason" required rows="4"></textarea>
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
        
        modalTitle.textContent = 'Alasan Penolakan untuk ' + reservationName;
        modalBodyReservationName.textContent = reservationName;
        rejectForm.action = actionUrl;
      });
  }

  // Inisialisasi tooltip untuk menampilkan alasan penolakan
  const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
  const tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
    return new bootstrap.Tooltip(tooltipTriggerEl)
  });
});
</script>
@endsection