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
                    </td>
                    <td class="text-center">
                        <div class="d-flex justify-content-center gap-2">
                            <form method="POST" action="{{ route('admin.reservations.update-status', $reservation->id) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="approved">
                                <button type="submit" class="btn btn-sm btn-success" {{ $reservation->status == 'approved' ? 'disabled' : '' }}>Approve</button>
                            </form>
                            <form method="POST" action="{{ route('admin.reservations.update-status', $reservation->id) }}" class="d-inline">
                                @csrf
                                @method('PUT')
                                <input type="hidden" name="status" value="rejected">
                                <button type="submit" class="btn btn-sm btn-danger" {{ $reservation->status == 'rejected' ? 'disabled' : '' }}>Reject</button>
                            </form>
                            <form method="POST" action="{{ route('admin.reservations.destroy', $reservation->id) }}" class="d-inline">
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
@endsection