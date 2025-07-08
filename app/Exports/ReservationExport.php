<?php

namespace App\Exports;

use App\Models\Reservation;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Maatwebsite\Excel\Concerns\WithStyles;

class ReservationExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithStyles
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        // Mengambil semua data reservasi dengan relasi yang dibutuhkan
        return Reservation::with(['user', 'roomInfo', 'dinas'])->orderBy('tanggal', 'desc')->get();
    }

    /**
     * @return array
     */
    public function headings(): array
    {
        // Mendefinisikan judul untuk setiap kolom di file Excel
        return [
            'ID',
            'Tanggal Reservasi',
            'Jam Mulai',
            'Jam Selesai',
            'Nama Pemohon',
            'NIP Pemohon',
            'Instansi/Dinas',
            'Ruangan',
            'Keperluan',
            'Fasilitas yang Digunakan',
            'Status',
            'Alasan Penolakan',
            'Tanggal Pengajuan',
            'Waktu Dibatalkan',
            'Waktu Check Out',
        ];
    }

    /**
     * @param mixed $reservation
     *
     * @return array
     */
    public function map($reservation): array
    {
        $waktu_disetujui = '-';
        if ($reservation->status === 'approved') {
            $waktu_disetujui = $reservation->updated_at->format('d-m-Y H:i:s');
        }

        $waktu_dibatalkan = '-';
        if ($reservation->status === 'canceled') {
            $waktu_dibatalkan = $reservation->updated_at->format('d-m-Y H:i:s');
        }

        return [
            $reservation->id,
            $reservation->tanggal->format('d-m-Y'),
            date('H:i', strtotime($reservation->jam_mulai)),
            date('H:i', strtotime($reservation->jam_selesai)),
            $reservation->nama,
            $reservation->user->nip ?? 'N/A',
            $reservation->dinas->name ?? 'N/A',
            $reservation->roomInfo->nama_ruangan ?? 'N/A',
            $reservation->keperluan,
            $reservation->fasilitas_terpilih ?? '-',
            ucfirst($reservation->status),
            $reservation->rejection_reason ?? '-',
            $reservation->created_at->format('d-m-Y H:i:s'),
            $waktu_dibatalkan,
            $reservation->checked_out_at ? $reservation->checked_out_at->format('d-m-Y H:i:s') : '-',
        ];
    }
    
    /**
     * Memberikan style pada sheet
     *
     * @param Worksheet $sheet
     * @return array
     */
    public function styles(Worksheet $sheet)
    {
        return [
            1    => [
                'font' => ['bold' => true, 'size' => 12, 'color' => ['argb' => 'FFFFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => [
                        'argb' => 'FF4F81BD', // Warna biru
                    ],
                ],
            ],
        ];
    }
}