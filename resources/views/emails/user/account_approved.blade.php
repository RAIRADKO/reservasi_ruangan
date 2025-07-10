@extends('emails.layouts.custom')

@section('title', 'Akun Anda Telah Disetujui')

@section('header', 'Persetujuan Akun Berhasil')

@section('header_icon', '✅')

@section('content')
    <h2 style="color: #28a745; margin-bottom: 25px;">
        🎉 Selamat! Akun Anda Telah Aktif
    </h2>

    <p>Halo, <strong>{{ $user->name }}</strong>!</p>

    <p>Kabar baik! Akun Anda untuk Sistem Reservasi Ruangan telah <strong>disetujui</strong> oleh admin. Sekarang Anda dapat login dan mulai melakukan reservasi.</p>

    <div class="info-card">
        <h3>🔑 Informasi Akun Anda</h3>
        <table class="info-table">
            <tbody>
                <tr>
                    <td>👤 Nama</td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td>💳 NIP (untuk Login)</td>
                    <td>{{ $user->nip }}</td>
                </tr>
                <tr>
                    <td>📧 Email</td>
                    <td>{{ $user->email }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" class="button">Login Sekarang</a>
    </div>

    <div class="signature">
        <p>Terima kasih telah bergabung. Kami berharap dapat memberikan layanan terbaik untuk Anda.</p>
        <br>
        <p>Hormat kami,<br>
        <strong>Tim {{ config('app.name') }}</strong></p>
    </div>
@endsection