@extends('layouts.app')

@section('title', 'Reservasi Saya')

@section('content')
<div class="card">
    <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
        <h5 class="mb-0">
            <i class="bi bi-journal-text me-2 text-primary"></i>
            Reservasi Saya
        </h5>
        <a href="{{ route('reservations.create') }}" class="btn btn-sm btn-primary">
            <i class="bi bi-plus-circle-fill me-1"></i> Buat Reservasi Baru
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
                        <tr>
                            <td>{{ $reservation->tanggal->isoFormat('dddd, D MMMM Y') }}</td>
                            <td>{{ $reservation->jam_range }}</td>
                            <td>{{ \Illuminate\Support\Str::limit($reservation->keperluan, 60) }}</td>
                            <td class="text-center">
                                <span class="badge rounded-pill
                                    @if($reservation->status == 'approved') bg-success-subtle text-success-emphasis border border-success-subtle
                                    @elseif($reservation->status == 'pending') bg-warning-subtle text-warning-emphasis border border-warning-subtle
                                    @elseif($reservation->status == 'rejected') bg-danger-subtle text-danger-emphasis border border-danger-subtle
                                    @else bg-secondary-subtle text-secondary-emphasis border border-secondary-subtle @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                            <td class="text-center">
                                @if($reservation->status == 'pending')
                                <form method="POST" action="{{ route('user.reservations.cancel', $reservation->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?');">
                                    @csrf
                                    @method('PATCH')
                                    <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Batalkan Reservasi">
                                        <i class="bi bi-x-circle"></i>
                                    </button>
                                </form>
                                @else
                                    -
                                @endif
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            
            <div class="d-flex justify-content-end mt-4">
                {{ $reservations->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

@section('scripts')
<script>
    // Inisialisasi tooltip Bootstrap
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
      return new bootstrap.Tooltip(tooltipTriggerEl)
    })
</script>
@endsection