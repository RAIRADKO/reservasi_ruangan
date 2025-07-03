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
        $room = RoomInfo::firstOrFail();
        
        // Mengambil tanggal yang penuh karena reservasi
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
            
        return view('home', compact('room', 'bookedDates'));
    }
    
    // PASTIKAN METHOD INI ADA
    public function showReservationsByDate($date)
    {
        try {
            $date = Carbon::parse($date)->format('Y-m-d');
        } catch (\Exception $e) {
            abort(404, 'Format tanggal tidak valid');
        }
        
        $reservations = Reservation::with('user')
            ->where('tanggal', $date)
            ->where('status', Reservation::STATUS_APPROVED)
            ->orderBy('jam_mulai')
            ->get();
            
        return view('reservations.date', compact('reservations', 'date'));
    }
}