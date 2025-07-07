@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    {{-- Card Informasi Profil --}}
    <div class="col-12 col-md-8 mb-4">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3 d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Profil Pengguna</h5>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left me-1 d-none d-md-inline"></i>
                    <span class="d-md-none">Kembali</span>
                    <span class="d-none d-md-inline">Kembali</span>
                </a>
            </div>

            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-12 col-md-3 text-center mb-4 mb-md-0">
                         <i class="bi bi-person-circle display-1 text-primary"></i>
                    </div>
                    <div class="col-12 col-md-9">
                        <div class="mb-3 border-bottom pb-2">
                            <label class="form-label text-muted small">Nama Lengkap</label>
                            <p class="fs-5 fw-bold mb-0">{{ $user->name }}</p>
                        </div>
                        <div class="mb-3 border-bottom pb-2">
                            <label class="form-label text-muted small">NIP</label>
                            <p class="fs-5 mb-0">{{ $user->formatted_nip }}</p>
                        </div>
                         <div class="mb-3">
                            <label class="form-label text-muted small">Email</label>
                            <p class="fs-5 mb-0">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <hr class="my-4">

                <div class="d-grid">
                    <a href="{{ route('user.reservations') }}" class="btn btn-primary py-2">
                        <i class="bi bi-calendar-check-fill me-2"></i> 
                        Lihat Semua Reservasi Saya
                    </a>
                </div>
            </div>
        </div>
    </div>

    {{-- Card Ganti Password --}}
    <div class="col-12 col-md-8">
        <div class="card shadow-sm border-0">
            <div class="card-header bg-white py-3">
                <h5 class="mb-0">
                    <i class="bi bi-key-fill me-2 text-primary"></i>
                    Ganti Password
                </h5>
            </div>
            <div class="card-body">
                <form method="POST" action="{{ route('user.password.update') }}">
                    @csrf
                    @method('PATCH')

                    {{-- Menampilkan pesan sukses --}}
                    @if (session('password_success'))
                        <div class="alert alert-success">
                            {{ session('password_success') }}
                        </div>
                    @endif

                    {{-- Menampilkan pesan error --}}
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label for="current_password" class="form-label">Password Saat Ini</label>
                        <input type="password" name="current_password" id="current_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password" class="form-label">Password Baru</label>
                        <input type="password" name="new_password" id="new_password" class="form-control" required>
                    </div>
                    <div class="mb-3">
                        <label for="new_password_confirmation" class="form-label">Konfirmasi Password Baru</label>
                        <input type="password" name="new_password_confirmation" id="new_password_confirmation" class="form-control" required>
                    </div>
                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-save-fill me-2"></i>
                            Simpan Password Baru
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection