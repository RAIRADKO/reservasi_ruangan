<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Reservation;
use App\Models\BlockedDate;

class StoreReservationRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'room_info_id' => 'required|exists:room_infos,id',
            'dinas_id' => 'required|exists:dinas,id',
            'nama' => 'required|string|max:100',
            'kontak' => 'required|string|max:100',
            'tanggal' => 'required|date|after_or_equal:today',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal', // Ditambahkan
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keperluan' => 'required|string|max:255',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string|max:100',
        ];
    }

    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tanggalMulai = $this->input('tanggal');
            $tanggalSelesai = $this->input('tanggal_selesai', $tanggalMulai); // Gunakan tanggal mulai jika tanggal selesai kosong
            $jamMulai = $this->input('jam_mulai');
            $jamSelesai = $this->input('jam_selesai');
            $roomInfoId = $this->input('room_info_id');

            // Cek tanggal yang diblokir
            $blocked = BlockedDate::whereBetween('date', [$tanggalMulai, $tanggalSelesai])->exists();
            if ($blocked) {
                $validator->errors()->add('blocked', 'Satu atau lebih tanggal dalam rentang yang Anda pilih tidak tersedia.');
                return;
            }

            // Cek konflik jadwal
            if (Reservation::hasConflict($tanggalMulai, $tanggalSelesai, $jamMulai, $jamSelesai, $roomInfoId)) {
                $validator->errors()->add('conflict', 'Ruangan sudah dibooking pada rentang tanggal dan jam tersebut.');
            }

            // Cek jam operasional
            if ($jamMulai < config('room.operating_hours.start') || $jamSelesai > config('room.operating_hours.end')) {
                 $validator->errors()->add(
                     'operational',
                     'Jam operasional hanya dari ' . config('room.operating_hours.start') . ' sampai ' . config('room.operating_hours.end') . '.'
                 );
            }
        });
    }
}