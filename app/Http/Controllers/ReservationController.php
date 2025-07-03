<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReservationRequest;
use App\Models\BlockedDate;

class ReservationController extends Controller
{
    public function create()
    {
        $rooms = RoomInfo::all(); // Mengambil semua ruangan
        $blockedDates = BlockedDate::pluck('date')->map->format('Y-m-d')->toArray();
        return view('reservations.create', compact('rooms', 'blockedDates'));
    }

    public function store(StoreReservationRequest $request)
    {
        Reservation::create([
            'user_id' => Auth::id(),
            'room_info_id' => $request->room_info_id, // Menyimpan room_info_id
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