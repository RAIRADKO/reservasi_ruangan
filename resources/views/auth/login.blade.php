@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center py-3 py-md-4">
    <div class="col-11 col-md-6 col-lg-4">
        <div class="card shadow-lg border-0 rounded-lg">
            <div class="card-body p-4 p-md-5">
                <div class="text-center mb-4">
                    <img src="{{ asset('img/Lambang_Kabupaten_Purworejo.png') }}" alt="Logo" class="mb-3" style="width: 80px;">
                    <h4 class="card-title mb-1 fw-bold">Selamat Datang</h4>
                    <p class="text-muted small mb-0">Masuk dengan NIP untuk melanjutkan</p>
                </div>
                
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="form-floating mb-3">
                        <input type="text" class="form-control @error('nip') is-invalid @enderror" name="nip" id="nip" placeholder="NIP" required value="{{ old('nip') }}" autofocus>
                        <label for="nip"><i class="bi bi-person me-2"></i>NIP</label>
                        @error('nip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="form-floating mb-3 position-relative">
                        <input type="password" class="form-control @error('password') is-invalid @enderror" name="password" id="password" placeholder="Password" required>
                        <label for="password"><i class="bi bi-lock me-2"></i>Password</label>
                        <button type="button" id="togglePassword" tabindex="-1" class="btn btn-sm btn-outline-secondary position-absolute top-50 end-0 translate-middle-y me-2" style="z-index: 2;">
                            <i class="bi bi-eye" id="togglePasswordIcon"></i>
                        </button>
                        @error('password')
                            <span class="invalid-feedback d-block" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label small" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                        <a href="{{ route('password.request') }}" class="small text-decoration-none">Lupa Password?</a>
                    </div>


                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg py-2 fw-bold">Login</button>
                    </div>
                     <div class="mt-4 text-center">
                        <small class="text-muted">
                            Belum punya akun? <a href="{{ route('register') }}" class="text-decoration-none">Daftar di sini</a>.
                        </small>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const togglePasswordIcon = document.getElementById('togglePasswordIcon');
        if (togglePassword && passwordInput) {
            togglePassword.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                togglePasswordIcon.classList.toggle('bi-eye');
                togglePasswordIcon.classList.toggle('bi-eye-slash');
            });
        }
    });
</script>
@endsection