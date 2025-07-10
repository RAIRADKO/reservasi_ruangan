@extends('emails.layouts.custom')

@section('title', 'Pengajuan Registrasi Akun Baru')

@section('header', 'Akun Baru Menunggu Persetujuan')

@section('header_icon', '👤')

@section('content')
    <h2 style="color: #667eea; margin-bottom: 20px;">
        📬 Pengajuan Registrasi Akun Baru
    </h2>

    <p>Halo <strong>Admin</strong>,</p>

    <p>Ada pengguna baru yang telah mendaftar dan menunggu persetujuan Anda. Berikut adalah detailnya:</p>

    <div class="info-card">
        <h3>📋 Detail Pengguna Baru</h3>
        <table class="info-table">
            <tbody>
                <tr>
                    <td>👤 Nama</td>
                    <td>{{ $user->name }}</td>
                </tr>
                <tr>
                    <td>📧 Email</td>
                    <td>{{ $user->email }}</td>
                </tr>
                 <tr>
                    <td>💳 NIP</td>
                    <td>{{ $user->nip }}</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" class="button">Proses Pengajuan</a>
    </div>

    <div class="signature">
        <p>Harap segera ditindaklanjuti agar pengguna dapat segera menggunakan layanan.</p>
    </div>
@endsection