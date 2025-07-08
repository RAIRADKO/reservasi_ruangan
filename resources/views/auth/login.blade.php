@extends('layouts.app')

@section('title', 'Login')

@section('content')
<div class="row justify-content-center py-3 py-md-4">
    <div class="col-11 col-md-6 col-lg-4">
        <div class="card shadow-sm">
            <div class="card-body p-3 p-md-4">
                <div class="text-center mb-3 mb-md-4">
                    <h4 class="card-title mb-1">Login</h4>
                    <p class="text-muted small mb-0">Masuk dengan NIP Anda</p>
                </div>
                
                <form action="{{ route('login') }}" method="POST">
                    @csrf

                    <div class="mb-3">
                        <label for="nip" class="form-label">NIP</label>
                        <input type="text" class="form-control form-control-lg @error('nip') is-invalid @enderror" name="nip" id="nip" required value="{{ old('nip') }}" autofocus>
                        @error('nip')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="password" class="form-label">Password</label>
                        <div class="input-group">
                            <input type="password" class="form-control form-control-lg @error('password') is-invalid @enderror" name="password" id="password" required>
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

                    <div class="d-grid mt-4">
                        <button type="submit" class="btn btn-primary btn-lg py-2">Login</button>
                    </div>
                     <div class="mt-3 text-center">
                        <small class="text-muted">
                            <a href="{{ route('password.request') }}">Lupa Password?</a>
                            <span class="mx-1"><br></span>
                            Belum punya akun? <a href="{{ route('register') }}">Daftar Akun Baru</a>.
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
    document.getElementById('togglePassword').addEventListener('click', function (e) {
        const password = document.getElementById('password');
        const icon = this.querySelector('i');
        
        // toggle the type attribute
        const type = password.getAttribute('type') === 'password' ? 'text' : 'password';
        password.setAttribute('type', type);
        
        // toggle the eye icon
        icon.classList.toggle('bi-eye');
        icon.classList.toggle('bi-eye-slash');
    });
</script>
@endsection