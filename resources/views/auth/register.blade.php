@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-3 py-md-4"> {{-- Added vertical padding --}}
    <div class="col-11 col-md-8 col-lg-6"> {{-- Responsive columns --}}
        <div class="card">
            <div class="card-body p-3 p-md-4"> {{-- Adjusted padding --}}
                 <div class="text-center mb-3 mb-md-4"> {{-- Adjusted margin --}}
                    <h4 class="card-title mb-1">{{ __('Register') }}</h4>
                    <p class="text-muted small mb-0">Buat akun baru untuk memulai</p> {{-- Smaller text on mobile --}}
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Nama Lengkap') }}</label>
                        <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus> {{-- Larger input --}}
                        
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nip" class="form-label">{{ __('NIP') }}</label>
                        <input id="nip" type="text" class="form-control form-control-lg @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip') }}" required autocomplete="nip"> {{-- Larger input --}}
                        <div class="form-text small">Masukkan 18 digit Nomor Induk Pegawai Anda.</div> {{-- Smaller text --}}
                        @error('nip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Alamat Email') }}</label>
                        <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email"> {{-- Larger input --}}

                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password"> {{-- Larger input --}}
                        <div class="form-text small">Minimal 8 karakter, dengan kombinasi huruf besar, angka, dan karakter unik.</div> {{-- Smaller text --}}
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">{{ __('Konfirmasi Password') }}</label>
                        <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password"> {{-- Larger input --}}
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg py-2"> {{-- Larger button --}}
                            {{ __('Register') }}
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                       <p class="text-muted small mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p> {{-- Smaller text --}}
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection