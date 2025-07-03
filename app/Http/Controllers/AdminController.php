<?php

namespace App\Http\Controllers;

use App\Models\Reservation;
use App\Models\RoomInfo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use App\Models\BlockedDate;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;


class AdminController extends Controller
{
    public function dashboard()
    {
        $pendingCount = Reservation::where('status', Reservation::STATUS_PENDING)->count();
        $approvedCount = Reservation::where('status', Reservation::STATUS_APPROVED)->count();
        
        $reservations = Reservation::with('user')
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();
            
        return view('admin.dashboard', compact('pendingCount', 'approvedCount', 'reservations'));
    }

    public function reservations()
    {
        $reservations = Reservation::with('user')
            ->orderBy('tanggal', 'desc')
            ->paginate(10); // Menggunakan paginasi
            
        return view('admin.reservations.index', compact('reservations'));
    }

    public function updateStatus(Request $request, Reservation $reservation)
    {
        $request->validate([
            'status' => ['required', Rule::in(array_keys(Reservation::statusOptions()))],
        ]);
        
        $reservation->update(['status' => $request->status]);
        
        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function destroy(Reservation $reservation)
    {
        $reservation->delete();
        return back()->with('success', 'Reservasi berhasil dihapus.');
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