@extends('layouts.app')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">Profil Pengguna</div>

                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label fw-bold">Nama Lengkap</label>
                        <p class="form-control-plaintext">{{ $user->name }}</p>
                    </div>
                    
                    <div class="mb-3">
                        <label class="form-label fw-bold">NIP</label>
                        <p class="form-control-plaintext">{{ $user->formatted_nip }}</p>
                    </div>
                    
                    <div class="d-flex justify-content-between mt-4">
                        <a href="{{ route('home') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left"></i> Kembali ke Home
                        </a>
                        <a href="{{ route('user.reservations') }}" class="btn btn-primary">
                            <i class="bi bi-calendar-check"></i> Lihat Reservasi Saya
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection