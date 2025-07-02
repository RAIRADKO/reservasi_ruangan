<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
    public function profile()
    {
        $user = Auth::user();
        return view('user.profile', compact('user'));
    }

    public function reservations()
    {
        $user = Auth::user();
        $reservations = $user->reservations()
            ->orderBy('tanggal', 'desc')
            ->orderBy('jam_mulai', 'desc')
            ->paginate(10);
            
        return view('user.reservations', compact('reservations'));
    }

    public function cancelReservation(Request $request, $id)
    {
        $reservation = Reservation::where('user_id', Auth::id())
            ->where('id', $id)
            ->firstOrFail();

        if ($reservation->status !== Reservation::STATUS_PENDING) {
            return back()->with('error', 'Hanya reservasi pending yang bisa dibatalkan');
        }

        $reservation->update(['status' => Reservation::STATUS_CANCELED]);
        
        return back()->with('success', 'Reservasi berhasil dibatalkan');
    }
}