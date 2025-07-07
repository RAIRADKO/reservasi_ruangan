@extends('layouts.app')

@section('content')
<div class="alert alert-success" role="alert">
    <h4 class="alert-heading">Reservasi Berhasil!</h4>
    <p>Reservasi ruangan Anda telah berhasil diajukan. Silakan menunggu persetujuan dari admin.</p>
    <hr>
    <p class="mb-0">Anda dapat melihat status reservasi pada halaman profil Anda.</p>
</div>
<div class="d-flex flex-column flex-sm-row justify-content-between gap-2">
    <a href="{{ route('home') }}" class="btn btn-outline-primary">Kembali ke Home</a>
    <a href="{{ route('user.profile') }}" class="btn btn-primary">Lihat Profil</a>
</div>
@endsection