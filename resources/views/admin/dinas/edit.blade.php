@extends('layouts.admin')

@section('title', 'Edit Instansi/Dinas')

@section('content')
<div class="container py-3 py-md-4">
    <h2 class="mb-3">Edit Instansi/Dinas: {{ $dina->name }}</h2>

    <div class="card">
        <div class="card-body p-3 p-md-4">
            <form method="POST" action="{{ route('admin.dinas.update', $dina->id) }}">
                @csrf
                @method('PUT')
                <div class="mb-3">
                    <label for="name" class="form-label">Nama Instansi/Dinas</label>
                    <input type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" 
                           id="name" name="name" value="{{ old('name', $dina->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="d-grid gap-2 d-md-flex">
                    <button type="submit" class="btn btn-primary btn-lg flex-grow-1 py-2">
                        Simpan Perubahan
                    </button>
                    <a href="{{ route('admin.dinas.index') }}" class="btn btn-secondary btn-lg flex-grow-1 py-2">
                        Batal
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection