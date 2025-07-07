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
                                    @if($reservation->status == 'pending' || $reservation->status == 'approved')
                                    <form method="POST" action="{{ route('user.reservations.cancel', $reservation->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?{{ $reservation->status == 'approved' ? ' Admin akan diberitahu.' : '' }}');">
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
            </div>
            
            <div class="d-block d-md-none">
                <div class="list-group">
                    @foreach($reservations as $reservation)
                    <a href="{{ route('reservations.show', $reservation->id) }}" class="list-group-item list-group-item-action">
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
                                @if($reservation->status == 'rejected' && $reservation->rejection_reason)
                                    <i class="bi bi-info-circle text-danger ms-1" 
                                       data-bs-toggle="tooltip" 
                                       data-bs-placement="top"
                                       title="Alasan: {{ $reservation->rejection_reason }}">
                                    </i>
                                @endif
                            </div>
                        </div>
                        <div class="mt-2">
                            <i class="bi bi-clock me-1"></i> {{ $reservation->jam_range }}
                        </div>
                        <div class="mt-2 text-truncate">
                            <i class="bi bi-card-text me-1"></i> {{ $reservation->keperluan }}
                        </div>
                        @if($reservation->status == 'pending' || $reservation->status == 'approved')
                        <div class="mt-3 d-flex justify-content-end">
                            <form method="POST" action="{{ route('user.reservations.cancel', $reservation->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin membatalkan reservasi ini?{{ $reservation->status == 'approved' ? ' Admin akan diberitahu.' : '' }}');">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-outline-danger" data-bs-toggle="tooltip" title="Batalkan Reservasi">
                                    <i class="bi bi-x-circle me-1"></i> Batalkan
                                </button>
                            </form>
                        </div>
                        @endif
                    </a>
                    @endforeach
                </div>
            </div>
            
            <div class="d-flex justify-content-center justify-content-md-end mt-4">
                {{ $reservations->links('pagination::bootstrap-5') }}            </div>
        @endif
    </div>
</div>
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
    
    @media (max-width: 767.98px) {
        .card-header h5 {
            font-size: 1.1rem;
        }
        
        .card-header .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
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
    });
</script>
@endsection