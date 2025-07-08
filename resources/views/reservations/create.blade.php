@extends('layouts.app')

@section('content')
<div class="card border-0 shadow">
    <div class="card-header bg-primary text-white">
        <h5 class="mb-0"><i class="bi bi-calendar-plus me-2"></i>Form Reservasi Ruangan</h5>
    </div>
    <div class="card-body p-4">
        <form method="POST" action="{{ route('reservations.store') }}" class="needs-validation" novalidate>
            @csrf

            <!-- Personal Information Section -->
            <div class="mb-4">
                <h5 class="text-primary mb-3"><i class="bi bi-person-badge me-2"></i>Informasi Pemohon</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama Lengkap</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-person"></i></span>
                            <input type="text" class="form-control" id="nama" name="nama" value="{{ Auth::user()->name }}" readonly>
                        </div>
                    </div>
                    
                    <div class="col-md-6 mb-3">
                        <label for="kontak" class="form-label">Nomor Kontak</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-phone"></i></span>
                            <input type="text" class="form-control" id="kontak" name="kontak" value="{{ old('kontak') }}" 
                                   placeholder="081234567890" required>
                        </div>
                        <div class="form-text">Format: 08xxxxxxxxxx</div>
                        @error('kontak')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
                
                <div class="mb-3">
                    <label for="dinas_id" class="form-label">Asal Instansi/Dinas</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-building"></i></span>
                        <select class="form-select @error('dinas_id') is-invalid @enderror" id="dinas_id" name="dinas_id" required>
                            <option value="" disabled {{ old('dinas_id') ? '' : 'selected' }}>-- Pilih Instansi/Dinas --</option>
                            @foreach($dinas as $d)
                                <option value="{{ $d->id }}" {{ old('dinas_id') == $d->id ? 'selected' : '' }}>
                                    {{ $d->name }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('dinas_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>
            
            <!-- Room Selection Section -->
            <div class="mb-4">
                <h5 class="text-primary mb-3"><i class="bi bi-door-closed me-2"></i>Detail Ruangan</h5>
                <div class="mb-3">
                    <label for="room_info_id" class="form-label">Pilih Ruangan</label>
                    <div class="input-group">
                        <span class="input-group-text"><i class="bi bi-door-open"></i></span>
                        <select class="form-select" id="room_info_id" name="room_info_id" required>
                            <option value="" disabled {{ old('room_info_id') ? '' : 'selected' }}>-- Pilih Ruangan --</option>
                            @foreach($rooms as $room)
                                <option value="{{ $room->id }}" data-fasilitas="{{ $room->fasilitas }}" {{ old('room_info_id') == $room->id ? 'selected' : '' }}>
                                    {{ $room->nama_ruangan }} (Kapasitas: {{ $room->kapasitas }} orang)
                                </option>
                            @endforeach
                        </select>
                    </div>
                    @error('room_info_id')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>

                <div id="fasilitas-wrapper" class="mb-3" style="display: none;">
                    <label class="form-label">Fasilitas Ruangan</label>
                    <div class="alert alert-light border">
                        <p class="mb-2 small text-muted">Centang fasilitas yang akan digunakan:</p>
                        <div id="fasilitas-checklist" class="d-flex flex-wrap gap-3"></div>
                    </div>
                    @error('fasilitas')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <!-- Time Selection Section -->
            <div class="mb-4">
                <h5 class="text-primary mb-3"><i class="bi bi-clock me-2"></i>Waktu Reservasi</h5>
                <div class="row g-3">
                    <div class="col-md-6">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-calendar"></i></span>
                            <input type="date" class="form-control" id="tanggal" name="tanggal" 
                                   min="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}" required>
                        </div>
                        <div id="date-warning" class="invalid-feedback d-block" style="display: none;"></div>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-play-circle"></i></span>
                            <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" 
                                   min="{{ config('room.operating_hours.start') }}" 
                                   max="{{ config('room.operating_hours.end') }}" 
                                   value="{{ old('jam_mulai') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-md-3">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <div class="input-group">
                            <span class="input-group-text"><i class="bi bi-stop-circle"></i></span>
                            <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" 
                                   min="{{ config('room.operating_hours.start') }}" 
                                   max="{{ config('room.operating_hours.end') }}" 
                                   value="{{ old('jam_selesai') }}" required>
                        </div>
                    </div>
                    
                    <div class="col-12">
                        <div class="d-flex justify-content-between align-items-center">
                            <div class="text-muted small">
                                <span>Jam Operasional: {{ config('room.operating_hours.start') }} - {{ config('room.operating_hours.end') }}</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Availability Check -->
            <div id="availability-result" class="mb-4" style="display: none;">
                <div class="alert p-3" role="alert">
                    <div class="d-flex align-items-center">
                        <span id="alert-icon" class="me-2" style="font-size: 1.5rem;"></span>
                        <div id="availability-message" class="fw-bold"></div>
                    </div>
                    <div id="existing-reservations" class="mt-3" style="display: none;">
                        <hr>
                        <h6 class="fw-bold">Jadwal Terpakai:</h6>
                        <ul id="reservation-list" class="mb-0 ps-3"></ul>
                    </div>
                </div>
            </div>

            <!-- Purpose Section -->
            <div class="mb-4">
                <h5 class="text-primary mb-3"><i class="bi bi-card-text me-2"></i>Detail Acara</h5>
                <div class="mb-3">
                    <label for="keperluan" class="form-label">Keperluan</label>
                    <textarea class="form-control" id="keperluan" name="keperluan" rows="4" 
                              placeholder="Deskripsikan tujuan penggunaan ruangan" required>{{ old('keperluan') }}</textarea>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="d-flex flex-column flex-md-row gap-3 pt-2">
                <button type="button" id="checkAvailabilityButton" class="btn btn-outline-primary flex-grow-1">
                    <i class="bi bi-search me-2"></i> Cek Ketersediaan
                </button>
                <button type="submit" id="submitButton" class="btn btn-primary flex-grow-1">
                    <i class="bi bi-send-check me-2"></i> Ajukan Reservasi
                </button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // ... [existing JS code remains the same] ...
    
    // Add time validation
    function validateTimes() {
        const startTime = jamMulai.value;
        const endTime = jamSelesai.value;
        
        if (startTime && endTime && startTime >= endTime) {
            jamSelesai.setCustomValidity('Jam selesai harus setelah jam mulai');
            jamSelesai.classList.add('is-invalid');
        } else {
            jamSelesai.setCustomValidity('');
            jamSelesai.classList.remove('is-invalid');
        }
    }

    jamMulai.addEventListener('change', validateTimes);
    jamSelesai.addEventListener('change', validateTimes);
    
    // Update availability result styling
    function showAvailabilityResult(available, message, existingReservationsData = null) {
        availabilityResult.style.display = 'block';
        const alertIcon = document.getElementById('alert-icon');
        
        if (available) {
            availabilityResult.firstElementChild.className = 'alert alert-success p-3';
            alertIcon.className = 'bi bi-check-circle-fill text-success';
            availabilityMessage.innerHTML = message;
            existingReservationsList.style.display = 'none';
        } else {
            availabilityResult.firstElementChild.className = 'alert alert-danger p-3';
            alertIcon.className = 'bi bi-exclamation-triangle-fill text-danger';
            availabilityMessage.innerHTML = message;
            
            if (existingReservationsData && existingReservationsData.length > 0) {
                reservationList.innerHTML = '';
                existingReservationsData.forEach(reservation => {
                    const li = document.createElement('li');
                    li.innerHTML = `<strong>${reservation.jam_mulai} - ${reservation.jam_selesai}</strong> (${reservation.nama_pemesan})`;
                    reservationList.appendChild(li);
                });
                existingReservationsList.style.display = 'block';
            } else {
                existingReservationsList.style.display = 'none';
            }
        }
    }
});
</script>
@endsection