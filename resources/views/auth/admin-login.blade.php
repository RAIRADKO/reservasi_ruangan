@extends('layouts.app')

@section('title', 'Admin Login')

@section('content')
<div class="container">
    <div class="row justify-content-center align-items-center" style="min-height: 70vh;">
        <div class="col-11 col-md-6 col-lg-4"> {{-- Responsive columns --}}
            <div class="card shadow-sm">
                <div class="card-body p-3 p-md-4"> {{-- Adjusted padding --}}
                    <div class="text-center mb-3 mb-md-4"> {{-- Adjusted margin --}}
                        <h4 class="card-title mb-1">Admin Panel Login</h4> {{-- Better spacing --}}
                        <p class="text-muted small mb-0">Silakan masuk untuk melanjutkan</p> {{-- Smaller text on mobile --}}
                    </div>

                    {{-- Error section remains same --}}

                    <form action="{{ route('admin.login.submit') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="username" class="form-label">Username</label>
                            <input type="text" class="form-control form-control-lg" name="username" id="username" required value="{{ old('username') }}" autofocus> {{-- Larger input --}}
                        </div>
                        <div class="mb-3">
                            <label for="password" class="form-label">Password</label>
                            <input type="password" class="form-control form-control-lg" name="password" id="password" required> {{-- Larger input --}}
                        </div>
                        <div class="form-check mb-3">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label" for="remember">
                                Ingat Saya
                            </label>
                        </div>
                        <div class="d-grid">
                            <button type="submit" class="btn btn-primary btn-lg py-2">Login</button> {{-- Larger button --}}
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection