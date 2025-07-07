<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

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

    /**
     * Update the user's password.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse
     */
    public function updatePassword(Request $request)
    {
        # Validasi input
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
            'new_password.min' => 'Password baru minimal harus 8 karakter.',
        ]);

        # Update password pengguna
        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('password_success', 'Password Anda berhasil diperbarui.');
    }
}