{{-- Hapus semua kode <x-mail::...> yang lama --}}

@extends('emails.layouts.custom')

@section('title', 'Permintaan Reservasi Baru')

@section('header', 'Permintaan Reservasi Baru')

@section('content')
    <p>Halo Admin,</p>

    <p>Ada permintaan reservasi ruangan baru yang membutuhkan persetujuan Anda. Berikut adalah detailnya:</p>

    <table style="width: 100%; border-collapse: collapse; margin: 20px 0;">
        <tr style="background-color: #f9f9f9;">
            <td style="padding: 8px; border: 1px solid #ddd; width: 30%;"><strong>Nama Pemohon</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $reservation->nama }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Instansi</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $reservation->dinas->name ?? 'N/A' }}</td>
        </tr>
        <tr style="background-color: #f9f9f9;">
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Ruangan</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $reservation->roomInfo->nama_ruangan }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Tanggal</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $reservation->tanggal->isoFormat('dddd, D MMMM YYYY') }}</td>
        </tr>
        <tr style="background-color: #f9f9f9;">
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Waktu</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ date('H:i', strtotime($reservation->jam_mulai)) }} - {{ date('H:i', strtotime($reservation->jam_selesai)) }}</td>
        </tr>
        <tr>
            <td style="padding: 8px; border: 1px solid #ddd;"><strong>Keperluan</strong></td>
            <td style="padding: 8px; border: 1px solid #ddd;">{{ $reservation->keperluan }}</td>
        </tr>
    </table>

    <p>Silakan tinjau dan berikan persetujuan untuk permintaan reservasi ini.</p>

    <a href="{{ $url }}" class="button">Lihat Permintaan Reservasi</a>

    <p>Terima kasih atas perhatiannya.</p>
@endsection