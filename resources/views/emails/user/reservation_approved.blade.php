<x-mail::message>
# Reservasi Ruangan Anda Telah Disetujui

Halo {{ $reservation->nama }},

Kabar baik! Reservasi ruangan yang Anda ajukan telah disetujui oleh admin.

**Detail Reservasi:**
- **Ruangan:** {{ $reservation->roomInfo->nama_ruangan }}
- **Tanggal:** {{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}
- **Waktu:** {{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}
- **Keperluan:** {{ $reservation->keperluan }}
- **Status:** <strong style="color: green;">Disetujui</strong>

Anda dapat melihat detail reservasi Anda melalui tombol di bawah ini.

<x-mail::button :url="$url">
Lihat Reservasi Saya
</x-mail::button>

Terima kasih telah menggunakan layanan kami.<br>
Hormat kami,<br>
{{ config('app.name') }}
</x-mail::message>