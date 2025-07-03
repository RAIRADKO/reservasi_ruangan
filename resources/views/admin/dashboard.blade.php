@extends('layouts.admin')

@section('content')
<div class="container">
    <h2>Dashboard Admin</h2>
    <div class="row">
        <div class="col-md-3">
            <div class="card text-white bg-primary mb-3">
                <div class="card-header">Reservasi Pending</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $pendingCount }}</h5>
                    <p class="card-text">Menunggu persetujuan</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-white bg-success mb-3">
                <div class="card-header">Reservasi Disetujui</div>
                <div class="card-body">
                    <h5 class="card-title">{{ $approvedCount }}</h5>
                    <p class="card-text">Telah disetujui</p>
                </div>
            </div>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header">
            <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-primary float-end">Lihat Semua</a>
            Reservasi Terbaru
        </div>
        <div class="card-body">
            <table class="table table-striped">
                <thead>
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
                            <span class="badge bg-{{ $reservation->status == 'approved' ? 'success' : ($reservation->status == 'pending' ? 'warning' : 'danger') }}">
                                {{ $reservation->status }}
                            </span>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection