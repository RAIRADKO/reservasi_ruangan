@extends('layouts.app')

@section('content')
<div class="row justify-content-center">
    <div class="col-md-8">
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Profil Pengguna</h5>
                <a href="{{ route('home') }}" class="btn btn-sm btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>

            <div class="card-body">
                <div class="row align-items-center">
                    <div class="col-md-3 text-center">
                         <i class="bi bi-person-circle display-1 text-primary"></i>
                    </div>
                    <div class="col-md-9">
                        <div class="mb-3">
                            <label class="form-label text-muted small">Nama Lengkap</label>
                            <p class="fs-5 fw-bold">{{ $user->name }}</p>
                        </div>
                        <div class="mb-3">
                            <label class="form-label text-muted small">NIP</label>
                            <p class="fs-5">{{ $user->formatted_nip }}</p>
                        </div>
                         <div class="mb-3">
                            <label class="form-label text-muted small">Email</label>
                            <p class="fs-5">{{ $user->email }}</p>
                        </div>
                    </div>
                </div>

                <hr>

                <div class="d-grid mt-4">
                    <a href="{{ route('user.reservations') }}" class="btn btn-primary">
                        <i class="bi bi-calendar-check-fill me-2"></i> Lihat Semua Reservasi Saya
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection