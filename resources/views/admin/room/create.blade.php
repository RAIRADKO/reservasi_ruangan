@extends('layouts.admin')

@section('title', 'Tambah Ruangan Baru')

@section('content')
<div class="container">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2>Tambah Ruangan Baru</h2>
        <a href="{{ route('admin.room.index') }}" class="btn btn-sm btn-outline-secondary">
            <i class="bi bi-arrow-left me-1"></i> Kembali
        </a>
    </div>

    <div class="card shadow-sm border-0">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.room.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="mb-3">
                    <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
                    <input type="text" class="form-control @error('nama_ruangan') is-invalid @enderror" 
                           id="nama_ruangan" name="nama_ruangan" value="{{ old('nama_ruangan') }}" required>
                    @error('nama_ruangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" 
                              id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi') }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="kapasitas" class="form-label">Kapasitas</label>
                    <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" 
                           id="kapasitas" name="kapasitas" min="1" value="{{ old('kapasitas') }}" required>
                    @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="fasilitas" class="form-label">Fasilitas (pisahkan dengan koma)</label>
                    <textarea class="form-control @error('fasilitas') is-invalid @enderror" 
                              id="fasilitas" name="fasilitas" rows="3" required>{{ old('fasilitas') }}</textarea>
                    @error('fasilitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @if(auth()->guard('admin')->user()->role === 'superadmin')
                <div class="mb-3">
                    <label for="instansi_id" class="form-label">Instansi</label>
                    <select class="form-select @error('instansi_id') is-invalid @enderror" id="instansi_id" name="instansi_id" required>
                        <option value="">Pilih Instansi</option>
                        @foreach($dinas as $d)
                            <option value="{{ $d->id }}" {{ old('instansi_id') == $d->id ? 'selected' : '' }}>
                                {{ $d->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('instansi_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                @endif
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Ruangan</label>
                    <input class="form-control @error('foto') is-invalid @enderror" 
                           type="file" id="foto" name="foto">
                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                    <button type="submit" class="btn btn-primary px-4 py-2">
                        <i class="bi bi-save-fill me-1"></i> Simpan Ruangan
                    </button>
                    <a href="{{ route('admin.room.index') }}" class="btn btn-outline-secondary px-4 py-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection