<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Requests\StoreReservationRequest;
use App\Models\BlockedDate;
use App\Models\Dinas;
use App\Mail\NewReservationAdminNotification;
use Illuminate\Support\Facades\Mail;

class ReservationController extends Controller
{
    public function create()
    {
        $rooms = RoomInfo::all(); 
        $blockedDates = BlockedDate::pluck('date')->map->format('Y-m-d')->toArray();
        $dinas = Dinas::orderBy('name')->get(); 
        return view('reservations.create', compact('rooms', 'blockedDates', 'dinas'));
    }

    public function store(StoreReservationRequest $request)
    {
        $validatedData = $request->validated();
        $reservationData = [
            'user_id' => Auth::id(),
            'room_info_id' => $validatedData['room_info_id'],
            'dinas_id' => $validatedData['dinas_id'],
            'nama' => $validatedData['nama'],
            'kontak' => $validatedData['kontak'],
            'tanggal' => $validatedData['tanggal'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'keperluan' => $validatedData['keperluan'],
            'status' => Reservation::STATUS_PENDING,
        ];
        if (isset($validatedData['fasilitas']) && is_array($validatedData['fasilitas'])) {
            $reservationData['fasilitas_terpilih'] = implode(',', $validatedData['fasilitas']);
        } else {
            $reservationData['fasilitas_terpilih'] = null;
        }
        $reservation = Reservation::create($reservationData);

        $room = RoomInfo::find($validatedData['room_info_id']);
        $admins = \App\Models\Admin::where('instansi_id', $room->instansi_id)->get();
        foreach ($admins as $admin) {
            Mail::to($admin->email)->send(new NewReservationAdminNotification($reservation));
        }

        return redirect()->route('reservations.success');
    }

    public function success()
    {
        return view('reservations.success');
    }

    public function show(Reservation $reservation)
    {
        if (Auth::id() !== $reservation->user_id) {
            abort(403, 'Anda tidak memiliki akses untuk melihat reservasi ini.');
        }
        $reservation->load(['user', 'roomInfo', 'dinas']);
        return view('reservations.show', compact('reservation'));
    }

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