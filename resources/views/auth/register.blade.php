@extends('layouts.app')

@section('title', 'Register')

@section('content')
<div class="row justify-content-center py-3 py-md-4">
    <div class="col-11 col-md-8 col-lg-6">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-body p-4 p-md-5">
                 <div class="text-center mb-4">
                    <img src="{{ asset('img/Lambang_Kabupaten_Purworejo.png') }}" alt="Logo" class="mb-3" style="width: 80px;">
                    <h4 class="card-title mb-1 fw-bold">Buat Akun Baru</h4>
                    <p class="text-muted small mb-0">Isi data berikut untuk mendaftar</p>
                </div>

                <form method="POST" action="{{ route('register') }}">
                    @csrf

                    <div class="form-floating mb-3">
                        <input id="name" type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name') }}" required autocomplete="name" autofocus placeholder="Nama Lengkap">
                        <label for="name"><i class="bi bi-person me-2"></i>Nama Lengkap</label>
                        @error('name')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input id="nip" type="text" class="form-control @error('nip') is-invalid @enderror" name="nip" value="{{ old('nip') }}" required autocomplete="nip" placeholder="NIP (18 digit)">
                        <label for="nip"><i class="bi bi-person-badge me-2"></i>NIP (18 digit)</label>
                        @error('nip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                         <input id="email" type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email') }}" required autocomplete="email" placeholder="Alamat Email">
                        <label for="email"><i class="bi bi-envelope me-2"></i>Alamat Email</label>
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-floating mb-3">
                        <input id="kontak" type="text" class="form-control @error('kontak') is-invalid @enderror" name="kontak" value="{{ old('kontak') }}" required placeholder="Nomor Kontak">
                        <label for="kontak"><i class="bi bi-phone me-2"></i>Nomor Kontak</label>
                        @error('kontak')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>
                    
                    <div class="form-floating mb-3 position-relative">
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror" name="password" required autocomplete="new-password" placeholder="Password">
                        <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                        <button type="button" id="togglePassword" tabindex="-1" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" style="z-index: 2;">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                    </div>
                    
                    {{-- Password Criteria Checklist --}}
                    <div id="password-criteria" class="p-3 bg-light rounded small mb-3 border">
                        <div id="length" class="criteria-item invalid"><i class="bi bi-x-circle me-2"></i>Minimal 8 karakter</div>
                        <div id="uppercase" class="criteria-item invalid"><i class="bi bi-x-circle me-2"></i>Satu huruf besar</div>
                        <div id="number" class="criteria-item invalid"><i class="bi bi-x-circle me-2"></i>Satu angka</div>
                        <div id="symbol" class="criteria-item invalid"><i class="bi bi-x-circle me-2"></i>Satu simbol unik</div>
                    </div>
                    
                    <div class="form-floating mb-3 position-relative">
                        <input id="password-confirm" type="password" class="form-control" name="password_confirmation" required autocomplete="new-password" placeholder="Konfirmasi Password">
                        <label for="password-confirm"><i class="bi bi-lock-fill me-2"></i>Konfirmasi Password</label>
                        <button type="button" id="togglePasswordConfirm" tabindex="-1" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" style="z-index: 2;">
                            <i class="bi bi-eye" id="togglePasswordConfirmIcon"></i>
                        </button>
                    </div>

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg py-2 fw-bold">
                            Register
                        </button>
                    </div>

                    <div class="mt-4 text-center">
                       <p class="text-muted small mb-0">Sudah punya akun? <a href="{{ route('login') }}" class="text-decoration-none">Login di sini</a></p>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('styles')
<style>
    .criteria-item {
        transition: all 0.3s ease;
    }
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
    .criteria-item .bi-check-circle-fill {
        color: #198754;
    }
</style>
@endsection

@section('scripts')
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    
    const lengthCheck = document.getElementById('length');
    const uppercaseCheck = document.getElementById('uppercase');
    const numberCheck = document.getElementById('number');
    const symbolCheck = document.getElementById('symbol');

    passwordInput.addEventListener('input', function() {
        const value = this.value;
        updateCriteria(lengthCheck, value.length >= 8);
        updateCriteria(uppercaseCheck, /[A-Z]/.test(value));
        updateCriteria(numberCheck, /[0-9]/.test(value));
        updateCriteria(symbolCheck, /[^A-Za-z0-9]/.test(value));
    });

    function updateCriteria(element, isValid) {
        const icon = element.querySelector('i');
        if (isValid) {
            element.classList.remove('invalid');
            element.classList.add('valid');
            icon.classList.remove('bi-x-circle');
            icon.classList.add('bi-check-circle-fill');
        } else {
            element.classList.remove('valid');
            element.classList.add('invalid');
            icon.classList.remove('bi-check-circle-fill');
            icon.classList.add('bi-x-circle');
        }
    }
});
</script>
<script>
document.addEventListener('DOMContentLoaded', function () {
    const passwordInput = document.getElementById('password');
    const passwordConfirmInput = document.getElementById('password-confirm');
    const togglePassword = document.getElementById('togglePassword');
    const togglePasswordIcon = document.getElementById('togglePasswordIcon');
    const togglePasswordConfirm = document.getElementById('togglePasswordConfirm');
    const togglePasswordConfirmIcon = document.getElementById('togglePasswordConfirmIcon');

    if (togglePassword && passwordInput) {
        togglePassword.addEventListener('click', function() {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            togglePasswordIcon.classList.toggle('bi-eye');
            togglePasswordIcon.classList.toggle('bi-eye-slash');
        });
    }
    if (togglePasswordConfirm && passwordConfirmInput) {
        togglePasswordConfirm.addEventListener('click', function() {
            const type = passwordConfirmInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordConfirmInput.setAttribute('type', type);
            togglePasswordConfirmIcon.classList.toggle('bi-eye');
            togglePasswordConfirmIcon.classList.toggle('bi-eye-slash');
        });
    }

    const lengthCheck = document.getElementById('length');
    const uppercaseCheck = document.getElementById('uppercase');
    const numberCheck = document.getElementById('number');
    const symbolCheck = document.getElementById('symbol');

    passwordInput.addEventListener('input', function() {
        const value = this.value;
        updateCriteria(lengthCheck, value.length >= 8);
        updateCriteria(uppercaseCheck, /[A-Z]/.test(value));
        updateCriteria(numberCheck, /[0-9]/.test(value));
        updateCriteria(symbolCheck, /[^A-Za-z0-9]/.test(value));
    });

    function updateCriteria(element, isValid) {
        const icon = element.querySelector('i');
        if (isValid) {
            element.classList.remove('invalid');
            element.classList.add('valid');
            icon.classList.remove('bi-x-circle');
            icon.classList.add('bi-check-circle-fill');
        } else {
            element.classList.remove('valid');
            element.classList.add('invalid');
            icon.classList.remove('bi-check-circle-fill');
            icon.classList.add('bi-x-circle');
        }
    }
});
</script>
@endsection