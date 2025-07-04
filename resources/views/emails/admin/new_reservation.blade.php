<x-mail::message>
{{-- Header dengan Logo --}}
<x-mail::layout>
    <x-slot name="header">
        <table style="width: 100%; text-align: center; background: #1e3a8a; padding: 20px; border-radius: 10px;">
            <tr>
                <td>
                    @php
                        $logoUrl = config('app.logo_url', url('img/Lambang_Kabupaten_Purworejo.png'));
                    @endphp
                    <img src="{{ $logoUrl }}" alt="Logo Kabupaten Purworejo" style="height: 80px; margin-bottom: 15px; display: block; margin-left: auto; margin-right: auto;" onerror="this.style.display='none'">
                    <h1 style="color: white; margin: 0; font-size: 24px;">Sistem Reservasi Ruangan</h1>
                    <p style="color: #e2e8f0; margin: 5px 0 0 0; font-size: 14px;">Kabupaten Purworejo</p>
                </td>
            </tr>
        </table>
    </x-slot>

# 📋 Permintaan Reservasi Ruangan Baru

Halo Admin,

Ada permintaan reservasi ruangan baru yang membutuhkan persetujuan Anda.

<x-mail::panel>
**📄 Detail Reservasi:**

**👤 Nama Pemohon:** {{ $reservation->nama }}  
**🏢 Instansi:** {{ $reservation->dinas->name ?? 'N/A' }}  
**🏛️ Ruangan:** {{ $reservation->roomInfo->nama_ruangan }}  
**📅 Tanggal:** {{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}  
**⏰ Waktu:** {{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}  
**📝 Keperluan:** {{ $reservation->keperluan }}
</x-mail::panel>

⚠️ **Tindakan Diperlukan:** Silakan tinjau dan berikan persetujuan untuk permintaan reservasi ini.

<x-mail::button :url="$url">
🔍 Lihat Permintaan Reservasi
</x-mail::button>

---

Terima kasih atas perhatiannya.

**{{ config('app.name') }}**  
*Sistem Reservasi Ruangan Kabupaten Purworejo*

</x-mail::layout>
</x-mail::message>