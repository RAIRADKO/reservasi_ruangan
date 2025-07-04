@extends('layouts.admin')

@section('title', 'Manajemen Instansi/Dinas')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Manajemen Instansi/Dinas</h2>
        <a href="{{ route('admin.dinas.create') }}" class="btn btn-primary">
            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Instansi/Dinas
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-striped table-hover">
                    <thead>
                        <tr>
                            <th>Nama Instansi/Dinas</th>
                            <th>Jumlah Reservasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dinas as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td>{{ $item->reservations_count }}</td>
                            <td class="text-center">
                                <div class="d-flex justify-content-center gap-2">
                                    <a href="{{ route('admin.dinas.edit', $item->id) }}" class="btn btn-sm btn-warning">
                                        <i class="bi bi-pencil-fill"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.dinas.destroy', $item->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
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
                            <td colspan="3" class="text-center">Tidak ada data instansi/dinas.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-end mt-3">
                {{ $dinas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection