@extends('emails.layouts.custom')

@section('title', 'Reservasi Anda Telah Disetujui')

@section('header', 'Reservasi Disetujui')

@section('header_icon', '✅')

@section('content')
    <h2 style="color: #28a745; margin-bottom: 25px;">
        🎉 Selamat! Reservasi Anda Telah Disetujui
    </h2>

    <p>Halo, <strong>{{ $reservation->nama }}</strong>!</p>

    <p>Kabar baik untuk Anda! Reservasi ruangan yang Anda ajukan telah <strong>disetujui</strong> oleh admin. Anda dapat menggunakan ruangan sesuai dengan jadwal yang telah ditentukan.</p>

    <div class="info-card">
        <h3>📋 Detail Reservasi Anda</h3>
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
                    <td><span class="status-badge status-approved">Disetujui</span></td>
                </tr>
            </tbody>
        </table>
    </div>

    <div class="info-list">
        <h4>📌 Hal Penting yang Perlu Diperhatikan:</h4>
        <ul>
            <li><strong>Kehadiran:</strong> Harap datang tepat waktu sesuai jadwal yang telah ditetapkan</li>
            <li><strong>Kebersihan:</strong> Pastikan ruangan dalam kondisi bersih dan rapi setelah digunakan</li>
            <li><strong>Fasilitas:</strong> Gunakan fasilitas ruangan dengan bijak dan bertanggung jawab</li>
            <li><strong>Perubahan:</strong> Hubungi admin jika ada perubahan atau pembatalan mendadak</li>
            <li><strong>Waktu:</strong> Patuhi batas waktu penggunaan yang telah ditentukan</li>
        </ul>
    </div>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" class="button">📱 Lihat Detail Reservasi</a>
    </div>

    <div class="info-card" style="background: linear-gradient(135deg, #e8f5e8 0%, #d4edda 100%); border: 1px solid #c3e6cb;">
        <h3 style="color: #28a745;">💡 Tips Penggunaan Ruangan:</h3>
        <p style="margin: 0; color: #155724;">Untuk pengalaman terbaik, pastikan Anda sudah familiar dengan tata letak ruangan dan fasilitas yang tersedia. Jika memerlukan bantuan teknis, jangan ragu untuk menghubungi petugas yang bertugas.</p>
    </div>

    <div class="signature">
        <p>Terima kasih telah menggunakan layanan reservasi ruangan kami. Semoga kegiatan Anda berjalan lancar dan sukses!</p>
        <br>
        <p>Hormat kami,<br>
        <strong>Tim {{ config('app.name') }}</strong><br>
        <em>Sistem Reservasi Ruangan</em></p>
    </div>
@endsection