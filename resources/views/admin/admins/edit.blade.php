@extends('layouts.admin')

@section('title', 'Edit Admin')

@section('content')
<div class="container py-3 py-md-4">
    <h2 class="mb-3">Edit Admin: {{ $admin->username }}</h2>

    <div class="card">
        <div class="card-body p-3 p-md-4">
            <form method="POST" action="{{ route('admin.admins.update', $admin->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="username" class="form-label">Username</label>
                    <input type="text" class="form-control form-control-lg @error('username') is-invalid @enderror"
                           id="username" name="username" value="{{ old('username', $admin->username) }}" required>
                    @error('username')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-3">
                    <label for="role" class="form-label">Role</label>
                    <select class="form-select form-select-lg @error('role') is-invalid @enderror" id="role" name="role" required>
                        <option value="admin" {{ old('role', $admin->role) == 'admin' ? 'selected' : '' }}>Admin</option>
                        <option value="superadmin" {{ old('role', $admin->role) == 'superadmin' ? 'selected' : '' }}>Superadmin</option>
                    </select>
                    @error('role')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <hr class="my-4">
                <p class="text-muted mb-4">Kosongkan password jika tidak ingin mengubahnya.</p>

                <div class="mb-3">
                    <label for="password" class="form-label">Password Baru</label>
                    <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror"
                           id="password" name="password">
                    @error('password')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                    <input type="password" class="form-control form-control-lg"
                           id="password_confirmation" name="password_confirmation">
                </div>

                <div class="d-grid gap-2 d-md-flex">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1 py-2">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.admins.index') }}" class="btn btn-secondary btn-lg flex-grow-1 py-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection