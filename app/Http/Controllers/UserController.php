<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;
use App\Mail\ReservationCanceledAdminNotification;
use Illuminate\Support\Facades\Mail;
use Carbon\Carbon;

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

        $reservation->status = Reservation::STATUS_CANCELED;
        $reservation->save();
        
        if ($wasApproved) {
            $adminEmail = config('mail.admin_address');
            if ($adminEmail) {
                Mail::to($adminEmail)->send(new ReservationCanceledAdminNotification($reservation));
            }
            return redirect()->route('user.reservations')->with('success', 'Reservasi yang telah disetujui berhasil dibatalkan. Admin telah diberitahu.');
        }
        
        return redirect()->route('user.reservations')->with('success', 'Reservasi berhasil dibatalkan');
    }

    public function updatePassword(Request $request)
    {
        $request->validate([
            'current_password' => ['required', 'current_password'],
            'new_password' => ['required', 'confirmed', Password::min(8)->mixedCase()->numbers()->symbols()],
        ], [
            'current_password.current_password' => 'Password saat ini tidak sesuai.',
            'new_password.confirmed' => 'Konfirmasi password baru tidak cocok.',
        ]);

        $user = Auth::user();
        $user->password = Hash::make($request->new_password);
        $user->save();

        return back()->with('password_success', 'Password Anda berhasil diperbarui.');
    }

    public function showCheckoutForm(Reservation $reservation)
    {
        if (Auth::id() !== $reservation->user_id) {
            abort(403, 'Akses ditolak.');
        }

        $endTime = Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->jam_selesai);

        if ($reservation->status !== Reservation::STATUS_APPROVED || $endTime->isFuture()) {
             return redirect()->route('user.reservations')->with('error', 'Reservasi ini belum dapat di-checkout.');
        }

        return view('user.checkout', compact('reservation'));
    }

    public function completeCheckout(Request $request, Reservation $reservation)
    {
        if (Auth::id() !== $reservation->user_id) {
            abort(403, 'Akses ditolak.');
        }
        $request->validate([
            'satisfaction_rating' => 'required|integer|between:1,5',
            'feedback' => 'required|string|max:1000',
        ], [
            'feedback.required' => 'Kolom saran dan masukan wajib diisi.',
            'satisfaction_rating.required' => 'Mohon berikan penilaian kepuasan Anda.'
        ]);
        $endTime = Carbon::parse($reservation->tanggal->toDateString() . ' ' . $reservation->jam_selesai);
        if ($reservation->status !== Reservation::STATUS_APPROVED || $endTime->isFuture()) {
             return redirect()->route('user.reservations')->with('error', 'Reservasi ini belum dapat di-checkout.');
        }
        $reservation->status = Reservation::STATUS_COMPLETED;
        $reservation->checked_out_at = now();
        $reservation->satisfaction_rating = $request->satisfaction_rating;
        $reservation->feedback = $request->feedback;
        $reservation->save();
        return redirect()->route('user.reservations')->with('success', 'Check-out berhasil! Terima kasih atas masukan Anda.');
    }
}