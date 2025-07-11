@extends('layouts.admin')

@section('title', 'Manajemen Admin')

@section('content')
<div class="container py-3 py-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
        <h2 class="mb-3 mb-md-0">Manajemen Admin</h2>
        <a href="{{ route('admin.admins.create') }}" class="btn btn-primary btn-lg py-2">
            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Admin
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Username</th>
                            <th class="text-center">Role</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($admins as $admin)
                        <tr>
                            <td>{{ $admin->username }}</td>
                            <td class="text-center">
                                <span class="badge bg-{{ $admin->role == 'superadmin' ? 'danger' : 'success' }}">
                                    {{ ucfirst($admin->role) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                                    <a href="{{ route('admin.admins.edit', $admin->id) }}"
                                       class="btn btn-warning btn-sm flex-grow-1">
                                        <i class="bi bi-pencil-fill me-1"></i> Edit
                                    </a>
                                    {{-- Tombol Hapus hanya muncul jika admin yang akan dihapus bukan superadmin terakhir --}}
                                    @if(Auth::guard('admin')->id() !== $admin->id)
                                        <button type="button" class="btn btn-danger btn-sm flex-grow-1"
                                                data-bs-toggle="modal"
                                                data-bs-target="#confirmDeleteModal"
                                                data-url="{{ route('admin.admins.destroy', $admin->id) }}"
                                                data-message="Apakah Anda yakin ingin menghapus admin '{{ $admin->username }}'?">
                                            <i class="bi bi-trash-fill me-1"></i> Hapus
                                        </button>
                                    @endif
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="3" class="text-center py-4">
                                Tidak ada data admin.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $admins->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection