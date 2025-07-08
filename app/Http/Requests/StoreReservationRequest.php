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
            $tanggal = $this->input('tanggal');
            $jamMulai = $this->input('jam_mulai');
            $jamSelesai = $this->input('jam_selesai');
            $roomInfoId = $this->input('room_info_id'); 

            if (BlockedDate::where('date', $tanggal)->exists()) {
                $validator->errors()->add('blocked', 'Tanggal yang dipilih tidak tersedia untuk reservasi. Silakan pilih tanggal lain.');
                return;
            }

            // Periksa konflik jadwal untuk ruangan yang spesifik
            if (Reservation::hasConflict($tanggal, $jamMulai, $jamSelesai, $roomInfoId)) {
                $validator->errors()->add('conflict', 'Ruangan sudah dibooking pada jam tersebut. Silakan pilih jam lain.');
            }

            if ($jamMulai < config('room.operating_hours.start') || $jamSelesai > config('room.operating_hours.end')) {
                 $validator->errors()->add(
                     'operational', 
                     'Jam operasional ruangan hanya dari ' . config('room.operating_hours.start') . ' sampai ' . config('room.operating_hours.end') . '.'
                 );
            }
        });
    }
}