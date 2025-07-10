@extends('layouts.app')

@section('title', 'Akun Menunggu Persetujuan')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card text-center">
                <div class="card-header">Menunggu Persetujuan Admin</div>
                <div class="card-body">
                    <h5 class="card-title">Terima kasih telah mendaftar!</h5>
                    <p class="card-text">Akun Anda sedang ditinjau oleh admin. Anda akan dapat login setelah akun Anda disetujui.</p>
                    <p class="card-text">Silakan cek email Anda secara berkala untuk notifikasi persetujuan.</p>
                    <a href="{{ route('logout') }}" onclick="event.preventDefault(); document.getElementById('logout-form').submit();" class="btn btn-primary">Logout</a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">
                        @csrf
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection