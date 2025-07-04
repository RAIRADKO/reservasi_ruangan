<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;
use App\Models\BlockedDate;


class RoomController extends Controller
{
    public function index()
    {
        $rooms = RoomInfo::all();
        $room = RoomInfo::firstOrFail(); // Keep for backward compatibility
        
        // Mengambil tanggal yang penuh karena reservasi (untuk semua ruangan)
        $reservationDates = Reservation::where('status', Reservation::STATUS_APPROVED)
            ->distinct()
            ->pluck('tanggal')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            });
            
        // Mengambil tanggal yang diblokir manual oleh admin
        $manualBlockedDates = BlockedDate::pluck('date')->map(function ($date) {
            return $date->format('Y-m-d');
        });
        
        // Menggabungkan keduanya dan memastikan tidak ada duplikat
        $bookedDates = $reservationDates->merge($manualBlockedDates)->unique()->values()->all();
            
        return view('home', compact('room', 'rooms', 'bookedDates'));
    }
    
    public function showReservationsByDate($date)
    {
        try {
            $date = Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            abort(404, 'Format tanggal tidak valid');
        }
        
        $reservations = Reservation::with(['user', 'roomInfo'])
            ->where('tanggal', $date)
            ->where('status', Reservation::STATUS_APPROVED)
            ->orderBy('jam_mulai')
            ->get();
            
        return view('reservations.date', compact('reservations', 'date'));
    }

    /**
     * Show reservations by date and room
     */
    public function showReservationsByDateAndRoom($date, $roomId = null)
    {
        try {
            $date = Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            abort(404, 'Format tanggal tidak valid');
        }
        
        $query = Reservation::with(['user', 'roomInfo'])
            ->where('tanggal', $date)
            ->where('status', Reservation::STATUS_APPROVED);
            
        if ($roomId) {
            $query->where('room_info_id', $roomId);
            $room = RoomInfo::findOrFail($roomId);
        } else {
            $room = null;
        }
        
        $reservations = $query->orderBy('jam_mulai')->get();
        $rooms = RoomInfo::all();
            
        return view('reservations.date', compact('reservations', 'date', 'room', 'rooms'));
    }
}