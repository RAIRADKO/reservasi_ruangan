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
                <input type="text" class="form-control" id="kontak" name="kontak" value="{{ old('kontak') }}" required>
                <div class="form-text">Contoh: 081234567890</div>
            </div>

            <div class="row">
                <div class="col-md-6">
                    <div class="mb-3">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}" required>
                        <div id="date-warning" class="invalid-feedback d-block" style="display: none;"></div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" min="{{ config('room.operating_hours.start') }}" max="{{ config('room.operating_hours.end') }}" value="{{ old('jam_mulai') }}" required>
                        <div class="form-text">Min: {{ config('room.operating_hours.start') }}</div>
                    </div>
                </div>
                <div class="col-md-3">
                    <div class="mb-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" min="{{ config('room.operating_hours.start') }}" max="{{ config('room.operating_hours.end') }}" value="{{ old('jam_selesai') }}" required>
                        <div class="form-text">Max: {{ config('room.operating_hours.end') }}</div>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="keperluan" class="form-label">Keperluan</label>
                <textarea class="form-control" id="keperluan" name="keperluan" rows="3" required>{{ old('keperluan') }}</textarea>
            </div>

            <button type="submit" id="submitButton" class="btn btn-primary">Ajukan Reservasi</button>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockedDates = @json($blockedDates ?? []);
    const tanggalInput = document.getElementById('tanggal');
    const submitButton = document.getElementById('submitButton');
    const dateWarning = document.getElementById('date-warning');

    function checkDate() {
        const selectedDate = tanggalInput.value;
        if (blockedDates.includes(selectedDate)) {
            tanggalInput.classList.add('is-invalid');
            dateWarning.textContent = 'Tanggal yang dipilih tidak tersedia untuk reservasi. Silakan pilih tanggal lain.';
            dateWarning.style.display = 'block';
            submitButton.disabled = true;
        } else {
            tanggalInput.classList.remove('is-invalid');
            dateWarning.style.display = 'none';
            submitButton.disabled = false;
        }
    }

    if (tanggalInput.value) {
        checkDate();
    }

    tanggalInput.addEventListener('change', checkDate);
});
</script>
@endsection