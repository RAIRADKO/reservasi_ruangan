<x-mail::message>
{{-- Header dengan Logo --}}
<x-mail::layout>
    <x-slot name="header">
        <table style="width: 100%; text-align: center; background: #dc2626; padding: 20px; border-radius: 10px;">
            <tr>
                <td>
                    @php
                        $logoUrl = config('app.logo_url', url('img/Lambang_Kabupaten_Purworejo.png'));
                    @endphp
                    <img src="{{ $logoUrl }}" alt="Logo Kabupaten Purworejo" style="height: 80px; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;" onerror="this.style.display='none'">
                    <h1 style="color: white; margin: 0; font-size: 24px;">Sistem Reservasi Ruangan</h1>
                    <p style="color: #fecaca; margin: 5px 0 0 0; font-size: 14px;">Kabupaten Purworejo</p>
                </td>
            </tr>
        </table>
    </x-slot>

# ❌ Reservasi Ruangan Anda Ditolak

Halo **{{ $reservation->nama }}**,

Mohon maaf, reservasi ruangan yang Anda ajukan telah **ditolak** oleh admin.

<x-mail::panel>
**📋 Detail Reservasi:**

**🏛️ Ruangan:** {{ $reservation->roomInfo->nama_ruangan }}  
**📅 Tanggal:** {{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}  
**⏰ Waktu:** {{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}  
**📊 Status:** **DITOLAK** ❌
</x-mail::panel>

**📝 Alasan Penolakan:**
> {{ $reservation->rejection_reason }}

**💡 Langkah Selanjutnya:**
- Anda dapat mengajukan reservasi ulang dengan waktu atau tanggal yang berbeda
- Hubungi pihak administrasi untuk informasi lebih lanjut
- Pastikan semua persyaratan reservasi telah dipenuhi

**📞 Butuh Bantuan?**  
Jika Anda memiliki pertanyaan lebih lanjut, silakan hubungi pihak administrasi.

---

Terima kasih atas pengertiannya.

**Hormat kami,**  
**{{ config('app.name') }}**  
*Sistem Reservasi Ruangan Kabupaten Purworejo*

</x-mail::layout>
</x-mail::message>