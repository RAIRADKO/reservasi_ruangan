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

            <!-- Asal Instansi/Dinas Field -->
            <div class="mb-3">
                <label for="dinas_id" class="form-label">Asal Instansi/Dinas</label>
                <select class="form-select @error('dinas_id') is-invalid @enderror" id="dinas_id" name="dinas_id" required>
                    <option value="" disabled {{ old('dinas_id') ? '' : 'selected' }}>-- Pilih Instansi/Dinas --</option>
                    @foreach($dinas as $d)
                        <option value="{{ $d->id }}" {{ old('dinas_id') == $d->id ? 'selected' : '' }}>
                            {{ $d->name }}
                        </option>
                    @endforeach
                </select>
                @error('dinas_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Pilihan Ruangan -->
            <div class="mb-3">
                <label for="room_info_id" class="form-label">Pilih Ruangan</label>
                <select class="form-select" id="room_info_id" name="room_info_id" required>
                    <option value="" disabled {{ old('room_info_id') ? '' : 'selected' }}>-- Pilih Ruangan --</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ old('room_info_id') == $room->id ? 'selected' : '' }}>
                            {{ $room->nama_ruangan }} (Kapasitas: {{ $room->kapasitas }} orang)
                        </option>
                    @endforeach
                </select>
                @error('room_info_id')
                    <div class="invalid-feedback d-block">{{ $message }}</div>
                @enderror
            </div>

            <!-- Room Details Card (initially hidden) -->
            <div id="room-details" class="mb-3" style="display: none;">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="card-title">Detail Ruangan</h6>
                        <div id="room-info-content">
                            <!-- Room details will be populated here -->
                        </div>
                    </div>
                </div>
            </div>

            <div class="row">
                <!-- Tanggal -->
                <div class="col-12 col-md-6 mb-3">
                    <label for="tanggal" class="form-label">Tanggal</label>
                    <input type="date" class="form-control" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}" required>
                    <div id="date-warning" class="invalid-feedback d-block" style="display: none;"></div>
                </div>
                
                <!-- Jam Mulai -->
                <div class="col-6 col-md-3 mb-3">
                    <label for="jam_mulai" class="form-label">Jam Mulai</label>
                    <input type="time" class="form-control" id="jam_mulai" name="jam_mulai" min="{{ config('room.operating_hours.start') }}" max="{{ config('room.operating_hours.end') }}" value="{{ old('jam_mulai') }}" required>
                    <div class="form-text d-none d-md-block">Min: {{ config('room.operating_hours.start') }}</div>
                </div>
                
                <!-- Jam Selesai -->
                <div class="col-6 col-md-3 mb-3">
                    <label for="jam_selesai" class="form-label">Jam Selesai</label>
                    <input type="time" class="form-control" id="jam_selesai" name="jam_selesai" min="{{ config('room.operating_hours.start') }}" max="{{ config('room.operating_hours.end') }}" value="{{ old('jam_selesai') }}" required>
                    <div class="form-text d-none d-md-block">Max: {{ config('room.operating_hours.end') }}</div>
                </div>
            </div>

            <div class="d-flex justify-content-between">
                <div>
                    <small class="d-block d-md-none text-muted">Min: {{ config('room.operating_hours.start') }}</small>
                    <small class="d-block d-md-none text-muted">Max: {{ config('room.operating_hours.end') }}</small>
                </div>
            </div>

            <!-- Availability Check Results -->
            <div id="availability-result" class="mb-3" style="display: none;">
                <div class="alert" role="alert">
                    <div id="availability-message"></div>
                    <div id="existing-reservations" style="display: none;">
                        <hr>
                        <h6>Jadwal yang sudah terisi:</h6>
                        <ul id="reservation-list"></ul>
                    </div>
                </div>
            </div>

            <div class="mb-3">
                <label for="keperluan" class="form-label">Keperluan</label>
                <textarea class="form-control" id="keperluan" name="keperluan" rows="3" required>{{ old('keperluan') }}</textarea>
            </div>

            <div class="d-flex flex-column flex-md-row gap-2">
                <button type="button" id="checkAvailabilityButton" class="btn btn-outline-info mb-2 mb-md-0">
                    <i class="bi bi-search"></i> Cek Ketersediaan
                </button>
                <button type="submit" id="submitButton" class="btn btn-primary">Ajukan Reservasi</button>
            </div>
        </form>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const blockedDates = @json($blockedDates ?? []);
    const rooms = @json($rooms ?? []);
    const tanggalInput = document.getElementById('tanggal');
    const roomSelect = document.getElementById('room_info_id');
    const dinasSelect = document.getElementById('dinas_id');
    const submitButton = document.getElementById('submitButton');
    const dateWarning = document.getElementById('date-warning');
    const roomDetails = document.getElementById('room-details');
    const roomInfoContent = document.getElementById('room-info-content');
    const checkAvailabilityButton = document.getElementById('checkAvailabilityButton');
    const availabilityResult = document.getElementById('availability-result');
    const availabilityMessage = document.getElementById('availability-message');
    const existingReservations = document.getElementById('existing-reservations');
    const reservationList = document.getElementById('reservation-list');
    const jamMulai = document.getElementById('jam_mulai');
    const jamSelesai = document.getElementById('jam_selesai');

    // Room selection handler
    roomSelect.addEventListener('change', function() {
        const selectedRoomId = this.value;
        if (selectedRoomId) {
            const selectedRoom = rooms.find(room => room.id == selectedRoomId);
            if (selectedRoom) {
                showRoomDetails(selectedRoom);
            }
        } else {
            roomDetails.style.display = 'none';
        }
    });

    function showRoomDetails(room) {
        const facilities = room.fasilitas ? room.fasilitas.split(',').map(f => f.trim()) : [];
        
        roomInfoContent.innerHTML = `
            <div class="row">
                <div class="col-md-6">
                    <p><strong>Nama Ruangan:</strong> ${room.nama_ruangan}</p>
                    <p><strong>Kapasitas:</strong> ${room.kapasitas} orang</p>
                    <p><strong>Deskripsi:</strong> ${room.deskripsi || 'Tidak ada deskripsi'}</p>
                </div>
                <div class="col-md-6">
                    <p><strong>Fasilitas:</strong></p>
                    <ul class="list-unstyled">
                        ${facilities.map(facility => `<li><i class="bi bi-check-circle-fill text-success me-2"></i>${facility}</li>`).join('')}
                    </ul>
                </div>
            </div>
        `;
        
        roomDetails.style.display = 'block';
    }

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

    // Availability check function
    checkAvailabilityButton.addEventListener('click', function() {
        const roomId = roomSelect.value;
        const tanggal = tanggalInput.value;
        const jam_mulai = jamMulai.value;
        const jam_selesai = jamSelesai.value;

        if (!roomId || !tanggal || !jam_mulai || !jam_selesai) {
            showAvailabilityResult(false, 'Mohon lengkapi semua field terlebih dahulu.');
            return;
        }

        // Show loading state
        checkAvailabilityButton.disabled = true;
        checkAvailabilityButton.innerHTML = '<i class="bi bi-hourglass-split"></i> Mengecek...';

        // Make AJAX request
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
            // Reset button state
            checkAvailabilityButton.disabled = false;
            checkAvailabilityButton.innerHTML = '<i class="bi bi-search"></i> Cek Ketersediaan';
        });
    });

    function showAvailabilityResult(available, message, existingReservations = null) {
        availabilityResult.style.display = 'block';
        
        if (available) {
            availabilityResult.firstElementChild.className = 'alert alert-success';
            availabilityMessage.innerHTML = '<i class="bi bi-check-circle-fill me-2"></i>' + message;
            existingReservations.style.display = 'none';
        } else {
            availabilityResult.firstElementChild.className = 'alert alert-danger';
            availabilityMessage.innerHTML = '<i class="bi bi-exclamation-triangle-fill me-2"></i>' + message;
            
            if (existingReservations && existingReservations.length > 0) {
                reservationList.innerHTML = '';
                existingReservations.forEach(reservation => {
                    const li = document.createElement('li');
                    li.innerHTML = `${reservation.jam_mulai} - ${reservation.jam_selesai}`;
                    reservationList.appendChild(li);
                });
                existingReservations.style.display = 'block';
            } else {
                existingReservations.style.display = 'none';
            }
        }
    }

    // Hide availability result when form inputs change
    [roomSelect, tanggalInput, jamMulai, jamSelesai, dinasSelect].forEach(element => {
        element.addEventListener('change', function() {
            availabilityResult.style.display = 'none';
        });
    });

    // Check if there's a pre-selected room (for old input)
    if (roomSelect.value) {
        const selectedRoom = rooms.find(room => room.id == roomSelect.value);
        if (selectedRoom) {
            showRoomDetails(selectedRoom);
        }
    }

    if (tanggalInput.value) {
        checkDate();
    }

    tanggalInput.addEventListener('change', checkDate);
});
</script>
@endsection