<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Mail\ReservationCanceledAdminNotification;
use Illuminate\Support\Facades\Mail;

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

        $allowedStatuses = [Reservation::STATUS_PENDING, Reservation::STATUS_APPROVED];

        if (!in_array($reservation->status, $allowedStatuses)) {
            return redirect()->route('user.reservations')->with('error', 'Hanya reservasi dengan status pending atau disetujui yang bisa dibatalkan.');
        }

        $wasApproved = $reservation->status === Reservation::STATUS_APPROVED;

        // Mengubah metode update menjadi lebih eksplisit
        $reservation->status = Reservation::STATUS_CANCELED;
        $reservation->save();
        
        if ($wasApproved) {
            // Jika reservasi yang dibatalkan sudah disetujui, kirim email ke admin
            $adminEmail = config('mail.admin_address');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new ReservationCanceledAdminNotification($reservation));
            }
            return redirect()->route('user.reservations')->with('success', 'Reservasi yang telah disetujui berhasil dibatalkan. Admin telah diberitahu.');
        }
        
        return redirect()->route('user.reservations')->with('success', 'Reservasi berhasil dibatalkan');
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