@extends('layouts.admin')

@section('title', 'Buat Reservasi Baru')

@section('styles')
<style>
    /* Styling untuk checklist fasilitas agar lebih interaktif */
    .fasilitas-checklist {
        display: flex;
        flex-wrap: wrap;
        gap: 0.75rem;
    }

    .fasilitas-item .btn {
        border-radius: 50px;
        font-weight: 500;
        transition: all 0.2s ease-in-out;
        display: flex;
        align-items: center;
        padding: 0.5rem 1rem;
        border: 1px solid #dee2e6;
    }
    
    /* Ikon untuk status checkbox (checked/unchecked) */
    .fasilitas-item .btn .icon-unchecked,
    .fasilitas-item .btn-check:checked ~ .btn .icon-checked {
        display: inline-block;
    }

    .fasilitas-item .btn .icon-checked,
    .fasilitas-item .btn-check:checked ~ .btn .icon-unchecked {
        display: none;
    }

    .fasilitas-item .btn-check:checked ~ .btn {
        background-color: #0d6efd;
        color: white;
        border-color: #0d6efd;
    }
</style>
@endsection

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2><i class="bi bi-calendar-plus me-2"></i>Buat Reservasi Baru</h2>
        <a href="{{ route('admin.reservations.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body p-4">
            {{-- Pesan jika admin tidak memiliki ruangan yang bisa diakses --}}
            @if($rooms->isEmpty())
                <div class="alert alert-warning text-center">
                    <h5 class="alert-heading">Tidak Ada Ruangan</h5>
                    <p class="mb-0">Tidak ada ruangan yang terhubung dengan instansi Anda. Silakan hubungi Super Admin untuk konfigurasi.</p>
                </div>
            @else
            {{-- Form utama --}}
            <form method="POST" action="{{ route('admin.reservations.store') }}">
                @csrf
                
                {{-- Informasi Pemohon --}}
                <h5 class="mb-3">Informasi Pemohon</h5>
                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nama" class="form-label">Nama Pemohon</label>
                        <input type="text" class="form-control @error('nama') is-invalid @enderror" id="nama" name="nama" value="{{ old('nama') }}" placeholder="Masukkan nama pemohon" required>
                        @error('nama') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kontak" class="form-label">Nomor Kontak</label>
                        <input type="text" class="form-control @error('kontak') is-invalid @enderror" id="kontak" name="kontak" value="{{ old('kontak') }}" placeholder="Contoh: 08123456789" required>
                        @error('kontak') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>
                 <div class="mb-4">
                    <label for="dinas_id" class="form-label">Asal Instansi/Dinas</label>
                    <select class="form-select @error('dinas_id') is-invalid @enderror" id="dinas_id" name="dinas_id" required>
                        <option value="">-- Pilih Instansi/Dinas --</option>
                        @foreach($dinas as $d)
                            <option value="{{ $d->id }}" {{ old('dinas_id') == $d->id ? 'selected' : '' }}>{{ $d->name }}</option>
                        @endforeach
                    </select>
                    @error('dinas_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <hr class="my-4">

                {{-- Detail Ruangan dan Fasilitas --}}
                <h5 class="mb-3">Detail Reservasi</h5>
                <div class="mb-3">
                    <label for="room_info_id" class="form-label">Pilih Ruangan</label>
                    <select class="form-select @error('room_info_id') is-invalid @enderror" id="room_info_id" name="room_info_id" required>
                        <option value="">-- Pilih Ruangan --</option>
                        @foreach($rooms as $room)
                            <option value="{{ $room->id }}" data-fasilitas="{{ $room->fasilitas }}" {{ old('room_info_id') == $room->id ? 'selected' : '' }}>
                                {{ $room->nama_ruangan }} (Kapasitas: {{ $room->kapasitas }} orang)
                            </option>
                        @endforeach
                    </select>
                    @error('room_info_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div id="fasilitas-wrapper" class="mb-3" style="display: none;">
                    <label class="form-label">Fasilitas Ruangan (pilih yang akan digunakan)</label>
                    <div class="p-3 bg-light border rounded">
                        <div id="fasilitas-checklist" class="fasilitas-checklist">
                            {{-- Fasilitas akan dimuat oleh JavaScript --}}
                        </div>
                    </div>
                    @error('fasilitas')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                </div>
                
                {{-- Waktu Reservasi --}}
                 <div class="row g-3 mb-3">
                    <div class="col-md-4">
                        <label for="tanggal" class="form-label">Tanggal</label>
                        <input type="date" class="form-control @error('tanggal') is-invalid @enderror" id="tanggal" name="tanggal" min="{{ date('Y-m-d') }}" value="{{ old('tanggal') }}" required>
                        @error('tanggal') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="jam_mulai" class="form-label">Jam Mulai</label>
                        <input type="time" class="form-control @error('jam_mulai') is-invalid @enderror" id="jam_mulai" name="jam_mulai" value="{{ old('jam_mulai') }}" required>
                        @error('jam_mulai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                    <div class="col-md-4">
                        <label for="jam_selesai" class="form-label">Jam Selesai</label>
                        <input type="time" class="form-control @error('jam_selesai') is-invalid @enderror" id="jam_selesai" name="jam_selesai" value="{{ old('jam_selesai') }}" required>
                        @error('jam_selesai') <div class="invalid-feedback">{{ $message }}</div> @enderror
                    </div>
                </div>

                {{-- Keperluan --}}
                <div class="mb-3">
                    <label for="keperluan" class="form-label">Keperluan</label>
                    <textarea class="form-control @error('keperluan') is-invalid @enderror" id="keperluan" name="keperluan" rows="3" placeholder="Jelaskan keperluan reservasi" required>{{ old('keperluan') }}</textarea>
                    @error('keperluan') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>

                <div class="alert alert-info small">
                    <i class="bi bi-info-circle-fill me-2"></i>
                    Reservasi yang dibuat oleh admin akan otomatis berstatus <strong>Disetujui</strong>.
                </div>

                <div class="d-grid gap-2 d-md-flex justify-content-md-end mt-4">
                    <button type="submit" class="btn btn-primary px-4 py-2"><i class="bi bi-check-circle me-2"></i>Buat dan Setujui Reservasi</button>
                </div>
            </form>
            @endif
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const roomSelect = document.getElementById('room_info_id');
    const fasilitasWrapper = document.getElementById('fasilitas-wrapper');
    const fasilitasChecklist = document.getElementById('fasilitas-checklist');

    function updateFasilitas() {
        // Kosongkan checklist
        fasilitasChecklist.innerHTML = ''; 
        const selectedOption = roomSelect.options[roomSelect.selectedIndex];
        
        // Sembunyikan jika tidak ada ruangan yang dipilih
        if (!selectedOption || !selectedOption.value) {
            fasilitasWrapper.style.display = 'none';
            return;
        }

        const fasilitasData = selectedOption.getAttribute('data-fasilitas');

        if (fasilitasData && fasilitasData.trim() !== '') {
            const fasilitasArray = fasilitasData.split(',').map(item => item.trim()).filter(item => item);
            
            if (fasilitasArray.length > 0) {
                // Buat checkbox untuk setiap fasilitas
                fasilitasArray.forEach(fasilitas => {
                    const uniqueId = 'fasilitas-' + fasilitas.replace(/[^a-zA-Z0-9]/g, '-').toLowerCase();
                    
                    const itemDiv = document.createElement('div');
                    itemDiv.className = 'fasilitas-item';
                    
                    const input = document.createElement('input');
                    input.type = 'checkbox';
                    input.className = 'btn-check';
                    input.name = 'fasilitas[]';
                    input.id = uniqueId;
                    input.value = fasilitas;
                    input.autocomplete = 'off';

                    const label = document.createElement('label');
                    label.className = 'btn btn-outline-primary';
                    label.htmlFor = uniqueId;
                    label.innerHTML = `
                        <i class="bi bi-square icon-unchecked me-2"></i>
                        <i class="bi bi-check-square-fill icon-checked me-2"></i>
                        <span>${fasilitas}</span>
                    `;
                    
                    itemDiv.appendChild(input);
                    itemDiv.appendChild(label);
                    fasilitasChecklist.appendChild(itemDiv);
                });
                fasilitasWrapper.style.display = 'block';
            } else {
                // Tampilkan pesan jika tidak ada fasilitas
                fasilitasChecklist.innerHTML = '<div class="text-muted small">Ruangan ini tidak memiliki fasilitas khusus yang dapat dipilih.</div>';
                fasilitasWrapper.style.display = 'block';
            }
        } else {
            fasilitasChecklist.innerHTML = '<div class="text-muted small">Ruangan ini tidak memiliki fasilitas khusus yang dapat dipilih.</div>';
            fasilitasWrapper.style.display = 'block';
        }
    }

    // Panggil fungsi saat halaman dimuat dan saat pilihan ruangan berubah
    roomSelect.addEventListener('change', updateFasilitas);
    updateFasilitas(); // Panggil saat inisialisasi
});
</script>
@endsection
