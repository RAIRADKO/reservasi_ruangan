@extends('layouts.admin')

@section('title', 'Edit Instansi/Dinas')

@section('content')
<div class="container">
    <h2>Edit Instansi/Dinas: {{ $dina->name }}</h2>

    <div class="card">
        <div class="card-body">
            <form method="POST" action="{{ route('admin.dinas.update', $dina->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Instansi/Dinas</label>
                    <input type="text" class="form-control @error('name') is-invalid @enderror" id="name" name="name" value="{{ old('name', $dina->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                <a href="{{ route('admin.dinas.index') }}" class="btn btn-secondary">Batal</a>
            </form>
        </div>
    </div>
</div>
@endsection