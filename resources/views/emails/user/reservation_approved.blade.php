<x-mail::message>
{{-- Header Sederhana --}}
<x-mail::layout>
    <x-slot name="header">
        <table style="width: 100%; text-align: center; background: #059669; padding: 20px; border-radius: 10px;">
            <tr>
                <td>
                    <h1 style="color: white; margin: 0; font-size: 24px;">Sistem Reservasi Ruangan</h1>
                    <p style="color: #d1fae5; margin: 5px 0 0 0; font-size: 14px;">Sistem Manajemen Reservasi</p>
                </td>
            </tr>
        </table>
    </x-slot>

# ✅ Reservasi Ruangan Anda Telah Disetujui

Halo **{{ $reservation->nama }}**,

Kabar baik! Reservasi ruangan yang Anda ajukan telah **disetujui** oleh admin.

<x-mail::panel>
**📋 Detail Reservasi Anda:**

**🏛️ Ruangan:** {{ $reservation->roomInfo->nama_ruangan }}  
**📅 Tanggal:** {{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}  
**⏰ Waktu:** {{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}  
**📝 Keperluan:** {{ $reservation->keperluan }}  
**📊 Status:** **DISETUJUI** ✅
</x-mail::panel>

**ℹ️ Informasi Penting:**
- Harap datang tepat waktu sesuai jadwal yang telah ditetapkan
- Pastikan ruangan dalam kondisi bersih setelah digunakan
- Hubungi admin jika ada perubahan atau pembatalan mendadak

<x-mail::button :url="$url">
📋 Lihat Reservasi Saya
</x-mail::button>

---

Terima kasih telah menggunakan layanan kami.

**Hormat kami,**  
**{{ config('app.name') }}**  
*Sistem Reservasi Ruangan*

</x-mail::layout>
</x-mail::message>
