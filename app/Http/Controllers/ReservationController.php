<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReservationRequest; // Import Form Request

class ReservationController extends Controller
{
    public function create()
    {
        $room = RoomInfo::first();
        return view('reservations.create', compact('room'));
    }

    public function store(StoreReservationRequest $request) // Gunakan Form Request di sini
    {
        // Validasi sudah ditangani secara otomatis oleh StoreReservationRequest
        // Jika validasi gagal, Laravel akan otomatis redirect kembali dengan error.

        Reservation::create([
            'user_id' => Auth::id(),
            'nama' => $request->nama,
            'kontak' => $request->kontak,
            'tanggal' => $request->tanggal,
            'jam_mulai' => $request->jam_mulai,
            'jam_selesai' => $request->jam_selesai,
            'keperluan' => $request->keperluan,
            'status' => Reservation::STATUS_PENDING,
        ]);

        return redirect()->route('reservations.success');
    }

    public function success()
    {
        return view('reservations.success');
    }
}