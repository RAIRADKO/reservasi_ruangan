@extends('emails.layouts.custom')

@section('title', 'Check Out Otomatis')

@section('header', 'Reservasi Telah Selesai')

@section('header_icon', '✅')

@section('content')
    <h2 style="color: #dc3545; margin-bottom: 25px;">
        🔔 Reservasi Telah Di-Check Out Otomatis
    </h2>

    <p>Halo, <strong>{{ $reservation->nama }}</strong>!</p>

    <p>Sistem kami telah secara otomatis melakukan <i>check out</i> untuk reservasi Anda di ruangan <strong>{{ $reservation->roomInfo->nama_ruangan }}</strong> karena Anda tidak melakukan <i>check out</i> dalam waktu 12 jam setelah sesi reservasi berakhir.</p>

    <div class="info-card">
        <h3>📋 Detail Reservasi Anda</h3>
        <table class="info-table">
            <tbody>
                <tr>
                    <td>🏛️ Ruangan</td>
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
                    <td>✅ Status</td>
                    <td><span style="color: #28a745; font-weight: bold;">Selesai (Auto Check-out)</span></td>
                </tr>
                <tr>
                    <td>⭐ Rating</td>
                    <td>5/5 (Default)</td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-list">
        <h4>📝 Informasi Penting</h4>
        <ul>
            <li>Status reservasi Anda telah diubah menjadi <strong>Selesai</strong>.</li>
            <li>Sistem memberikan rating default <strong>5/5</strong> untuk reservasi ini.</li>
            <li>Feedback default: "Check-out otomatis oleh sistem setelah 12 jam."</li>
            <li>Ruangan telah tersedia kembali untuk pengguna lain.</li>
        </ul>
    </div>

    <div class="info-card" style="background-color: #fff3cd; border-left: 4px solid #ffc107;">
        <h4 style="color: #856404;">⚠️ Catatan Penting</h4>
        <p style="color: #856404; margin-bottom: 0;">
            Untuk pengalaman yang lebih baik di masa depan, mohon lakukan <i>check out</i> secara manual setelah selesai menggunakan ruangan. Ini membantu kami memberikan layanan yang lebih baik untuk semua pengguna.
        </p>
    </div>

    <p>Anda dapat melihat detail lengkap reservasi ini dengan mengklik tombol di bawah ini:</p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" class="button" style="background: linear-gradient(135deg, #007bff 0%, #0056b3 100%);">📄 Lihat Detail Reservasi</a>
    </div>

    <div class="signature">
        <p>Terima kasih telah menggunakan layanan kami.</p>
        <br>
        <p>Hormat kami,<br>
        <strong>Tim {{ config('app.name') }}</strong></p>
    </div>
@endsection