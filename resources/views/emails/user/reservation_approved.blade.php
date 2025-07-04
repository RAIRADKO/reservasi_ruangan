@extends('emails.layouts.custom')

@section('title', 'Reservasi Anda Telah Disetujui')

@section('header', 'Reservasi Disetujui')

@section('content')
    <h2 style="color: #28a745;">✅ Reservasi Ruangan Anda Telah Disetujui</h2>

    <p>Halo, <strong>{{ $reservation->nama }}</strong>.</p>

    <p>Kabar baik! Reservasi ruangan yang Anda ajukan telah disetujui oleh admin. Berikut adalah detailnya:</p>

    <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 20px; margin: 20px 0;">
        <h3 style="margin-top: 0;">📋 Detail Reservasi Anda:</h3>
        <p><strong>🏛️ Ruangan:</strong> {{ $reservation->roomInfo->nama_ruangan }}</p>
        <p><strong>📅 Tanggal:</strong> {{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}</p>
        <p><strong>⏰ Waktu:</strong> {{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}</p>
        <p><strong>📝 Keperluan:</strong> {{ $reservation->keperluan }}</p>
        <p><strong>📊 Status:</strong> <span style="color: #28a745; font-weight: bold;">DISETUJUI</span></p>
    </div>

    <h4>ℹ️ Informasi Penting:</h4>
    <ul>
        <li>Harap datang tepat waktu sesuai jadwal yang telah ditetapkan.</li>
        <li>Pastikan ruangan dalam kondisi bersih setelah digunakan.</li>
        <li>Hubungi admin jika ada perubahan atau pembatalan mendadak.</li>
    </ul>

    <p style="text-align: center;">
        <a href="{{ $url }}" class="button">Lihat Reservasi Saya</a>
    </p>

    <p>Terima kasih telah menggunakan layanan kami.</p>
    <br>
    <p>Hormat kami,<br>
    <strong>{{ config('app.name') }}</strong></p>
@endsection