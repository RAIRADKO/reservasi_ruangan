@extends('layouts.admin')

@section('title', 'Manajemen Ruangan')

@section('content')
<div class="container">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-4">
        <h2 class="mb-3 mb-md-0">Manajemen Ruangan</h2>
        <a href="{{ route('admin.room.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-1"></i> 
            <span class="d-none d-md-inline">Tambah Ruangan</span>
            <span class="d-md-none">Tambah</span>
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <div class="d-none d-md-block">
                <div class="table-responsive">
                    <table class="table table-striped table-hover">
                        <thead>
                            <tr>
                                <th>Nama Ruangan</th>
                                <th>Kapasitas</th>
                                <th>Fasilitas</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rooms as $room)
                            <tr>
                                <td>{{ $room->nama_ruangan }}</td>
                                <td>{{ $room->kapasitas }} orang</td>
                                <td>{{ \Illuminate\Support\Str::limit($room->fasilitas, 50) }}</td>
                                <td class="text-center">
                                    <div class="d-flex justify-content-center gap-2">
                                        <a href="{{ route('admin.room.edit', $room->id) }}" class="btn btn-sm btn-warning">
                                            <i class="bi bi-pencil-fill"></i> Edit
                                        </a>
                                        <form method="POST" action="{{ route('admin.room.destroy', $room->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini? Semua reservasi terkait akan dihapus.');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="bi bi-trash-fill"></i> Hapus
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            @empty
                            <tr>
                                <td colspan="4" class="text-center">Tidak ada data ruangan.</td>
                            </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="d-block d-md-none">
                <div class="list-group">
                    @forelse($rooms as $room)
                    <div class="list-group-item">
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <h5 class="mb-0">{{ $room->nama_ruangan }}</h5>
                            <span class="badge bg-primary rounded-pill">{{ $room->kapasitas }} orang</span>
                        </div>
                        
                        <div class="mb-3">
                            <p class="mb-1 text-muted">Fasilitas:</p>
                            <p>{{ \Illuminate\Support\Str::limit($room->fasilitas, 100) }}</p>
                        </div>
                        
                        <div class="d-flex justify-content-end gap-2">
                            <a href="{{ route('admin.room.edit', $room->id) }}" class="btn btn-sm btn-warning">
                                <i class="bi bi-pencil-fill"></i>
                            </a>
                            <form method="POST" action="{{ route('admin.room.destroy', $room->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus ruangan ini? Semua reservasi terkait akan dihapus.');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">
                                    <i class="bi bi-trash-fill"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-4">
                        <i class="bi bi-door-closed text-muted" style="font-size: 3rem;"></i>
                        <p class="mt-2">Tidak ada data ruangan.</p>
                    </div>
                    @endforelse
                </div>
            </div>
            
            <div class="d-flex justify-content-center mt-4">
                {{ $rooms->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection