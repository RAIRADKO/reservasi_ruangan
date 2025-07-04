<x-mail::message>
{{-- Header dengan Logo --}}
<x-mail::layout>
    <x-slot name="header">
        <table style="width: 100%; text-align: center; background: #059669; padding: 20px; border-radius: 10px;">
            <tr>
                <td>
                    @php
                        $logoUrl = config('app.logo_url', url('img/Lambang_Kabupaten_Purworejo.png'));
                    @endphp
                    <img src="{{ $logoUrl }}" alt="Logo Kabupaten Purworejo" style="height: 80px; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;" onerror="this.style.display='none'">
                    <h1 style="color: white; margin: 0; font-size: 24px;">Sistem Reservasi Ruangan</h1>
                    <p style="color: #d1fae5; margin: 5px 0 0 0; font-size: 14px;">Kabupaten Purworejo</p>
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
*Sistem Reservasi Ruangan Kabupaten Purworejo*

</x-mail::layout>
</x-mail::message>