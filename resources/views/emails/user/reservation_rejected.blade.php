@extends('emails.layouts.custom')

@section('title', 'Reservasi Anda Tidak Dapat Disetujui')

@section('header', 'Reservasi Tidak Disetujui')

@section('header_icon', '📋')

@section('content')
    <h2 style="color: #dc3545; margin-bottom: 25px;">
        📢 Pemberitahuan Reservasi
    </h2>

    <p>Halo, <strong>{{ $reservation->nama }}</strong>.</p>

    <p>Terima kasih atas pengajuan reservasi ruangan Anda. Setelah ditinjau oleh tim admin, mohon maaf reservasi Anda <strong>tidak dapat disetujui</strong> pada saat ini.</p>

    <div class="info-card">
        <h3>📋 Detail Reservasi yang Diajukan</h3>
        <table class="info-table">
            <thead>
                <tr>
                    <th>Informasi</th>
                    <th>Detail</th>
                </tr>
            </thead>
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
                    <td>📝 Keperluan</td>
                    <td>{{ $reservation->keperluan }}</td>
                </tr>
                <tr>
                    <td>📊 Status</td>
                    <td><span class="status-badge status-rejected">Tidak Disetujui</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-card" style="background: linear-gradient(135deg, #fff3cd 0%, #ffeaa7 100%); border: 1px solid #ffc107;">
        <h3 style="color: #856404;">📝 Alasan Tidak Disetujui:</h3>
        <div class="rejection-box">
            <p><strong>{{ $reservation->rejection_reason }}</strong></p>
        </div>
    </div>

    <div class="info-list">
        <h4>🔄 Langkah Selanjutnya yang Dapat Anda Lakukan:</h4>
        <ul>
            <li><strong>Ajukan Ulang:</strong> Anda dapat mengajukan reservasi dengan waktu atau tanggal yang berbeda</li>
            <li><strong>Pilih Ruangan Lain:</strong> Coba pilih ruangan alternatif yang mungkin tersedia</li>
            <li><strong>Hubungi Admin:</strong> Konsultasikan dengan admin untuk mendapatkan rekomendasi waktu yang tepat</li>
            <li><strong>Periksa Ketersediaan:</strong> Cek kalender ketersediaan ruangan sebelum mengajukan kembali</li>
        </ul>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url ?? '#' }}" class="button">🔄 Ajukan Reservasi Baru</a>
    </div>

    <div class="info-card" style="background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%); border: 1px solid #2196f3;">
        <h3 style="color: #1976d2;">💡 Tips untuk Reservasi Selanjutnya:</h3>
        <p style="margin: 0; color: #0d47a1;">
            Untuk meningkatkan peluang persetujuan, pastikan Anda mengajukan reservasi dengan waktu yang cukup (minimal 3 hari sebelumnya), 
            pilih waktu di luar jam sibuk, dan sertakan informasi keperluan yang jelas dan detail.
        </p>
    </div>

    <div class="signature">
        <p>Kami mohon maaf atas ketidaknyamanan ini. Jika Anda memiliki pertanyaan lebih lanjut, jangan ragu untuk menghubungi tim administrasi kami.</p>
        <p>Terima kasih atas pengertian dan kerjasamanya.</p>
        <br>
        <p>Hormat kami,<br>
        <strong>Tim {{ config('app.name') }}</strong><br>
        <em>Sistem Reservasi Ruangan</em></p>
    </div>
@endsection