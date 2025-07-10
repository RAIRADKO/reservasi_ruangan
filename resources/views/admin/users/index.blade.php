@extends('layouts.admin')

@section('title', 'Manajemen Pengguna')

@section('content')
<div class="container py-3 py-md-4">
    <div class="d-flex flex-column flex-md-row justify-content-between align-items-center mb-3">
        <h2 class="mb-3 mb-md-0">Manajemen Pengguna</h2>
        <a href="{{ route('admin.users.create') }}" class="btn btn-primary btn-lg py-2">
            <i class="bi bi-plus-circle-fill me-1"></i> Tambah Pengguna
        </a>
    </div>

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Nama</th>
                            <th>NIP</th>
                            <th class="d-none d-md-table-cell">Email</th>
                            <th class="text-center">Status</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($users as $user)
                        <tr>
                            <td>{{ $user->name }}</td>
                            <td class="small">{{ $user->formatted_nip }}</td>
                            <td class="d-none d-md-table-cell small">{{ $user->email }}</td>
                             <td class="text-center">
                                <span class="badge bg-{{ $user->status == 'approved' ? 'success' : ($user->status == 'pending' ? 'warning' : 'danger') }}">
                                    {{ ucfirst($user->status) }}
                                </span>
                            </td>
                            <td>
                                <div class="d-flex flex-column flex-md-row gap-2 justify-content-center">
                                    @if($user->status === 'pending')
                                    <form action="{{ route('admin.users.approve', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-success btn-sm flex-grow-1 w-100">Approve</button>
                                    </form>
                                     <form action="{{ route('admin.users.reject', $user) }}" method="POST" class="d-inline">
                                        @csrf
                                        @method('PUT')
                                        <button type="submit" class="btn btn-secondary btn-sm flex-grow-1 w-100">Reject</button>
                                    </form>
                                    @endif
                                    <a href="{{ route('admin.users.edit', $user->id) }}" 
                                       class="btn btn-warning btn-sm flex-grow-1">
                                        <i class="bi bi-pencil-fill me-1 d-none d-md-inline"></i> Edit
                                    </a>
                                    <button type="button" class="btn btn-danger btn-sm flex-grow-1"
                                            data-bs-toggle="modal"
                                            data-bs-target="#confirmDeleteModal"
                                            data-url="{{ route('admin.users.destroy', $user->id) }}"
                                            data-message="Apakah Anda yakin ingin menghapus pengguna '{{ $user->name }}'?">
                                        <i class="bi bi-trash-fill me-1 d-none d-md-inline"></i> Hapus
                                    </button>
                                </div>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="text-center py-4">
                                Tidak ada data pengguna.
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            <div class="d-flex justify-content-center mt-3 p-3">
                {{ $users->links('pagination::bootstrap-5') }}
            </div>
        </div>
    </div>
</div>
@endsection