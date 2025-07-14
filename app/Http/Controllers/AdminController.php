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
use Illuminate\support\Facades\Storage;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Facades\Excel;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function adminIndex()
    {
        $admins = \App\Models\Admin::paginate(10);
        return view('admin.admins.index', compact('admins'));
    }

    public function adminCreate()
    {
        $dinas = Dinas::orderBy('name')->get();
        return view('admin.admins.create', compact('dinas'));
    }

    public function adminStore(Request $request)
    {
        $request->validate([
            'username' => 'required|string|max:255|unique:admins,username',
            'email' => 'required|string|email|max:255|unique:admins,email',
            'password' => 'required|string|min:8|confirmed',
            'role' => ['required', Rule::in(['admin', 'superadmin'])],
            'instansi_id' => 'required_if:role,admin|nullable|exists:dinas,id',
        ]);

        \App\Models\Admin::create([
            'username' => $request->username,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role,
            'instansi_id' => $request->role === 'superadmin' ? null : $request->instansi_id,
        ]);

        return redirect()->route('admin.admins.index')->with('success', 'Admin baru berhasil ditambahkan.');
    }


    public function adminEdit(\App\Models\Admin $admin)
    {
        $dinas = auth()->guard('admin')->user()->role === 'superadmin' ? Dinas::orderBy('name')->get() : null;
        return view('admin.admins.edit', compact('admin', 'dinas'));
    }


    public function adminUpdate(Request $request, \App\Models\Admin $admin)
    {
        $request->validate([
            'username' => ['required', 'string', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'email' => ['required', 'string', 'email', 'max:255', Rule::unique('admins')->ignore($admin->id)],
            'password' => 'nullable|string|min:8|confirmed',
            'role' => auth()->guard('admin')->user()->role === 'superadmin' ? ['required', Rule::in(['admin', 'superadmin'])] : 'nullable',
            'instansi_id' => auth()->guard('admin')->user()->role === 'superadmin' && $request->role === 'admin' ? 'required|exists:dinas,id' : 'nullable',
        ]);

        $data = $request->only('username', 'email');
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        if (auth()->guard('admin')->user()->role === 'superadmin') {
            $data['role'] = $request->role;
            $data['instansi_id'] = $request->role === 'admin' ? $request->instansi_id : null;
        }

        $admin->update($data);

        return redirect()->route('admin.admins.index')->with('success', 'Informasi admin berhasil diperbarui.');
    }

    public function adminDestroy(\App\Models\Admin $admin)
    {
        if ($admin->role === 'superadmin' && \App\Models\Admin::where('role', 'superadmin')->count() === 1) {
            return redirect()->route('admin.admins.index')->with('error', 'Tidak dapat menghapus superadmin terakhir.');
        }
        $admin->delete();
        return redirect()->route('admin.admins.index')->with('success', 'Admin berhasil dihapus.');
    }
    public function dashboard()
    {
        $admin = auth()->guard('admin')->user();
        $query = Reservation::query();

        if ($admin->role !== 'superadmin') {
            $query->whereHas('roomInfo', function ($q) use ($admin) {
                $q->where('instansi_id', $admin->instansi_id);
            });
        }

        $pendingCount = (clone $query)->where('status', Reservation::STATUS_PENDING)->count();
        $approvedCount = (clone $query)->where('status', Reservation::STATUS_APPROVED)->count();
        $completedCount = (clone $query)->where('status', Reservation::STATUS_COMPLETED)->count();
        $userCount = User::count();

        $reservations = (clone $query)->with(['user', 'roomInfo'])
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();
            
        return view('admin.dashboard', compact('pendingCount', 'approvedCount', 'completedCount', 'reservations', 'userCount'));
    }

    public function reservations(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $reservationsQuery = Reservation::with(['user', 'roomInfo', 'dinas']);
        $roomsQuery = RoomInfo::query();

        // Filter reservasi dan ruangan berdasarkan instansi admin
        if ($admin->role !== 'superadmin') {
            $reservationsQuery->whereHas('roomInfo', function ($query) use ($admin) {
                $query->where('instansi_id', $admin->instansi_id);
            });
            $roomsQuery->where('instansi_id', $admin->instansi_id);
        }

        // Filter berdasarkan tanggal
        if ($request->filled('date')) {
            $reservationsQuery->whereDate('tanggal', $request->date);
        }

        // Filter berdasarkan ID ruangan dari dropdown
        if ($request->filled('room_id')) {
            $reservationsQuery->where('room_info_id', $request->room_id);
        }

        $reservations = $reservationsQuery->orderBy('tanggal', 'desc')->paginate(10)->withQueryString();
        
        // Ambil daftar ruangan yang sudah difilter untuk dropdown
        $rooms = $roomsQuery->orderBy('nama_ruangan')->get();

        return view('admin.reservations.index', compact('reservations', 'rooms'));
    }

    public function exportReservations()
    {
        $fileName = 'laporan-riwayat-reservasi-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ReservationExport(), $fileName);
    }

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

        if ($reservation->user) { // Pastikan user ada sebelum kirim email
            if ($newStatus === Reservation::STATUS_APPROVED && $oldStatus !== Reservation::STATUS_APPROVED) {
                Mail::to($reservation->user->email)->send(new ReservationApprovedUserNotification($reservation));
            }
    
            if ($newStatus === Reservation::STATUS_REJECTED && $oldStatus !== Reservation::STATUS_REJECTED) {
                Mail::to($reservation->user->email)->send(new ReservationRejectedUserNotification($reservation));
            }
        }
        
        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back()->with('success', 'Reservasi berhasil dihapus.');
    }

    public function checkout(Reservation $reservation)
    {
        if ($reservation->admin_id === null) {
            return back()->with('error', 'Hanya reservasi yang dibuat oleh admin yang bisa di-checkout dari halaman ini.');
        }

        if ($reservation->status !== Reservation::STATUS_APPROVED) {
            return back()->with('error', 'Hanya reservasi yang disetujui yang bisa di-checkout.');
        }

        $reservation->update([
            'status' => Reservation::STATUS_COMPLETED,
            'checked_out_at' => now(),
        ]);

        return back()->with('success', 'Reservasi berhasil di-checkout.');
    }

    public function roomIndex()
    {
        $admin = auth()->guard('admin')->user();
        $roomsQuery = RoomInfo::query();

        if ($admin->role !== 'superadmin') {
            $roomsQuery->where('instansi_id', $admin->instansi_id);
        }

        $rooms = $roomsQuery->paginate(10);
        return view('admin.room.index', compact('rooms'));
    }


    public function roomCreate()
    {
        $dinas = auth()->guard('admin')->user()->role === 'superadmin' ? Dinas::orderBy('name')->get() : null;
        return view('admin.room.create', compact('dinas'));
    }

    public function roomStore(Request $request)
    {
        $admin = auth()->guard('admin')->user();
        $isSuperAdmin = $admin->role === 'superadmin';

        $request->validate([
            'nama_ruangan' => 'required|string|max:100|unique:room_infos,nama_ruangan',
            'deskripsi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'instansi_id' => $isSuperAdmin ? 'required|exists:dinas,id' : 'nullable',
        ]);

        $data = $request->only(['nama_ruangan', 'deskripsi', 'kapasitas', 'fasilitas']);

        if ($isSuperAdmin) {
            $data['instansi_id'] = $request->instansi_id;
        } else {
            $data['instansi_id'] = $admin->instansi_id;
        }

        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('room_photos', 'public');
            $data['foto'] = $path;
        }

        RoomInfo::create($data);

        return redirect()->route('admin.room.index')->with('success', 'Ruangan baru berhasil ditambahkan.');
    }

    public function roomEdit(RoomInfo $room)
    {
        $dinas = auth()->guard('admin')->user()->role === 'superadmin' ? Dinas::orderBy('name')->get() : null;
        return view('admin.room.edit', compact('room', 'dinas'));
    }

    public function roomUpdate(Request $request, RoomInfo $room)
    {
        $admin = auth()->guard('admin')->user();
        $isSuperAdmin = $admin->role === 'superadmin';

        if (!$isSuperAdmin && $room->instansi_id !== $admin->instansi_id) {
            return redirect()->route('admin.room.index')->with('error', 'Anda tidak memiliki hak untuk mengubah ruangan ini.');
        }

        $request->validate([
            'nama_ruangan' => ['required', 'string', 'max:100', Rule::unique('room_infos')->ignore($room->id)],
            'deskripsi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'instansi_id' => $isSuperAdmin ? 'required|exists:dinas,id' : 'nullable',
        ]);

        $data = $request->only(['nama_ruangan', 'deskripsi', 'kapasitas', 'fasilitas']);

        if ($isSuperAdmin) {
            $data['instansi_id'] = $request->instansi_id;
        }

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

    public function roomDestroy(RoomInfo $room)
    {
        if ($room->foto) {
            Storage::delete('public/' . $room->foto);
        }
        $room->delete();
        return redirect()->route('admin.room.index')->with('success', 'Ruangan berhasil dihapus.');
    }

    public function showCalendarManagement()
    {
        $blockedDates = BlockedDate::pluck('date')->map(fn ($date) => $date->format('Y-m-d'));
        return view('admin.calendar.management', compact('blockedDates'));
    }

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

    public function dinasIndex()
    {
        $dinas = Dinas::withCount('reservations')->orderBy('name')->paginate(10);
        return view('admin.dinas.index', ['dinas' => $dinas]);
    }

    public function dinasCreate()
    {
        return view('admin.dinas.create');
    }

    public function dinasStore(Request $request)
    {
        $request->validate(['name' => 'required|string|max:255|unique:dinas,name']);
        Dinas::create($request->only('name'));
        return redirect()->route('admin.dinas.index')->with('success', 'Instansi/Dinas berhasil ditambahkan.');
    }
    
    public function dinasEdit(Dinas $dina)
    {
        return view('admin.dinas.edit', compact('dina'));
    }

    public function dinasUpdate(Request $request, Dinas $dina)
    {
        $request->validate(['name' => ['required', 'string', 'max:255', Rule::unique('dinas')->ignore($dina->id)]]);
        $dina->update($request->only('name'));
        return redirect()->route('admin.dinas.index')->with('success', 'Informasi Instansi/Dinas berhasil diperbarui.');
    }

    public function dinasDestroy(Dinas $dina)
    {
        if ($dina->reservations()->exists()) {
            return redirect()->route('admin.dinas.index')->with('error', 'Instansi/Dinas tidak dapat dihapus karena sudah digunakan dalam data reservasi.');
        }
        $dina->delete();
        return redirect()->route('admin.dinas.index')->with('success', 'Instansi/Dinas berhasil dihapus.');
    }

    public function usersIndex()
    {
        $users = User::paginate(10);
        return view('admin.users.index', compact('users'));
    }

    public function usersCreate()
    {
        return view('admin.users.create');
    }

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

    public function usersEdit(User $user)
    {
        return view('admin.users.edit', compact('user'));
    }

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

    public function usersDestroy(User $user)
    {
        $user->delete();
        return redirect()->route('admin.users.index')->with('success', 'Pengguna berhasil dihapus.');
    }

    public function approveUser(User $user)
    {
        if ($user->status !== 'approved') {
            $user->update(['status' => 'approved']);
            
            Mail::to($user->email)->send(new UserApprovedNotification($user));
        }

        return back()->with('success', 'Pengguna ' . $user->name . ' telah disetujui.');
    }

    public function rejectUser(User $user)
    {
        $user->update(['status' => 'rejected']);
        return back()->with('success', 'Pengguna ' . $user->name . ' telah ditolak.');
    }

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

    public function showReservationForm()
    {
        $admin = auth()->guard('admin')->user();
        $roomsQuery = RoomInfo::query();

        if ($admin->role !== 'superadmin') {
            $roomsQuery->where('instansi_id', $admin->instansi_id);
        }

        $rooms = $roomsQuery->get();
        $blockedDates = BlockedDate::pluck('date')->map->format('Y-m-d')->toArray();
        $dinas = Dinas::orderBy('name')->get();

        return view('admin.reservations.create', compact('rooms', 'blockedDates', 'dinas', 'admin'));
    }

    public function storeReservation(Request $request)
    {
        $validatedData = $request->validate([
            'room_info_id' => 'required|exists:room_infos,id',
            'dinas_id' => 'required|exists:dinas,id',
            'nama' => 'required|string|max:100',
            'kontak' => 'required|string|max:100',
            'tanggal' => 'required|date|after_or_equal:today',
            'jam_mulai' => 'required|date_format:H:i',
            'jam_selesai' => 'required|date_format:H:i|after:jam_mulai',
            'keperluan' => 'required|string|max:255',
            'fasilitas' => 'nullable|array',
            'fasilitas.*' => 'string|max:100',
        ]);
        
        $admin = auth()->guard('admin')->user();
        
        if (Reservation::hasConflict($validatedData['tanggal'], $validatedData['jam_mulai'], $validatedData['jam_selesai'], $validatedData['room_info_id'])) {
            return back()->with('error', 'Ruangan sudah dibooking pada jam tersebut. Silakan pilih jam lain.');
        }
        
        $reservationData = [
            'admin_id' => $admin->id,
            'user_id' => null,
            'room_info_id' => $validatedData['room_info_id'],
            'dinas_id' => $validatedData['dinas_id'],
            'nama' => $validatedData['nama'],
            'kontak' => $validatedData['kontak'],
            'tanggal' => $validatedData['tanggal'],
            'jam_mulai' => $validatedData['jam_mulai'],
            'jam_selesai' => $validatedData['jam_selesai'],
            'keperluan' => $validatedData['keperluan'],
            'status' => Reservation::STATUS_APPROVED,
            'fasilitas_terpilih' => isset($validatedData['fasilitas']) ? implode(',', $validatedData['fasilitas']) : null,
        ];

        Reservation::create($reservationData);

        return redirect()->route('admin.reservations.index')->with('success', 'Reservasi baru berhasil dibuat dan otomatis disetujui.');
    }
}