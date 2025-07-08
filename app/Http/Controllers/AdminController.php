<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\BlockedDate;
use App\Models\User;
use App\Models\Dinas;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Mail;
use App\Mail\ReservationApprovedUserNotification;
use App\Mail\ReservationRejectedUserNotification; 
use App\Exports\ReservationExport; 
use Maatwebsite\Excel\Facades\Excel; 


class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingCount = Reservation::where('status', Reservation::STATUS_PENDING)->count();
        $approvedCount = Reservation::where('status', Reservation::STATUS_APPROVED)->count();
        $completedCount = Reservation::where('status', Reservation::STATUS_COMPLETED)->count(); // TAMBAHKAN INI
        $userCount = User::count();

        $reservations = Reservation::with(['user', 'roomInfo']) // Eager load roomInfo
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.dashboard', compact('pendingCount', 'approvedCount', 'completedCount', 'reservations', 'userCount')); // PERBARUI INI
    }

    public function reservations()
    {
        $reservations = Reservation::with(['user', 'roomInfo']) // Eager load roomInfo
            ->orderBy('tanggal', 'desc')
            ->paginate(10);
            
        return view('admin.reservations.index', compact('reservations'));
    }

    /**
     * Menangani permintaan untuk export data reservasi ke Excel.
     */
    public function exportReservations()
    {
        $fileName = 'laporan-riwayat-reservasi-' . date('Y-m-d') . '.xlsx';
        return Excel::download(new ReservationExport(), $fileName);
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(Reservation::statusOptions()))],
            // Alasan wajib diisi jika statusnya 'rejected'
            'rejection_reason' => 'required_if:status,rejected|string|nullable',
        ]);
        
        $oldStatus = $reservation->status;
        $newStatus = $request->status;
        
        $updateData = ['status' => $newStatus];

        // Simpan alasan penolakan jika statusnya 'rejected', jika tidak, hapus alasan.
        if ($newStatus === Reservation::STATUS_REJECTED) {
            $updateData['rejection_reason'] = $request->rejection_reason;
        } else {
            $updateData['rejection_reason'] = null;
        }

        $reservation->update($updateData);

        // Memuat relasi user jika belum termuat
        $reservation->load('user');

        // Kirim email jika status diubah menjadi 'approved'
        if ($newStatus === Reservation::STATUS_APPROVED && $oldStatus !== Reservation::STATUS_APPROVED) {
            Mail::to($reservation->user->email)->send(new ReservationApprovedUserNotification($reservation));
        }

        // Kirim email jika status diubah menjadi 'rejected'
        if ($newStatus === Reservation::STATUS_REJECTED && $oldStatus !== Reservation::STATUS_REJECTED) {
            Mail::to($reservation->user->email)->send(new ReservationRejectedUserNotification($reservation));
        }
        
        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back()->with('success', 'Reservasi berhasil dihapus.');
    }

    // == START: Room Management CRUD ==
    public function roomIndex()
    {
        $rooms = RoomInfo::paginate(10);
        return view('admin.room.index', compact('rooms'));
    }

    public function roomCreate()
    {
        return view('admin.room.create');
    }

    public function roomStore(Request $request)
    {
        $request->validate([
            'nama_ruangan' => 'required|string|max:100|unique:room_infos,nama_ruangan',
            'deskripsi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg|max:1024', // Tambahkan validasi QR Code
        ]);
        
        $data = $request->only(['nama_ruangan', 'deskripsi', 'kapasitas', 'fasilitas']);
        
        if ($request->hasFile('foto')) {
            $path = $request->file('foto')->store('room_photos', 'public');
            $data['foto'] = $path;
        }

        if ($request->hasFile('qr_code')) { // Tambahkan logika untuk menyimpan QR Code
            $path = $request->file('qr_code')->store('room_qrcodes', 'public');
            $data['qr_code_path'] = $path;
        }
        
        RoomInfo::create($data);
        
        return redirect()->route('admin.room.index')->with('success', 'Ruangan baru berhasil ditambahkan.');
    }
    
    public function roomEdit(RoomInfo $room)
    {
        return view('admin.room.edit', compact('room'));
    }

    public function roomUpdate(Request $request, RoomInfo $room)
    {
        $request->validate([
            'nama_ruangan' => ['required', 'string', 'max:100', Rule::unique('room_infos')->ignore($room->id)],
            'deskripsi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            'qr_code' => 'nullable|image|mimes:jpeg,png,jpg|max:1024',
            'survey_link' => 'nullable|url|max:255', 
        ]);
        
    $data = $request->only(['nama_ruangan', 'deskripsi', 'kapasitas', 'fasilitas', 'survey_link']);
        
            if ($request->hasFile('foto')) {
                if ($room->foto) {
                    Storage::disk('public')->delete($room->foto);
                }
                $path = $request->file('foto')->store('room_photos', 'public');
                $data['foto'] = $path;
            }
            
            if ($request->hasFile('qr_code')) {
                if ($room->qr_code_path) {
                    Storage::disk('public')->delete($room->qr_code_path);
                }
                $path = $request->file('qr_code')->store('room_qrcodes', 'public');
                $data['qr_code_path'] = $path;
            }

            $room->update($data);
            
            return redirect()->route('admin.room.index')->with('success', 'Informasi ruangan berhasil diperbarui.');
        }

    public function roomDestroy(RoomInfo $room)
    {
        if ($room->foto) {
            Storage::delete('public/' . $room->foto);
        }
        if ($room->qr_code_path) { // Hapus juga QR code jika ada
            Storage::disk('public')->delete($room->qr_code_path);
        }
        $room->delete();
        return redirect()->route('admin.room.index')->with('success', 'Ruangan berhasil dihapus.');
    }

    public function editRoom()
    {
        $room = RoomInfo::firstOrFail(); // Menggunakan firstOrFail untuk memastikan data ada
        return view('admin.room.edit', compact('room'));
    }

    public function updateRoom(Request $request)
    {
        $room = RoomInfo::firstOrFail();
        
        $request->validate([
            'nama_ruangan' => 'required|string|max:100',
            'deskripsi' => 'required|string',
            'kapasitas' => 'required|integer|min:1',
            'fasilitas' => 'required|string',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);
        
        $data = $request->only(['nama_ruangan', 'deskripsi', 'kapasitas', 'fasilitas']);
        
        if ($request->hasFile('foto')) {
            // Hapus foto lama jika ada
            if ($room->foto) {
                Storage::delete('public/' . $room->foto);
            }
            
            $path = $request->file('foto')->store('room_photos', 'public');
            $data['foto'] = $path;
        }
        
        $room->update($data);
        
        return redirect()->route('admin.room.edit')->with('success', 'Informasi ruangan berhasil diperbarui.');
    }

    public function showCalendarManagement()
    {
        $blockedDates = BlockedDate::pluck('date')->map(function ($date) {
            return $date->format('Y-m-d');
        });

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

    // == START: METODE BARU UNTUK MANAJEMEN DINAS ==
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
        $request->validate([
            'name' => 'required|string|max:255|unique:dinas,name',
        ]);

        Dinas::create($request->only('name'));

        return redirect()->route('admin.dinas.index')->with('success', 'Instansi/Dinas berhasil ditambahkan.');
    }
    
    public function dinasEdit(Dinas $dina)
    {
        return view('admin.dinas.edit', compact('dina'));
    }

    public function dinasUpdate(Request $request, Dinas $dina)
    {
        $request->validate([
            'name' => ['required', 'string', 'max:255', Rule::unique('dinas')->ignore($dina->id)],
        ]);

        $dina->update($request->only('name'));

        return redirect()->route('admin.dinas.index')->with('success', 'Informasi Instansi/Dinas berhasil diperbarui.');
    }

    public function dinasDestroy(Dinas $dina)
    {
        // Mencegah penghapusan jika ada reservasi terkait
        if ($dina->reservations()->exists()) {
            return redirect()->route('admin.dinas.index')->with('error', 'Instansi/Dinas tidak dapat dihapus karena sudah digunakan dalam data reservasi.');
        }

        $dina->delete();
        return redirect()->route('admin.dinas.index')->with('success', 'Instansi/Dinas berhasil dihapus.');
    }
    // == END: METODE BARU UNTUK MANAJEMEN DINAS ==

    // == METODE BARU UNTUK MANAJEMEN USER ==
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
}