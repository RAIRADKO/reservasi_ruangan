@extends('layouts.app')

@section('content')
<div class="card">
    <div class="card-header">
        Form Reservasi Ruangan
    </div>
    <div class="card-body">
        <form method="POST" action="{{ route('reservations.store') }}">
            @csrf

            <div class="mb-3">
                <label for="nama" class="form-label">Nama Lengkap</label>
                <input type="text" class="form-control" id="nama" name="nama" value="{{ Auth::user()->name }}" readonly>
            </div>

            <div class="mb-3">
                <label for="kontak" class="form-label">Nomor Kontak</label>
                <input type="text" class="form-control" id="kontak" name="kontak" required>
                <div class="form-text">Contoh: 081234567890</div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" required>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" min="08:00" max="17:00" required>
                        <div class="form-text">Min: 08:00</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" min="08:00" max="17:00" required>
                        <div class="form-text">Max: 17:00</div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="keperluan" class="form-label">Keperluan</label>
                <textarea class="form-control" id="keperluan" name="keperluan" rows="3" required></textarea>
            </div>

            <button type="submit" class="btn btn-primary">Ajukan Reservasi</button>
        </form>
    </div>
</div>
@endsection