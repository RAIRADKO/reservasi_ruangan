<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function create()
    {
        $room = RoomInfo::first();
        return view('reservations.create', compact('room'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:100',
            'kontak' => 'required|string|max:100',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keperluan' => 'required|string|max:255',
        ]);

        if (Reservation::hasConflict($request->tanggal, $request->jam_mulai, $request->jam_selesai)) {
            return back()->withErrors([
                'conflict' => 'Ruangan sudah dibooking pada jam tersebut. Silakan pilih jam lain.'
            ])->withInput();
        }

        if ($request->jam_mulai < '08:00' || $request->jam_selesai > '17:00') {
            return back()->withErrors([
                'operational' => 'Jam operasional ruangan adalah 08:00 - 17:00'
            ])->withInput();
        }

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