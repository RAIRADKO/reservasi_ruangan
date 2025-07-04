<x-mail::message>
# Permintaan Reservasi Ruangan Baru

Halo Admin,

Ada permintaan reservasi ruangan baru yang membutuhkan persetujuan Anda.

**Detail Reservasi:**
- **Nama Pemohon:** {{ $reservation->nama }}
- **Instansi:** {{ $reservation->dinas->name ?? 'N/A' }}
- **Ruangan:** {{ $reservation->roomInfo->nama_ruangan }}
- **Tanggal:** {{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}
- **Waktu:** {{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}
- **Keperluan:** {{ $reservation->keperluan }}

Silakan tinjau permintaan ini di panel admin.

<x-mail::button :url="$url">
Lihat Permintaan Reservasi
</x-mail::button>

Terima kasih,<br>
{{ config('app.name') }}
</x-mail::message>