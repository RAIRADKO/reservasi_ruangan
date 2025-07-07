@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center py-3 py-md-4"> {{-- Added vertical padding --}}
    <div class="col-11 col-md-6 col-lg-4"> {{-- Responsive columns --}}
        <div class="card shadow-sm">
            <div class="card-body p-3 p-md-4"> {{-- Adjusted padding --}}
                <div class="text-center mb-3 mb-md-4"> {{-- Adjusted margin --}}
                    <h4 class="card-title mb-1">User Login</h4>
                    <p class="text-muted small mb-0">Masuk dengan NIP Anda</p> {{-- Smaller text on mobile --}}
                </div>
                
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control form-control-lg @error('nip') is-invalid @enderror" name="nip" id="nip" required value="{{ old('nip') }}" autofocus> {{-- Larger input --}}
                        @error('nip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" id="password" required> {{-- Larger input --}}
                         @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg py-2">Login</button> {{-- Larger button --}}
                    </div>
                     <div class="mt-3 text-center">
                        <small class="text-muted">
                            Belum punya akun? <a href="{{ route('register') }}">Daftar Akun Baru</a>.
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection