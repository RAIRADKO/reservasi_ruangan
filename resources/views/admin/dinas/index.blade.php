@extends('layouts.admin')

@section('title', 'Manajemen Instansi/Dinas')

@section('content')
<div class="container py-3 py-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
        <h2 class="mb-3 mb-md-0">Manajemen Instansi/Dinas</h2>
        <a href="{{ route('admin.dinas.create') }}" class="btn btn-primary btn-lg py-2">
            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Instansi/Dinas
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama Instansi/Dinas</th>
                            <th class="text-center">Reservasi</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($dinas as $item)
                        <tr>
                            <td>{{ $item->name }}</td>
                            <td class="text-center">{{ $item->reservations_count }}</td>
                            <td>
                                <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                                    <a href="{{ route('admin.dinas.edit', $item->id) }}" 
                                       class="btn btn-warning btn-sm flex-grow-1">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit
                                    </a>
                                    <form method="POST" action="{{ route('admin.dinas.destroy', $item->id) }}" 
                                          class="d-flex flex-grow-1" 
                                          onsubmit="return confirm('Apakah Anda yakin ingin menghapus data ini?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-danger btn-sm w-100">
                                            <i class="bi bi-trash-fill me-1"></i> Hapus
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">
                                Tidak ada data instansi/dinas.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $dinas->links() }}
            </div>
        </div>
    </div>
</div>
@endsection