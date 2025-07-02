@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Reservasi Saya</h5>
            <a href="{{ route('reservations.create') }}" class="btn btn-sm btn-success">
                <i class="bi bi-plus-circle"></i> Buat Reservasi Baru
            </a>
        </div>
        
        <div class="card-body">
            @if (session('success'))
                <div class="alert alert-success alert-dismissible fade show">
                    {{ session('success') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif
            
            @if (session('error'))
                <div class="alert alert-danger alert-dismissible fade show">
                    {{ session('error') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($reservations->isEmpty())
                <div class="alert alert-info">
                    Anda belum memiliki reservasi.
                    <a href="{{ route('reservations.create') }}" class="alert-link">Buat reservasi baru sekarang</a>
                </div>
            @else
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead class="table-light">
                            <tr>
                                <th>Tanggal</th>
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
                                <td>{{ $reservation->jam_range }}</td>
                                <td>{{ \Illuminate\Support\Str::limit($reservation->keperluan, 50) }}</td>
                                <td>
                                    <span class="badge 
                                        @if($reservation->status == 'approved') bg-success
                                        @elseif($reservation->status == 'pending') bg-warning text-dark
                                        @else bg-danger @endif">
                                        {{ ucfirst($reservation->status) }}
                                    </span>
                                </td>
                                <td>
                                    @if($reservation->status == 'pending')
                                    <form method="POST" action="{{ route('user.reservations.cancel', $reservation->id) }}">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <i class="bi bi-x-circle"></i> Batalkan
                                        </button>
                                    </form>
                                    @endif
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                
                <div class="d-flex justify-content-end mt-3">
                    {{ $reservations->links() }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection