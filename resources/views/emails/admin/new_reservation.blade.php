@extends('emails.layouts.custom')

@section('title', 'Permintaan Reservasi Baru')

@section('header', 'Permintaan Reservasi Baru')

@section('header_icon', '📋')

@section('content')
    <h2 style="color: #667eea; margin-bottom: 20px;">
        🔔 Permintaan Reservasi Baru Masuk
    </h2>

    <p>Halo <strong>Admin</strong>,</p>

    <p>Ada permintaan reservasi ruangan baru yang membutuhkan persetujuan Anda. Silakan tinjau detail berikut:</p>

    <div class="info-card">
        <h3>📋 Detail Permintaan Reservasi</h3>
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
                    <td>📊 Status</td>
                    <td><span class="status-badge status-pending">Menunggu Persetujuan</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-list">
        <h4>⚡ Tindakan yang Diperlukan:</h4>
        <ul>
            <li>Tinjau ketersediaan ruangan pada tanggal dan waktu yang diminta</li>
            <li>Periksa kelengkapan informasi pemohon</li>
            <li>Berikan persetujuan atau penolakan dengan alasan yang jelas</li>
            <li>Pastikan tidak ada konflik jadwal dengan reservasi lain</li>
        </ul>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" class="button">🔍 Tinjau & Proses Permintaan</a>
    </div>

    <div class="signature">
        <p>Harap segera ditindaklanjuti untuk memberikan kepastian kepada pemohon.</p>
        <p>Terima kasih atas perhatian dan kerjasamanya.</p>
        <br>
        <p><strong>Sistem Reservasi Ruangan</strong><br>
        <em>{{ config('app.name') }}</em></p>
    </div>
@endsection