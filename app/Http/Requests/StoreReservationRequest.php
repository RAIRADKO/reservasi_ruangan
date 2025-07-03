<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use App\Models\Reservation;
use App\Models\BlockedDate;

class StoreReservationRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true; // Izinkan semua user yang terautentikasi untuk membuat request
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'nama' => 'required|string|max:100',
            'kontak' => 'required|string|max:100',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keperluan' => 'required|string|max:255',
        ];
    }
    
    /**
     * Configure the validator instance.
     *
     * @param  \Illuminate\Validation\Validator  $validator
     * @return void
     */
    public function withValidator($validator)
    {
        $validator->after(function ($validator) {
            $tanggal = $this->input('tanggal');
            $jamMulai = $this->input('jam_mulai');
            $jamSelesai = $this->input('jam_selesai');

            // Cek apakah tanggal diblokir
            if (BlockedDate::where('date', $tanggal)->exists()) {
                $validator->errors()->add('blocked', 'Tanggal yang dipilih tidak tersedia untuk reservasi. Silakan pilih tanggal lain.');
                return; // Hentikan validasi jika tanggal sudah diblokir
            }

            // Cek konflik jadwal
            if (Reservation::hasConflict($tanggal, $jamMulai, $jamSelesai)) {
                $validator->errors()->add('conflict', 'Ruangan sudah dibooking pada jam tersebut. Silakan pilih jam lain.');
            }

            // Cek jam operasional dari file config
            if ($jamMulai < config('room.operating_hours.start') || $jamSelesai > config('room.operating_hours.end')) {
                 $validator->errors()->add(
                     'operational', 
                     'Jam operasional ruangan hanya dari ' . config('room.operating_hours.start') . ' sampai ' . config('room.operating_hours.end') . '.'
                 );
            }
        });
    }
}