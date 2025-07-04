@extends('emails.layouts.custom')

@section('title', 'Reservasi Anda Ditolak')

@section('header', 'Reservasi Ditolak')

@section('content')
    <h2 style="color: #dc3545;">❌ Reservasi Ruangan Anda Ditolak</h2>

    <p>Halo, <strong>{{ $reservation->nama }}</strong>.</p>

    <p>Mohon maaf, reservasi ruangan yang Anda ajukan telah ditolak oleh admin. Berikut adalah detail reservasi Anda:</p>

    <div style="background-color: #f8f9fa; border: 1px solid #dee2e6; border-radius: 5px; padding: 20px; margin: 20px 0;">
        <h3 style="margin-top: 0;">📋 Detail Reservasi:</h3>
        <p><strong>🏛️ Ruangan:</strong> {{ $reservation->roomInfo->nama_ruangan }}</p>
        <p><strong>📅 Tanggal:</strong> {{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}</p>
        <p><strong>⏰ Waktu:</strong> {{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}</p>
        <p><strong>📊 Status:</strong> <span style="color: #dc3545; font-weight: bold;">DITOLAK</span></p>
    </div>

    <h4>📝 Alasan Penolakan:</h4>
    <blockquote style="border-left: 4px solid #ffc107; padding-left: 15px; margin-left: 0; font-style: italic;">
        <p>{{ $reservation->rejection_reason }}</p>
    </blockquote>

    <h4>💡 Langkah Selanjutnya:</h4>
    <ul>
        <li>Anda dapat mengajukan reservasi ulang dengan waktu atau tanggal yang berbeda.</li>
        <li>Hubungi pihak administrasi untuk informasi lebih lanjut.</li>
    </ul>

    <p>Jika Anda memiliki pertanyaan, silakan hubungi pihak administrasi.</p>
    <br>
    <p>Terima kasih atas pengertiannya.</p>
    <p>Hormat kami,<br>
    <strong>{{ config('app.name') }}</strong></p>
@endsection