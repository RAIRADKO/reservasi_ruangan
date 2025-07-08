@extends('layouts.app')

@section('content')
<div class="row justify-content-center py-3 py-md-4">
    <div class="col-11 col-md-8 col-lg-6">
        <div class="card">
            <div class="card-body p-3 p-md-4">
                 <div class="text-center mb-3 mb-md-4">
                    <h4 class="card-title mb-1">{{ __('Register') }}</h4>
                    <p class="text-muted small mb-0">Buat akun baru untuk memulai</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="mb-3">
                        <label for="name" class="form-label">{{ __('Nama Lengkap') }}</label>
                        <input id="name" type="text" class="form-control form-control-lg @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="nip" class="form-label">{{ __('NIP') }}</label>
                        <input id="nip" type="text" class="form-control form-control-lg @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip') }}" required autocomplete="nip">
                        <div class="form-text small">Masukkan 18 digit Nomor Induk Pegawai Anda.</div>
                        @error('nip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="email" class="form-label">{{ __('Alamat Email') }}</label>
                        <input id="email" type="email" class="form-control form-control-lg @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="mb-3">
                        <label for="password" class="form-label">{{ __('Password') }}</label>
                        <div class="input-group">
                            <input id="password" type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" required autocomplete="new-password">
                            <button class="btn btn-outline-secondary" type="button" id="togglePassword">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    {{-- Password Criteria Checklist --}}
                    <div id="password-criteria" class="p-3 bg-light rounded small mb-3">
                        <div id="length" class="criteria-item invalid"><i class="bi bi-x-circle me-2"></i>Minimal 8 karakter</div>
                        <div id="uppercase" class="criteria-item invalid"><i class="bi bi-x-circle me-2"></i>Satu huruf besar</div>
                        <div id="number" class="criteria-item invalid"><i class="bi bi-x-circle me-2"></i>Satu angka</div>
                        <div id="symbol" class="criteria-item invalid"><i class="bi bi-x-circle me-2"></i>Satu karakter unik (simbol)</div>
                    </div>


                    <div class="mb-3">
                        <label for="password-confirm" class="form-label">{{ __('Konfirmasi Password') }}</label>
                        <div class="input-group">
                            <input id="password-confirm" type="password" class="form-control form-control-lg" name="password_confirmation" required autocomplete="new-password">
                             <button class="btn btn-outline-secondary" type="button" id="togglePasswordConfirm">
                                <i class="bi bi-eye"></i>
                            </button>
                        </div>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg py-2">
                            {{ __('Register') }}
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                       <p class="text-muted small mb-0">Sudah punya akun? <a href="{{ route('login') }}">Login di sini</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .criteria-item.invalid {
        color: #dc3545;
    }
    .criteria-item.valid {
        color: #198754;
        text-decoration: line-through;
    }
    .criteria-item .bi-x-circle {
        color: #dc3545;
    }
    .criteria-item .bi-check-circle {
        color: #198754;
    }
</style>
@endsection

@section('scripts')
<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const togglePasswordBtn = document.getElementById('togglePassword');
    const togglePasswordConfirmBtn = document.getElementById('togglePasswordConfirm');
    const passwordConfirmInput = document.getElementById('password-confirm');
    
    const lengthCheck = document.getElementById('length');
    const uppercaseCheck = document.getElementById('uppercase');
    const numberCheck = document.getElementById('number');
    const symbolCheck = document.getElementById('symbol');

    // Toggle Password Visibility
    function toggleVisibility(input, button) {
        const icon = button.querySelector('i');
        const type = input.getAttribute('type') === 'password' ? 'text' : 'password';
        input.setAttribute('type', type);
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    }

    togglePasswordBtn.addEventListener('click', function () {
        toggleVisibility(passwordInput, this);
    });

    togglePasswordConfirmBtn.addEventListener('click', function () {
        toggleVisibility(passwordConfirmInput, this);
    });

    // Password Criteria Validation
    passwordInput.addEventListener('input', function() {
        const value = this.value;

        // Length
        updateCriteria(lengthCheck, value.length >= 8);
        
        // Uppercase
        updateCriteria(uppercaseCheck, /[A-Z]/.test(value));

        // Number
        updateCriteria(numberCheck, /[0-9]/.test(value));
        
        // Symbol
        updateCriteria(symbolCheck, /[^A-Za-z0-9]/.test(value));
    });

    function updateCriteria(element, isValid) {
        const icon = element.querySelector('i');
        if (isValid) {
            element.classList.remove('invalid');
            element.classList.add('valid');
            icon.classList.remove('bi-x-circle');
            icon.classList.add('bi-check-circle');
        } else {
            element.classList.remove('valid');
            element.classList.add('invalid');
            icon.classList.remove('bi-check-circle');
            icon.classList.add('bi-x-circle');
        }
    }
});
</script>
@endsection