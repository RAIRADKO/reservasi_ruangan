@extends('layouts.admin')

@section('title', 'Edit Informasi Ruangan')

@section('content')
<div class="container">
    <h2>Edit Informasi Ruangan: {{ $room->nama_ruangan }}</h2>
    
    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.room.update', $room->id) }}" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
                    <input type="text" class="form-control @error('nama_ruangan') is-invalid @enderror" id="nama_ruangan" name="nama_ruangan" value="{{ old('nama_ruangan', $room->nama_ruangan) }}" required>
                    @error('nama_ruangan')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="deskripsi" class="form-label">Deskripsi</label>
                    <textarea class="form-control @error('deskripsi') is-invalid @enderror" id="deskripsi" name="deskripsi" rows="3" required>{{ old('deskripsi', $room->deskripsi) }}</textarea>
                    @error('deskripsi')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="kapasitas" class="form-label">Kapasitas</label>
                    <input type="number" class="form-control @error('kapasitas') is-invalid @enderror" id="kapasitas" name="kapasitas" min="1" value="{{ old('kapasitas', $room->kapasitas) }}" required>
                    @error('kapasitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="fasilitas" class="form-label">Fasilitas (pisahkan dengan koma)</label>
                    <textarea class="form-control @error('fasilitas') is-invalid @enderror" id="fasilitas" name="fasilitas" rows="3" required>{{ old('fasilitas', $room->fasilitas) }}</textarea>
                    @error('fasilitas')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="mb-3">
                    <label for="foto" class="form-label">Foto Ruangan</label>
                    <input class="form-control @error('foto') is-invalid @enderror" type="file" id="foto" name="foto">
                    @error('foto')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    @if($room->foto)
                        <div class="mt-2">
                            <img src="{{ $room->foto_url }}" alt="Foto Ruangan" class="img-thumbnail" style="max-height: 200px;">
                            <div class="form-text">Foto saat ini</div>
                        </div>
                    @endif
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.room.index') }}" class="btn btn-secondary">Kembali</a>
            </form>
        </div>
    </div>
</div>
@endsection