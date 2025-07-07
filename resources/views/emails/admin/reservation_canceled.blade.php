@extends('emails.layouts.custom')

@section('title', 'Pemberitahuan Pembatalan Reservasi')

@section('header', 'Reservasi Dibatalkan Pengguna')

@section('header_icon', '❌')

@section('content')
    <h2 style="color: #dc3545; margin-bottom: 20px;">
        ⚠️ Pemberitahuan Pembatalan Reservasi
    </h2>

    <p>Halo <strong>Admin</strong>,</p>

    <p>Sebuah reservasi yang telah disetujui telah <strong>dibatalkan oleh pengguna</strong>. Berikut adalah detailnya:</p>

    <div class="info-card">
        <h3>📋 Detail Reservasi yang Dibatalkan</h3>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Informasi</th>
                    <th>Detail</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>👤 Nama Pemohon</td>
                    <td>{{ $reservation->nama }}</td>
                </tr>
                <tr>
                    <td>🏛️ Instansi</td>
                    <td>{{ $reservation->dinas->name ?? 'N/A' }}</td>
                </tr>
                <tr>
                    <td>🚪 Ruangan</td>
                    <td>{{ $reservation->roomInfo->nama_ruangan }}</td>
                </tr>
                <tr>
                    <td>📅 Tanggal</td>
                    <td>{{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}</td>
                </tr>
                <tr>
                    <td>⏰ Waktu</td>
                    <td>{{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}</td>
                </tr>
                <tr>
                    <td>📝 Keperluan</td>
                    <td>{{ $reservation->keperluan }}</td>
                </tr>
                <tr>
                    <td>📊 Status Saat Ini</td>
                    <td><span class="status-badge status-rejected">Dibatalkan</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-list">
        <h4>ℹ️ Informasi Tambahan:</h4>
        <ul>
            <li>Reservasi ini sebelumnya berstatus <strong>'Disetujui'</strong>.</li>
            <li>Slot waktu yang sebelumnya dipesan sekarang tersedia kembali.</li>
            <li>Tidak ada tindakan lebih lanjut yang diperlukan dari Anda kecuali untuk tujuan pencatatan.</li>
        </ul>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" class="button">Lihat Riwayat Reservasi</a>
    </div>

    <div class="signature">
        <p>Ini adalah pemberitahuan otomatis.</p>
        <br>
        <p><strong>Sistem Reservasi Ruangan</strong><br>
        <em>{{ config('app.name') }}</em></p>
    </div>
@endsection