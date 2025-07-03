@extends('layouts.admin')

@section('title', 'Manajemen Ruangan')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manajemen Ruangan</h2>
        <a href="{{ route('admin.room.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Ruangan
        </a>
    </div>

    <div class="card">
        <div class="card-body">
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
            <div class="d-flex justify-content-end mt-3">
                {{ $rooms->links() }}
            </div>
        </div>
    </div>
</div>
@endsection