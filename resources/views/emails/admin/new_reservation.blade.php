<x-mail::message>
{{-- Header Sederhana --}}
<x-mail::layout>
    <x-slot name="header">
        <table style="width: 100%; text-align: center; background: #1e3a8a; padding: 20px; border-radius: 10px;">
            <tr>
                <td>
                    <h1 style="color: white; margin: 0; font-size: 24px;">Sistem Reservasi Ruangan</h1>
                    <p style="color: #e2e8f0; margin: 5px 0 0 0; font-size: 14px;">Sistem Manajemen Reservasi</p>
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
*Sistem Reservasi Ruangan*

</x-mail::layout>
</x-mail::message>
