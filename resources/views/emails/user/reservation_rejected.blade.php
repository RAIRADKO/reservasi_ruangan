<x-mail::message>
# Reservasi Ruangan Anda Ditolak

Halo {{ $reservation->nama }},

Mohon maaf, reservasi ruangan yang Anda ajukan telah ditolak oleh admin.

**Detail Reservasi:**
- **Ruangan:** {{ $reservation->roomInfo->nama_ruangan }}
- **Tanggal:** {{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}
- **Waktu:** {{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}
- **Status:** <strong style="color: red;">Ditolak</strong>

**Alasan Penolakan:**
> {{ $reservation->rejection_reason }}

Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi pihak administrasi.

Terima kasih atas pengertiannya.<br>
Hormat kami,<br>
{{ config('app.name') }}
</x-mail::message>