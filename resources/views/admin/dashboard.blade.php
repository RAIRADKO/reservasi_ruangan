@extends('layouts.admin')

@section('content')
<div class="container">
    <h2 class="mb-4">Dashboard Admin</h2>
    
    <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4 g-4 mb-4">
        <div class="col">
            <div class="card bg-warning text-white shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-bold fs-4 mb-1">{{ $pendingCount }}</h5>
                            <p class="card-text mb-0">Reservasi Pending</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-hourglass-split fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card bg-success text-white shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-bold fs-4 mb-1">{{ $approvedCount }}</h5>
                            <p class="card-text mb-0">Reservasi Disetujui</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-check-circle fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card bg-primary text-white shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-bold fs-4 mb-1">{{ $completedCount ?? 0 }}</h5>
                            <p class="card-text mb-0">Reservasi Selesai</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-check-all fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card bg-info text-white shadow-sm">
                <div class="card-body p-3">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h5 class="card-title fw-bold fs-4 mb-1">{{ $userCount }}</h5>
                            <p class="card-text mb-0">Total Pengguna</p>
                        </div>
                        <div class="bg-white bg-opacity-25 rounded-circle p-3">
                            <i class="bi bi-people fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center border-bottom">
            <h5 class="mb-0">Reservasi Terbaru</h5>
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-primary">
                Lihat Semua
            </a>
        </div>
        
        <div class="d-none d-md-block">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama</th>
                            <th>Jam</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($reservations as $reservation)
                        <tr>
                            <td>{{ $reservation->tanggal_formatted }}</td>
                            <td>{{ $reservation->nama }}</td>
                            <td>{{ $reservation->jam_range }}</td>
                            <td>
                                <span class="badge rounded-pill
                                    @if($reservation->status == 'approved') bg-success-subtle text-success-emphasis
                                    @elseif($reservation->status == 'pending') bg-warning-subtle text-warning-emphasis
                                    @elseif($reservation->status == 'completed') bg-primary-subtle text-primary-emphasis
                                    @else bg-danger-subtle text-danger-emphasis @endif">
                                    {{ ucfirst($reservation->status) }}
                                </span>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        
        <div class="d-block d-md-none">
            <div class="list-group list-group-flush">
                @foreach($reservations as $reservation)
                <div class="list-group-item border-bottom py-3">
                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <strong>{{ $reservation->nama }}</strong>
                        <span class="badge rounded-pill
                            @if($reservation->status == 'approved') bg-success-subtle text-success-emphasis
                            @elseif($reservation->status == 'pending') bg-warning-subtle text-warning-emphasis
                            @elseif($reservation->status == 'completed') bg-primary-subtle text-primary-emphasis
                            @else bg-danger-subtle text-danger-emphasis @endif">
                            {{ ucfirst($reservation->status) }}
                        </span>
                    </div>
                    <div class="d-flex justify-content-between">
                        <div>
                            <i class="bi bi-calendar me-1 text-muted"></i>
                            {{ $reservation->tanggal_formatted }}
                        </div>
                        <div>
                            <i class="bi bi-clock me-1 text-muted"></i>
                            {{ $reservation->jam_range }}
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .card {
        border-radius: 0.75rem;
        overflow: hidden;
        transition: transform 0.2s;
    }
    
    .card:hover {
        transform: translateY(-3px);
    }
    
    .list-group-item {
        border-radius: 0;
        padding: 1rem 1.25rem;
    }
    
    @media (max-width: 767.98px) {
        .card-header {
            padding: 0.75rem 1rem;
        }
        
        .card-header h5 {
            font-size: 1.1rem;
            margin-bottom: 0;
        }
        
        .card-header .btn {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
    }
</style>
@endsection