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

        // Cek konflik jadwal sebelum membuat reservasi
        if (Reservation::hasConflict($validatedData['tanggal'], $validatedData['jam_mulai'], $validatedData['jam_selesai'], $validatedData['room_info_id'])) {
            return back()->with('error', 'Ruangan sudah dibooking pada jam tersebut. Silakan pilih jam lain.');
        }

        // Gabungkan data yang divalidasi dengan user_id dan status
        $reservationData = array_merge($validatedData, [
            'user_id' => $user->id,
            'nama' => $user->name, // Ambil nama dari user yang login
            'kontak' => $user->nip, // Ambil NIP sebagai kontak
            'fasilitas_terpilih' => isset($validatedData['fasilitas']) ? implode(',', $validatedData['fasilitas']) : null,
            // --- INI BAGIAN PENTING ---
            // Pastikan status default adalah 'pending'
            'status' => Reservation::STATUS_PENDING, 
        ]);

        Reservation::create($reservationData);

        return redirect()->route('user.reservations')->with('success', 'Reservasi Anda telah berhasil dikirim dan sedang menunggu persetujuan.');
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