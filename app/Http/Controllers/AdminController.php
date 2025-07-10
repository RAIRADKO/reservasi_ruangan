<?php

namespace App\Http\Controllers;

use App\Exports\ReservationExport;
use App\Mail\ReservationApprovedUserNotification;
use App\Mail\ReservationRejectedUserNotification;
use App\Mail\UserApprovedNotification; 
use App\Models\BlockedDate;
use App\Models\Dinas;
use App\Models\Reservation;
use App\Models\RoomInfo;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;

class AdminController extends Controller
{
    /**
     * Menampilkan dashboard admin dengan data statistik utama.
     */
    public function dashboard()
    {
        $pendingCount = Reservation::where('status', Reservation::STATUS_PENDING)->count();
        $approvedCount = Reservation::where('status', Reservation::STATUS_APPROVED)->count();
        $completedCount = Reservation::where('status', Reservation::STATUS_COMPLETED)->count();
        $userCount = User::count();

        $reservations = Reservation::with(['user', 'roomInfo'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('pendingCount', 'approvedCount', 'completedCount', 'reservations', 'userCount'));
    }

    /**
     * Menampilkan daftar semua reservasi.
     */
    public function reservations()
    {
        $reservations = Reservation::with(['user', 'roomInfo', 'dinas'])
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
            
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Menangani permintaan untuk mengekspor data reservasi ke file Excel.
     */
    public function exportReservations()
    {
        $fileName = 'laporan-riwayat-reservasi-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ReservationExport(), $fileName);
    }

    /**
     * Memperbarui status reservasi.
     */
    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(Reservation::statusOptions()))],
            'rejection_reason' => 'required_if:status,rejected|string|nullable',
        ]);
        
        $oldStatus = $reservation->status;
        $newStatus = $request->status;
        
        $updateData = ['status' => $newStatus];

        if ($newStatus === Reservation::STATUS_REJECTED) {
            $updateData['rejection_reason'] = $request->rejection_reason;
        } else {
            $updateData['rejection_reason'] = null;
        }

        $reservation->update($updateData);
        $reservation->load('user');

        if ($newStatus === Reservation::STATUS_APPROVED && $oldStatus !== Reservation::STATUS_APPROVED) {
            Mail::to($reservation->user->email)->send(new ReservationApprovedUserNotification($reservation));
        }

        if ($newStatus === Reservation::STATUS_REJECTED && $oldStatus !== Reservation::STATUS_REJECTED) {
            Mail::to($reservation->user->email)->send(new ReservationRejectedUserNotification($reservation));
        }
        
        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    /**
     * Menghapus data reservasi.
     */
    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back()->with('success', 'Reservasi berhasil dihapus.');
    }

    /**
     * Menampilkan daftar ruangan.
     */
    public function roomIndex()
    {
        $rooms = RoomInfo::paginate(10);
        return view('admin.room.index', compact('rooms'));
    }

    /**
     * Menampilkan form untuk membuat ruangan baru.
     */
    public function roomCreate()
    {
        return view('admin.room.create');
    }

    /**
     * Menyimpan ruangan baru ke database.
     */
    public function roomStore(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:100|unique:room_infos,nama_ruangan',
            'deskripsi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->only(['nama_ruangan', 'deskripsi', 'kapasitas', 'fasilitas']);
        
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('room_photos', 'public');
            $data['foto'] = $path;
        }
        
        RoomInfo::create($data);
        
        return redirect()->route('admin.room.index')->with('success', 'Ruangan baru berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan form untuk mengedit informasi ruangan.
     */
    public function roomEdit(RoomInfo $room)
    {
        return view('admin.room.edit', compact('room'));
    }

    /**
     * Memperbarui informasi ruangan di database.
     */
    public function roomUpdate(Request $request, RoomInfo $room)
    {
        $request->validate([
            'nama_ruangan' => ['required', 'string', 'max:100', Rule::unique('room_infos')->ignore($room->id)],
            'deskripsi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->only(['nama_ruangan', 'deskripsi', 'kapasitas', 'fasilitas']);
            
        if ($request->hasFile('foto')) {
            if ($room->foto) {
                Storage::disk('public')->delete($room->foto);
            }
            $path = $request->file('foto')->store('room_photos', 'public');
            $data['foto'] = $path;
        }

        $room->update($data);
            
        return redirect()->route('admin.room.index')->with('success', 'Informasi ruangan berhasil diperbarui.');
    }

    /**
     * Menghapus ruangan dari database.
     */
    public function roomDestroy(RoomInfo $room)
    {
        if ($room->foto) {
            Storage::delete('public/' . $room->foto);
        }
        $room->delete();
        return redirect()->route('admin.room.index')->with('success', 'Ruangan berhasil dihapus.');
    }

    /**
     * Menampilkan halaman manajemen kalender.
     */
    public function showCalendarManagement()
    {
        $blockedDates = BlockedDate::pluck('date')->map(fn ($date) => $date->format('Y-m-d'));
        return view('admin.calendar.management', compact('blockedDates'));
    }

    /**
     * Menyimpan tanggal yang diblokir.
     */
    public function storeBlockedDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d|unique:blocked_dates,date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        BlockedDate::create(['date' => $request->date]);
        return response()->json(['message' => 'Tanggal berhasil diblokir.']);
    }

    /**
     * Menghapus tanggal yang diblokir.
     */
    public function destroyBlockedDate(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'date' => 'required|date_format:Y-m-d|exists:blocked_dates,date',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()->first()], 422);
        }

        BlockedDate::where('date', $request->date)->delete();
        return response()->json(['message' => 'Blokir tanggal berhasil dibuka.']);
    }

    /**
     * Menampilkan daftar dinas/instansi.
     */
    public function dinasIndex()
    {
        $dinas = Dinas::withCount('reservations')->orderBy('name')->paginate(10);
        return view('admin.dinas.index', ['dinas' => $dinas]);
    }

    /**
     * Menampilkan form untuk membuat dinas baru.
     */
    public function dinasCreate()
    {
        return view('admin.dinas.create');
    }

    /**
     * Menyimpan dinas baru ke database.
     */
    public function dinasStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:dinas,name']);
        Dinas::create($request->only('name'));
        return redirect()->route('admin.dinas.index')->with('success', 'Instansi/Dinas berhasil ditambahkan.');
    }
    
    /**
     * Menampilkan form untuk mengedit dinas.
     */
    public function dinasEdit(Dinas $dina)
    {
        return view('admin.dinas.edit', compact('dina'));
    }

    /**
     * Memperbarui data dinas di database.
     */
    public function dinasUpdate(Request $request, Dinas $dina)
    {
        $request->validate(['name' => ['required', 'string', 'max:255', Rule::unique('dinas')->ignore($dina->id)]]);
        $dina->update($request->only('name'));
        return redirect()->route('admin.dinas.index')->with('success', 'Informasi Instansi/Dinas berhasil diperbarui.');
    }

    /**
     * Menghapus dinas dari database.
     */
    public function dinasDestroy(Dinas $dina)
    {
        if ($dina->reservations()->exists()) {
            return redirect()->route('admin.dinas.index')->with('error', 'Instansi/Dinas tidak dapat dihapus karena sudah digunakan dalam data reservasi.');
        }
        $dina->delete();
        return redirect()->route('admin.dinas.index')->with('success', 'Instansi/Dinas berhasil dihapus.');
    }

    /**
     * Menampilkan daftar pengguna.
     */
    public function usersIndex()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    /**
     * Menampilkan form untuk membuat pengguna baru.
     */
    public function usersCreate()
    {
        return view('admin.users.create');
    }

    /**
     * Menyimpan pengguna baru ke database.
     */
    public function usersStore(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => 'required|string|size:18|unique:users,nip',
            'email' => 'required|string|email|max:255|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        User::create([
            'name' => $request->name,
            'nip' => $request->nip,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        return redirect()->route('admin.users.index')->with('success', 'Pengguna baru berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit pengguna.
     */
    public function usersEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

    /**
     * Memperbarui data pengguna di database.
     */
    public function usersUpdate(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'nip' => ['required', 'string', 'size:18', Rule::unique('users')->ignore($user->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => 'nullable|string|min:8|confirmed',
        ]);

        $data = $request->only('name', 'nip', 'email');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        $user->update($data);
        return redirect()->route('admin.users.index')->with('success', 'Data pengguna berhasil diperbarui.');
    }

    /**
     * Menghapus pengguna dari database.
     */
    public function usersDestroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    /**
     * Menyetujui registrasi pengguna.
     */
    public function approveUser(User $user)
    {
        if ($user->status !== 'approved') {
            $user->update(['status' => 'approved']);
            
            // Kirim email notifikasi ke pengguna
            Mail::to($user->email)->send(new UserApprovedNotification($user));
        }

        return back()->with('success', 'Pengguna ' . $user->name . ' telah disetujui.');
    }

    /**
     * Reject a user registration.
     */
    public function rejectUser(User $user)
    {
        $user->update(['status' => 'rejected']);
        // Anda bisa menambahkan notifikasi email penolakan ke user di sini jika diperlukan
        return back()->with('success', 'Pengguna ' . $user->name . ' telah ditolak.');
    }

    /**
     * Menampilkan halaman laporan statistik.
     */
    public function reports()
    {
        $todayVisitors = Reservation::where('status', Reservation::STATUS_APPROVED)
            ->whereDate('tanggal', today())
            ->distinct('user_id')
            ->count();

        $monthlyVisitors = Reservation::where('status', Reservation::STATUS_APPROVED)
            ->whereMonth('tanggal', today()->month)
            ->whereYear('tanggal', today()->year)
            ->distinct('user_id')
            ->count();

        $totalVisitors = User::count();

        $reservationsByRoom = RoomInfo::withCount(['reservations' => function ($query) {
                $query->where('status', Reservation::STATUS_APPROVED);
            }])
            ->orderBy('reservations_count', 'desc')
            ->get();

        $reservationsByDinas = Dinas::withCount(['reservations' => function ($query) {
                $query->where('status', Reservation::STATUS_APPROVED);
            }])
            ->orderBy('reservations_count', 'desc')
            ->get();

        return view('admin.reports.index', compact(
            'todayVisitors',
            'monthlyVisitors',
            'totalVisitors',
            'reservationsByRoom',
            'reservationsByDinas'
        ));
    }
}