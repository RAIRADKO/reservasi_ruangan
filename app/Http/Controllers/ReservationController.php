<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReservationRequest;
use App\Models\BlockedDate;
use App\Models\Dinas;

class ReservationController extends Controller
{
    public function create()
    {
        $rooms = RoomInfo::all(); // Mengambil semua ruangan
        $blockedDates = BlockedDate::pluck('date')->map->format('Y-m-d')->toArray();
        $dinas = Dinas::orderBy('name')->get(); // Mengambil semua data dinas
        return view('reservations.create', compact('rooms', 'blockedDates', 'dinas'));
    }

    public function store(StoreReservationRequest $request)
    {
        Reservation::create([
            'user_id' => Auth::id(),
            'room_info_id' => $request->room_info_id,
            'dinas_id' => $request->dinas_id, // Menyimpan dinas_id
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

    /**
     * Check room availability for specific date and time
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_info_id' => 'required|exists:room_infos,id',
            'tanggal' => 'required|date',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $isBlocked = BlockedDate::where('date', $request->tanggal)->exists();
        
        if ($isBlocked) {
            return response()->json([
                'available' => false,
                'message' => 'Tanggal yang dipilih tidak tersedia untuk reservasi.'
            ]);
        }

        $hasConflict = Reservation::hasConflict(
            $request->tanggal,
            $request->jam_mulai,
            $request->jam_selesai,
            $request->room_info_id
        );

        if ($hasConflict) {
            // Get existing reservations for this date and room
            $existingReservations = Reservation::where('tanggal', $request->tanggal)
                ->where('room_info_id', $request->room_info_id)
                ->where('status', Reservation::STATUS_APPROVED)
                ->orderBy('jam_mulai')
                ->get(['jam_mulai', 'jam_selesai']);

            return response()->json([
                'available' => false,
                'message' => 'Ruangan sudah dibooking pada jam tersebut.',
                'existing_reservations' => $existingReservations->map(function ($reservation) {
                    return [
                        'jam_mulai' => date('H:i', strtotime($reservation->jam_mulai)),
                        'jam_selesai' => date('H:i', strtotime($reservation->jam_selesai)),
                    ];
                })
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Ruangan tersedia untuk waktu yang dipilih.'
        ]);
    }
}