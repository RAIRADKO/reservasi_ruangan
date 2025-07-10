@extends('emails.layouts.custom')

@section('title', 'Pengingat Check Out')

@section('header', 'Jangan Lupa Check Out!')

@section('header_icon', '⏰')

@section('content')
    <h2 style="color: #ffc107; margin-bottom: 25px;">
        📢 Pengingat Sesi Reservasi Telah Berakhir
    </h2>

    <p>Halo, <strong>{{ $reservation->nama }}</strong>!</p>

    <p>Sistem kami mencatat bahwa sesi reservasi Anda untuk ruangan <strong>{{ $reservation->roomInfo->nama_ruangan }}</strong> telah berakhir 15 menit yang lalu, namun Anda belum melakukan proses <i>check out</i>.</p>

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
            </tbody>
        </table>
    </div>

    <div class="info-list">
        <h4>❓ Mengapa Check Out Penting?</h4>
        <ul>
            <li>Memberi tahu kami bahwa Anda telah selesai menggunakan ruangan.</li>
            <li>Memungkinkan kami mengumpulkan masukan Anda untuk perbaikan layanan.</li>
            <li>Memperbarui status ruangan agar dapat digunakan oleh pengguna lain.</li>
        </ul>
    </div>

    <p>Mohon untuk segera melakukan <i>check out</i> dengan menekan tombol di bawah ini. Notifikasi ini akan terus dikirim setiap 15 menit hingga Anda melakukan <i>check out</i>.</p>

    <div style="text-align: center; margin: 30px 0;">
        <a href="{{ $url }}" class="button" style="background: linear-gradient(135deg, #198754 0%, #20c997 100%);">✅ Lakukan Check Out Sekarang</a>
    </div>

    <div class="signature">
        <p>Terima kasih atas perhatian dan kerjasamanya.</p>
        <br>
        <p>Hormat kami,<br>
        <strong>Tim {{ config('app.name') }}</strong></p>
    </div>
@endsection