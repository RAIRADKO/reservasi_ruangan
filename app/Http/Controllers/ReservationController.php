<?php
namespace App\Http\Controllers;

use App\Http\Requests\StoreReservationRequest;
use App\Models\BlockedDate;
use App\Models\Dinas;
use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ReservationController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $reservations = Reservation::where('user_id', $user->id)
                                   ->with('roomInfo')
                                   ->orderBy('tanggal', 'desc')
                                   ->paginate(10);

        return view('reservations.index', compact('reservations'));
    }

    public function create()
    {
        $rooms = RoomInfo::orderBy('nama_ruangan')->get();
        $blockedDates = BlockedDate::pluck('date')->map->format('Y-m-d')->toArray();
        $dinas = Dinas::orderBy('name')->get(); // Ambil data dinas

        return view('reservations.create', compact('rooms', 'blockedDates', 'dinas'));
    }

    public function store(StoreReservationRequest $request)
    {
        $validatedData = $request->validated();
        $user = Auth::user();

        // Pengecekan konflik sekarang sudah dihandle oleh StoreReservationRequest

        $reservationData = array_merge($validatedData, [
            'user_id' => $user->id,
            'nama' => $user->name,
            'kontak' => $user->nip,
            'fasilitas_terpilih' => isset($validatedData['fasilitas']) ? implode(',', $validatedData['fasilitas']) : null,
            'status' => Reservation::STATUS_PENDING,
            'tanggal_selesai' => $request->input('tanggal_selesai', $request->input('tanggal')), // Simpan tanggal selesai
        ]);

        Reservation::create($reservationData);

        return redirect()->route('user.reservations')->with('success', 'Reservasi Anda telah berhasil dikirim.');
    }

    public function cancel(Reservation $reservation)
    {
        if ($reservation->user_id !== Auth::id()) {
            abort(403, 'Anda tidak memiliki hak untuk membatalkan reservasi ini.');
        }

        if ($reservation->status !== Reservation::STATUS_PENDING) {
            return back()->with('error', 'Reservasi yang sudah diproses tidak dapat dibatalkan.');
        }

        $reservation->update(['status' => Reservation::STATUS_CANCELED]);

        return back()->with('success', 'Reservasi berhasil dibatalkan.');
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
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
        ]);

        $tanggalMulai = $request->tanggal;
        $tanggalSelesai = $request->tanggal_selesai ?? $tanggalMulai;

        // Cek tanggal yang diblokir
        $isBlocked = BlockedDate::whereBetween('date', [$tanggalMulai, $tanggalSelesai])->exists();
        if ($isBlocked) {
            return response()->json([
                'available' => false,
                'message' => 'Satu atau lebih tanggal dalam rentang yang dipilih tidak tersedia.'
            ]);
        }

        // Cek konflik
        $hasConflict = Reservation::hasConflict(
            $tanggalMulai,
            $tanggalSelesai,
            $request->jam_mulai,
            $request->jam_selesai,
            $request->room_info_id
        );

        if ($hasConflict) {
            return response()->json([
                'available' => false,
                'message' => 'Ruangan sudah dibooking pada rentang tanggal dan jam tersebut.',
            ]);
        }

        return response()->json([
            'available' => true,
            'message' => 'Ruangan tersedia untuk waktu yang dipilih.'
        ]);
    }
}