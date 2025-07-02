<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Carbon\Carbon;

class RoomController extends Controller
{
    public function index()
    {
        $room = RoomInfo::first();
        
        $bookedDates = Reservation::where('status', Reservation::STATUS_APPROVED)
            ->select('tanggal')
            ->distinct()
            ->pluck('tanggal')
            ->map(function ($date) {
                return $date->format('Y-m-d');
            })
            ->toArray();
            
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