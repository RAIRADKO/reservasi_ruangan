@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Edit Informasi Ruangan</h2>
    
    <form method="POST" action="{{ route('admin.room.update') }}" enctype="multipart/form-data">
        @csrf
        <div class="mb-3">
            <label for="nama_ruangan" class="form-label">Nama Ruangan</label>
            <input type="text" class="form-control" id="nama_ruangan" name="nama_ruangan" value="{{ $room->nama_ruangan }}" required>
        </div>
        
        <div class="mb-3">
            <label for="deskripsi" class="form-label">Deskripsi</label>
            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $room->deskripsi }}</textarea>
        </div>
        
        <div class="mb-3">
            <label for="kapasitas" class="form-label">Kapasitas</label>
            <input type="number" class="form-control" id="kapasitas" name="kapasitas" min="1" value="{{ $room->kapasitas }}" required>
        </div>
        
        <div class="mb-3">
            <label for="fasilitas" class="form-label">Fasilitas (pisahkan dengan koma)</label>
            <textarea class="form-control" id="fasilitas" name="fasilitas" rows="3" required>{{ $room->fasilitas }}</textarea>
        </div>
        
        <div class="mb-3">
            <label for="foto" class="form-label">Foto Ruangan</label>
            <input class="form-control" type="file" id="foto" name="foto">
            @if($room->foto)
                <div class="mt-2">
                    <img src="{{ $room->foto_url }}" alt="Foto Ruangan" class="img-thumbnail" style="max-height: 200px;">
                    <div class="form-text">Foto saat ini</div>
                </div>
            @endif
        </div>
        
        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-secondary">Kembali</a>
    </form>
</div>
@endsection