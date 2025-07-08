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

                <!-- FACILITIES CHECKLIST RESTORED -->
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
    const roomSelect = document.getElementById('room_info_id');
    const fasilitasWrapper = document.getElementById('fasilitas-wrapper');
    const fasilitasChecklist = document.getElementById('fasilitas-checklist');

    // Function to handle room selection change
    roomSelect.addEventListener('change', function() {
        // Clear previous checklist
        fasilitasChecklist.innerHTML = '';
        
        // Get selected option
        const selectedOption = this.options[this.selectedIndex];
        const fasilitasData = selectedOption.getAttribute('data-fasilitas');

        if (fasilitasData && fasilitasData.trim() !== '') {
            const fasilitasArray = fasilitasData.split(',').map(item => item.trim());
            
            if (fasilitasArray.length > 0 && fasilitasArray[0] !== '') {
                fasilitasArray.forEach(fasilitas => {
                    const checkboxDiv = document.createElement('div');
                    checkboxDiv.classList.add('form-check');
                    
                    const checkboxInput = document.createElement('input');
                    checkboxInput.classList.add('form-check-input');
                    checkboxInput.type = 'checkbox';
                    checkboxInput.name = 'fasilitas[]';
                    checkboxInput.value = fasilitas;
                    checkboxInput.id = 'fasilitas-' + fasilitas.replace(/\s+/g, '-').toLowerCase();
                    
                    const checkboxLabel = document.createElement('label');
                    checkboxLabel.classList.add('form-check-label');
                    checkboxLabel.htmlFor = checkboxInput.id;
                    checkboxLabel.textContent = fasilitas;
                    
                    checkboxDiv.appendChild(checkboxInput);
                    checkboxDiv.appendChild(checkboxLabel);
                    fasilitasChecklist.appendChild(checkboxDiv);
                });

                fasilitasWrapper.style.display = 'block';
            } else {
                // Show message when room has no facilities
                fasilitasChecklist.innerHTML = '<div class="text-muted small">Ruangan ini tidak memiliki fasilitas khusus</div>';
                fasilitasWrapper.style.display = 'block';
            }
        } else {
            // Hide section when room has no facilities data
            fasilitasWrapper.style.display = 'none';
        }
    });
    
    // Trigger change event on page load if a room is already selected
    if (roomSelect.value) {
        roomSelect.dispatchEvent(new Event('change'));
    }

    // Keep the rest of the existing script
    const blockedDates = @json($blockedDates ?? []);
    const tanggalInput = document.getElementById('tanggal');
    const submitButton = document.getElementById('submitButton');
    const dateWarning = document.getElementById('date-warning');
    const checkAvailabilityButton = document.getElementById('checkAvailabilityButton');
    const availabilityResult = document.getElementById('availability-result');
    const availabilityMessage = document.getElementById('availability-message');
    const existingReservationsList = document.getElementById('existing-reservations');
    const reservationList = document.getElementById('reservation-list');
    const jamMulai = document.getElementById('jam_mulai');
    const jamSelesai = document.getElementById('jam_selesai');

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
    
    tanggalInput.addEventListener('change', checkDate);
    if (tanggalInput.value) {
        checkDate();
    }

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
    
    checkAvailabilityButton.addEventListener('click', function() {
        const roomId = roomSelect.value;
        const tanggal = tanggalInput.value;
        const jam_mulai = jamMulai.value;
        const jam_selesai = jamSelesai.value;

        if (!roomId || !tanggal || !jam_mulai || !jam_selesai) {
            showAvailabilityResult(false, 'Mohon lengkapi semua field terlebih dahulu.');
            return;
        }

        checkAvailabilityButton.disabled = true;
        checkAvailabilityButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengecek...';

        fetch('{{ route("reservations.check-availability") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                room_info_id: roomId,
                tanggal: tanggal,
                jam_mulai: jam_mulai,
                jam_selesai: jam_selesai
            })
        })
        .then(response => response.json())
        .then(data => {
            showAvailabilityResult(data.available, data.message, data.existing_reservations);
        })
        .catch(error => {
            console.error('Error:', error);
            showAvailabilityResult(false, 'Terjadi kesalahan saat mengecek ketersediaan.');
        })
        .finally(() => {
            checkAvailabilityButton.disabled = false;
            checkAvailabilityButton.innerHTML = '<i class="bi bi-search"></i> Cek Ketersediaan';
        });
    });

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

    [roomSelect, tanggalInput, jamMulai, jamSelesai].forEach(element => {
        element.addEventListener('change', function() {
            availabilityResult.style.display = 'none';
        });
    });
});
</script>
@endsection