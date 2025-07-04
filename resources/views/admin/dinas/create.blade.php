@extends('layouts.admin')

@section('title', 'Tambah Instansi/Dinas Baru')

@section('content')
<div class="container">
    <h2>Tambah Instansi/Dinas Baru</h2>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.dinas.store') }}">
                @csrf
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Instansi/Dinas</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name') }}" required autofocus>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan</button>
                <a href="{{ route('admin.dinas.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection